---
name: finance-manager
description: Mengelola logika data keuangan.
---
# Skill: Finance Manager
Gunakan skill ini untuk membuat Logic Component (Livewire v4 Component) atau Alpine Store.

## Technical Implementation Rules:
1. **Architecture:** Bangun menggunakan **Livewire 4 Volt (Class-based)** dengan integrasi **Alpine.js Store**.
2. **Schema:**
   - `id`: UUID (`Str::uuid()`), 
   - `type`: income/expense, 
   - `amount`: float, 
   - `category`: string, 
   - `date`: Y-m-d.
3. **Reactive Logic:**
   - Gunakan `#[Computed]` untuk data yang **hanya** dirender oleh Blade.
   - Gunakan **Public Properties** (array/string) untuk data yang perlu dibaca oleh **Alpine.js ($wire)** seperti data grafik untuk menghindari keterbatasan scope computed property di client.
   - Buat method `syncData()` untuk memusatkan pembaruan data statistik & grafik setiap kali ada perubahan (Create/Delete).
   - Gunakan **Livewire Form Objects** untuk enkapsulasi validasi `addTransaction`.
   - `deleteTransaction($id)`: Hapus item berdasarkan ID dan panggil `syncData()`.
   - Gunakan `Number::currency($value, 'IDR', 'id')` untuk output keuangan di Blade.
   - Gunakan atribut `#[Locked]` pada ID transaksi untuk keamanan.
4. **Persistence & SPA:**
   - Gunakan database sqlite untuk meyimpan transaksi keuangan dan Alpine `$persist` pada key `duitku-tallcraftui` untuk persistensi ui.
   - Pastikan navigasi mendukung `wire:navigate` (tanpa full reload).
   - Gunakan `@teleport` atau `$dispatch` untuk sinkronisasi state jika diperlukan.    
   - Pastikan penggunaan `$persist` pada Alpine disinkronkan dengan state tema global agar konsisten di seluruh halaman tanpa terkena `Hydration Mismatch`.
5. **Local Database (Dexie.js):**
   - Inisialisasi Dexie store: `db.transactions` dengan skema `++id, uuid, type, amount, category, date, sync_status`.
   - `sync_status`: 0 (pending/offline), 1 (synced).
6. **Hybrid Flow:**
   - **Create:** Simpan ke Dexie dengan `sync_status: 0` -> Trigger Alpine event -> Jika Online, panggil Livewire `syncToServer()`.
   - **Read:** Selalu ambil data dari Dexie untuk dirender di Alpine/Blade (melalui Alpine store) agar instan.
   - **Update/Delete:** Lakukan di Dexie dulu, baru kirim instruksi ke server.
7. **Reactive Logic:**
   - Gunakan `Livewire.dispatch('refresh-data')` hanya setelah sinkronisasi berhasil.
   - Data untuk ApexCharts diambil langsung dari IndexedDB via Alpine `x-init` agar grafik tetap muncul saat offline.
   - Form Validation tetap dilakukan di client (HTML5/Alpine) sebelum masuk ke Form Object Livewire untuk memastikan pengalaman offline yang lancar.