Baca semua perintah lalu rencanakan urutan eksekusinya dan lakukan eksekusi.
Inisiasi project Laravel v11 baru dengan nama 'duitku-tallcraftui-pwa' dengan PWA Support menggunakan Laravel Breeze dan SQLite sebagai database. Gunakan 'temp-app-install' sebagai temporary folder install.
Install laravel/boost mcp untuk membantu dalam pengembangan.
Stack: Livewire v4.x (Volt Class-based),  Alpine.js, Tailwind CSS 4.x. 
Features: Dark mode support, Konfigurasi `wire:navigate` pada layout utama sebagai default.
Library Tambahan: Install Blade Heroicons, ApexCharts, dan TallCraft UI.
Sesuai rule @design-system-pwa, siapkan komponen UI: Card, Button, Input, Select, Tabs, Table. Pastikan konfigurasi Tailwind 4.x sudah terhubung dengan sistem tema (Dark/Light) menggunakan Alpine `$persist`.
Install vite-plugin-pwa, Setup Vite PWA plugin di `vite.config.js`.
Buat `resources/js/db.js` untuk inisialisasi Dexie.js (IndexedDB).
Daftarkan Service Worker di `app.js`.
Tambahkan logic `isOffline` di Alpine global store.
Sesuai rule @design-system-pwa, pastikan layout utama mendukung `wire:navigate` dan memiliki meta tags PWA."