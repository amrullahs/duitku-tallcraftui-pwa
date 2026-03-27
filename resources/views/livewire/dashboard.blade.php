<?php

use App\Livewire\Forms\TransactionForm;
use App\Models\Transaction;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;

new class extends Component
{
    public TransactionForm $form;

    /**
     * Initial SQLite data for Hydration and SEO
     */
    #[Computed]
    public function transactions()
    {
        return Transaction::latest()->limit(50)->get();
    }

    #[Computed]
    public function summary()
    {
        $income = Transaction::where('type', 'income')->sum('amount');
        $expense = Transaction::where('type', 'expense')->sum('amount');

        return [
            'total'   => $income - $expense,
            'income'  => $income,
            'expense' => $expense,
        ];
    }

    /**
     * Sync data from Client (Dexie) to Server (SQLite)
     */
    public function syncToServer($data)
    {
        foreach ($data as $item) {
            // Check if already exist via UUID in payload
            if (!Transaction::where('id', $item['id'])->exists()) {
                Transaction::create([
                    'id'          => $item['id'],
                    'type'        => $item['type'],
                    'amount'      => $item['amount'],
                    'category'    => $item['category'],
                    'description' => $item['description'] ?? '',
                    'date'        => $item['date'],
                    'synced'      => true,
                ]);
            }
        }

        // Refresh the computed properties
        $this->refreshData();
        
        $this->dispatch('data-synced');
    }

    /**
     * Delete transaction from Server
     */
    public function delete($id)
    {
        Transaction::where('id', $id)->delete();
        $this->refreshData();
    }

    public function refreshData()
    {
        unset($this->transactions);
        unset($this->summary);
    }

    /**
     * Single direct store (Server-side)
     */
    public function store()
    {
        $this->form->store();
        $this->form->reset();
        
        $this->dispatch('refresh-data');
    }
    
    public function formatRupiah($value)
    {
        return 'Rp ' . number_format($value, 0, ',', '.');
    }
}; ?>

<div x-data="{
        tab: 'expense',
        offlineData: [],
        isLoading: true,
        summary: { total: 0, income: 0, expense: 0 },
        chart: null,

        async init() {
            // Wait for dependencies (db and ApexCharts)
            let attempts = 0;
            while (!window.db?.transactions || !window.ApexCharts) {
                await new Promise(r => setTimeout(r, 100));
                if (attempts++ > 30) {
                   console.error('DuitKu: Dependencies not loaded after 3s');
                   break;
                }
            }

            await this.loadFromIndexedDB();
            this.renderChart();

            // Watch for connectivity changes to sync
            window.addEventListener('online', () => this.syncQueue());
            
            // Listen to Livewire refresh events
            this.$wire.on('refresh-data', async () => {
                await this.loadFromIndexedDB();
            });
        },

        async loadFromIndexedDB() {
            if (!window.db?.transactions) return;
            
            this.isLoading = true;
            try {
                let txs = await window.db.transactions.orderBy('date').reverse().toArray();
                
                // Seed from SQLite if local is empty
                if (txs.length === 0) {
                    const serverTxs = @json($this->transactions);
                    if (serverTxs.length > 0) {
                        for (const t of serverTxs) {
                            await window.db.transactions.put({
                                id: t.id,
                                type: t.type,
                                amount: t.amount,
                                category: t.category,
                                description: t.description,
                                date: t.date,
                                synced: 1,
                                created_at: t.created_at
                            });
                        }
                        txs = await window.db.transactions.orderBy('date').reverse().toArray();
                    }
                }
                
                this.offlineData = txs;
                this.calculateSummary();
                this.updateChart();
            } catch (err) {
                console.error('Dexie Error:', err);
            } finally {
                this.isLoading = false;
            }
        },

        calculateSummary() {
            let inc = 0, exp = 0;
            this.offlineData.forEach(t => {
                if (t.type === 'income') inc += parseFloat(t.amount);
                else exp += parseFloat(t.amount);
            });
            this.summary = { total: inc - exp, income: inc, expense: exp };
        },

        async saveTransaction() {
            if (!this.$wire.form.amount || !this.$wire.form.category) {
                await this.$wire.store(); 
                return;
            }

            const payload = {
                id: crypto.randomUUID(),
                type: this.tab,
                amount: this.$wire.form.amount,
                category: this.$wire.form.category,
                date: this.$wire.form.date || new Date().toISOString().split('T')[0],
                description: this.$wire.form.description || '',
                synced: 0,
                created_at: new Date()
            };

            // 1. Optimistic Update (UI)
            this.offlineData.unshift(payload);
            this.calculateSummary();
            this.updateChart();

            // 2. Save to Dexie
            await window.db.transactions.add(payload);

            // 3. Reset Livewire form Object
            await this.$wire.form.reset();

            // 4. Try Sync to Livewire if Online
            if (navigator.onLine) {
                await this.syncQueue();
            }
        },

        async deleteTransaction(id) {
            if (!confirm('Hapus transaksi ini?')) return;

            // 1. Optimistic Delete (UI)
            this.offlineData = this.offlineData.filter(t => t.id !== id);
            this.calculateSummary();
            this.updateChart();

            // 2. Delete from Dexie
            await window.db.transactions.delete(id);

            // 3. Attempt Server Delete if Online
            if (navigator.onLine) {
                await this.$wire.delete(id);
            }
        },

        async syncQueue() {
            if (!window.db?.transactions) return;

            const unsynced = await window.db.transactions.where('synced').equals(0).toArray();
            if (unsynced.length > 0) {
                await this.$wire.syncToServer(unsynced);
                
                // Mark as synced in Dexie
                for (const t of unsynced) {
                    await window.db.transactions.update(t.id, { synced: 1 });
                }
                
                await this.loadFromIndexedDB();
            }
        },

        renderChart() {
            if (!window.ApexCharts) return;
            
            const container = document.getElementById('distributionChart');
            if (!container) return;

            const isDark = (this.$store.theme && this.$store.theme.darkMode) || document.documentElement.classList.contains('dark');

            const options = {
                chart: { type: 'donut', height: 350 },
                series: [],
                labels: [],
                colors: ['#4f46e5', '#818cf8', '#a5b4fc', '#c7d2fe', '#6366f1', '#4338ca', '#3730a3'],
                theme: { mode: isDark ? 'dark' : 'light' },
                tooltip: { y: { formatter: val => 'Rp ' + val.toLocaleString('id-ID') } },
                legend: { position: 'bottom' }
            };
            this.chart = new window.ApexCharts(container, options);
            this.chart.render();
            this.updateChart();
        },

        updateChart() {
            if (!this.chart || !window.ApexCharts) return;
            
            const distribution = {};
            this.offlineData.filter(t => t.type === 'expense').forEach(t => {
                distribution[t.category] = (distribution[t.category] || 0) + parseFloat(t.amount);
            });
            
            const labels = Object.keys(distribution);
            const series = Object.values(distribution);
            
            const isDark = (this.$store.theme && this.$store.theme.darkMode) || document.documentElement.classList.contains('dark');
            
            this.chart.updateOptions({
                labels: labels,
                series: series,
                theme: { mode: isDark ? 'dark' : 'light' }
            });
        }
    }"
    class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8"
