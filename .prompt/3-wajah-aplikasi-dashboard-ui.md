Baca semua perintah lalu rencanakan urutan eksekusinya dan lakukan eksekusi. 
Terapkan rules @design-system-pwa untuk membangun Dashboard yang bisa diakses tanpa login dan berlaku sebagai homepage dari aplikasi dengan logic Offline-First..
Hubungkan UI Card dan Table ke skill @finance-manager. 
Gunakan #[Computed] untuk menarik data dari database SQLite. 
Gunakan ApexCharts untuk visualisasi data dari properti computed tersebut.
Gunakan TransactionForm dan pastikan validasi muncul secara real-time di bawah input nominal menggunakan komponen pesan error dari TallCraft UI.
Detail UI:
Header: Logo 'DuitKu' di kiri, ThemeToggle (Sun/Moon icon dari Blade Heroicons) di kanan menggunakan Alpine $persist.
Top Section (Ringkasan): - Kartu 1: 'Total Saldo' (Background bg-slate-900, teks putih, Ikon Wallet).
Kartu 2: 'Pemasukan' (Background putih/dark, angka hijau).
Kartu 3: 'Pengeluaran' (Background putih/dark, angka merah).
Middle Section (Grid 2 Kolom): - Kiri (Input): Card dengan  TallCraftUI Tabs ('Pengeluaran' | 'Pemasukan'). masing masing tasb Ada Input Nominal, Kategori (dropdown), Tanggal, dan Tombol Simpan (Warna Indigo). Gunakan TransactionForm Object untuk logic.
Kanan (Chart): Card 'Distribusi Pengeluaran' dengan Donut Chart di tengahnya menggunakan ApexCharts.
Bottom Section: Tallcraft UI Table 'Riwayat Transaksi'. Tampilkan Tanggal, Kategori, dan Nominal (Merah utk expense, Hijau utk income).
Teknis: Gunakan properti '#[Computed]' untuk summary, formatRupiah.
**Data Source:** Saat komponen `mount`, cek IndexedDB. Jika kosong, fetch dari SQLite dan simpan ke IndexedDB.
**Visual:** Tampilkan badge 'Koneksi Terputus' di header jika `isOffline` true.
**Chart:** ApexCharts harus membaca data dari IndexedDB (Client-side) agar grafik tetap interaktif meski tanpa sinyal.
**Form:** Saat klik 'Simpan', data harus masuk ke IndexedDB secara instan (Optimistic UI) sebelum mencoba sinkronisasi ke server.