>
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <x-heroicon-o-banknotes class="w-8 h-8 text-indigo-600" />
                DuitKu Dashboard
            </h1>
            <p class="text-slate-500 dark:text-slate-400">Atur keuanganmu lebih pintar secara offline-first.</p>
        </div>

        <div class="flex items-center gap-3">
            <template x-if="$store.connectivity && $store.connectivity.isOffline">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-rose-100 text-rose-800 dark:bg-rose-900/50 dark:text-rose-200 border border-rose-200 dark:border-rose-800 animate-pulse">
                    <x-heroicon-o-signal-slash class="w-4 h-4 mr-1.5" />
                    Koneksi Terputus
                </span>
            </template>
        </div>
    </div>

    {{-- Top Section: Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <x-tc-card class="bg-slate-900 dark:bg-slate-800 border-none">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm font-medium">Total Saldo</p>
                    <h3 class="text-2xl font-bold text-white mt-1" x-text="'Rp ' + summary.total.toLocaleString('id-ID')">Rp 0</h3>
                </div>
                <div class="p-3 bg-indigo-500/20 rounded-xl">
                    <x-heroicon-o-wallet class="w-6 h-6 text-indigo-400" />
                </div>
            </div>
        </x-tc-card>

        <x-tc-card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Pemasukan</p>
                    <h3 class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1" x-text="'Rp ' + summary.income.toLocaleString('id-ID')">Rp 0</h3>
                </div>
                <div class="p-3 bg-emerald-100 dark:bg-emerald-900/40 rounded-xl">
                    <x-heroicon-o-arrow-trending-up class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                </div>
            </div>
        </x-tc-card>

        <x-tc-card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Pengeluaran</p>
                    <h3 class="text-2xl font-bold text-rose-600 dark:text-rose-400 mt-1" x-text="'Rp ' + summary.expense.toLocaleString('id-ID')">Rp 0</h3>
                </div>
                <div class="p-3 bg-rose-100 dark:bg-rose-900/40 rounded-xl">
                    <x-heroicon-o-arrow-trending-down class="w-6 h-6 text-rose-600 dark:text-rose-400" />
                </div>
            </div>
        </x-tc-card>
    </div>

    {{-- Middle Section: Form Input & Distribution Chart --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Form Card --}}
        <x-tc-card class="overflow-hidden p-0">
            <x-tc-tab class="px-6 pt-4 border-b">
                <x-slot:items>
                    <button @click="tab = 'expense'; $wire.form.type = 'expense'" :class="tab === 'expense' ? 'border-primary text-primary' : 'border-transparent text-slate-500'" class="pb-3 border-b-2 font-medium transition-colors">
                        Pengeluaran
                    </button>
                    <button @click="tab = 'income'; $wire.form.type = 'income'" :class="tab === 'income' ? 'border-primary text-primary' : 'border-transparent text-slate-500'" class="pb-3 border-b-2 font-medium transition-colors">
                        Pemasukan
                    </button>
                </x-slot:items>

                <div class="p-6 space-y-5">
                <div>
                    <x-tc-input 
                        label="Nominal" 
                        prefix="Rp" 
                        wire:model.live="form.amount" 
                        placeholder="0"
                        type="number"
                        x-on:keydown.enter="saveTransaction()"
                    />
                    <x-input-error :messages="$errors->get('form.amount')" class="mt-2" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-tc-select label="Kategori" wire:model.live="form.category">
                            <option value="">Pilih Kategori</option>
                            <template x-if="tab === 'expense'">
                                <optgroup label="Pengeluaran">
                                    <option value="Makan">Makan</option>
                                    <option value="Transport">Transport</option>
                                    <option value="Belanja">Belanja</option>
                                    <option value="Tagihan">Tagihan</option>
                                </optgroup>
                            </template>
                            <template x-if="tab === 'income'">
                                <optgroup label="Pemasukan">
                                    <option value="Gaji">Gaji</option>
                                    <option value="Investasi">Investasi</option>
                                    <option value="Bonus">Bonus</option>
                                </optgroup>
                            </template>
                        </x-tc-select>
                        <x-input-error :messages="$errors->get('form.category')" class="mt-2" />
                    </div>
                    <div>
                        <x-tc-input label="Tanggal" type="date" wire:model.live="form.date" />
                        <x-input-error :messages="$errors->get('form.date')" class="mt-2" />
                    </div>
                </div>

                <x-tc-button 
                    full-width 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 transition-all active:scale-95"
                    @click="saveTransaction()"
                >
                    Simpan Transaksi
                </x-tc-button>
            </div>
            </x-tc-tab>
        </x-tc-card>

        {{-- Chart Card --}}
        <x-tc-card title="Distribusi Pengeluaran">
            <div wire:ignore class="min-h-[350px] flex items-center justify-center">
                <div id="distributionChart" class="w-full"></div>
            </div>
        </x-tc-card>
    </div>

    {{-- Bottom Section: History Table --}}
    <x-tc-card title="Riwayat Transaksi" class="p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <x-tc-table>
                <x-slot name="header">
                    <x-tc-th>Tanggal</x-tc-th>
                    <x-tc-th>Kategori</x-tc-th>
                    <x-tc-th class="text-right">Nominal</x-tc-th>
                    <x-tc-th class="w-10"></x-tc-th>
                </x-slot>

                <template x-for="t in offlineData.slice(0, 10)" :key="t.id">
                    <x-tc-tr class="group">
                        <x-tc-td x-text="new Date(t.date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })"></x-tc-td>
                        <x-tc-td>
                            <span class="font-medium" x-text="t.category"></span>
                        </x-tc-td>
                        <x-tc-td class="text-right font-bold" x-bind:class="t.type === 'income' ? 'text-emerald-600' : 'text-rose-600'">
                            <span x-text="(t.type === 'income' ? '+ ' : '- ') + 'Rp ' + parseFloat(t.amount).toLocaleString('id-ID')"></span>
                            <template x-if="!t.synced">
                                <x-heroicon-o-cloud-arrow-up class="w-3 h-3 inline-block ml-1 opacity-50" />
                            </template>
                        </x-tc-td>
                        <x-tc-td class="text-center">
                            <button @click="deleteTransaction(t.id)" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-colors opacity-0 group-hover:opacity-100">
                                <x-heroicon-o-trash class="w-4 h-4" />
                            </button>
                        </x-tc-td>
                    </x-tc-tr>
                </template>

                <template x-if="offlineData.length === 0">
                    <x-tc-tr>
                        <x-tc-td colspan="4" class="text-center py-8 text-slate-500">
                            Belum ada transaksi. Tambahkan sekarang!
                        </x-tc-td>
                    </x-tc-tr>
                </template>
            </x-tc-table>
        </div>
    </x-tc-card>
</div>
