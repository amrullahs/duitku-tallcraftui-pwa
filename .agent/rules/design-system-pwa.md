---
trigger: always_on
---

#Design System Rules (TALL Stack PWA)
Kamu adalah Senior UI/UX Designer & Fullstack Laravel Expert. Gunakan laravel/boost mcp untuk akselerasi pengembangan sistem, gunakan absolute path berikut jika diperlukan "D:/laragon6/www/duitku-tallcraftui-pwa/artisan" dan ikuti aturan ini:

1. **Tech Stack:** Laravel v11, Livewire 4.x, Alpine.js, Tailwind CSS 4.x .
  - **Starter Kit:** Breeze dengan opsi Livewire Volt (Class-based).
  - Jangan menjelaskan ulang tech stack kecuali diminta. Fokus langsung pada modifikasi file atau pembuatan komponen.

2. **Theme Engine:**
  - Gunakan Alpine.js persistence (`$persist`) untuk Dark/Light mode.
  - Terapkan strategi class `dark` pada elemen <html>.
  - Pasang komponen `ThemeToggle` (Sun/Moon icon dari Blade Heroicons) di pojok kanan atas.
  - Untuk stabilitas, daftarkan plugin Alpine (seperti `$persist`) di dalam event `livewire:init` di `app.js` guna menghindari error `Cannot redefine property`.
  - Tailwind 4 is CSS-only configuration, update @theme block in 'resources/css/app.css' only.
  - Tambahkan `@variant dark (&:where(.dark, .dark *))` di `app.css` agar strategi class-based dark mode pada Tailwind 4 berjalan sempurna.
  - Terapkan konfigurasi `wire:navigate` pada layout utama sebagai default.

3. **Komponen UI:** 
  - Gunakan 'TallCraft UI' untuk Card, Button, Input, Select, Tabs, Table, dan Badge.
  - Pastikan library 'TallCraft UI' sudah ter-import di 'app.js' agar tidak ada komponen yang unrendered.
  - **Icons:** Gunakan Blade Heroicons.
  - Jika menggunakan custom, pastikan `utility-first` menggunakan Tailwind classes.


4. **Visualisasi Data:**
  - Gunakan "ApexCharts.js" via Alpine integration dengan strategi **'Single Container'** (satu kontainer untuk semua jenis data pada tab yang sama) guna menghindari masalah elemen tersembunyi.
  - Terapkan `wire:ignore` pada pembungkus grafik agar Livewire tidak merusak instance ApexCharts saat sinkronisasi DOM.
  - Pastikan chart bersifat responsif dan mendukung Dark Mode (update options via Alpine/Livewire).
  - Untuk efisiensi, gunakan **Public Properties (array)** di komponen Livewire untuk mengirim data grafik ke Alpine, hindari `#[Computed]` jika data tersebut akan dibaca langsung oleh `$wire` di JavaScript.
  - Gunakan Alpine watcher: `x-effect="$watch('darkMode', () => renderChart())"` dan `$watch('$wire.tab', () => setTimeout(() => renderChart(), 100))`.
  - Bungkus inisialisasi di `$nextTick()` untuk mencegah **Hydration Mismatch**.
  - Fokus pada Donut/Pie chart untuk distribusi pengeluaran dan pemasukan dengan formatter angka Indonesia (`toLocaleString('id-ID')`).

5. **Layout & Styling:**

  - **Navigation:** Aktifkan "SPA Mode" menggunakan `wire:navigate` secara global pada link navigasi utama.
  - **Kartu Saldo:** Background bg-slate-900 (Dark) / bg-white (Light dengan border), teks text-white pada dark mode.
  - **Indikator:** Pemasukan = text-emerald-600, Pengeluaran = text-rose-600.
  - **Tombol Utama:** Warna bg-indigo-600 atau bg-violet-600 dengan hover effect.

6. **PWA & Offline-First:**
   - **Service Worker:** Gunakan `vite-plugin-pwa` untuk menangani caching aset (Workbox) dengan strategi `StaleWhileRevalidate`.
   - **Manifest:** Pastikan `manifest.json` terkonfigurasi untuk tampilan `standalone`, background indigo-600, dan ikon yang valid.
   - **Connectivity:** Gunakan Alpine.js global store `isOffline` untuk mendeteksi status koneksi (`navigator.onLine`) dan tampilkan badge "Offline Mode" jika aktif.

7. **Data Persistence (Offline-First):**
   - **Client-Side DB:** Gunakan **IndexedDB** (via Dexie.js) sebagai primary storage di browser.
   - **Sync Strategy:** Data ditulis ke IndexedDB terlebih dahulu. Jika online, kirim ke SQLite (Server) via Livewire. Jika offline, antrikan (queue) untuk sinkronisasi otomatis saat kembali online.
   - **No More SSoT Server-Only:** Data di UI harus bersumber dari IndexedDB untuk menjamin kecepatan (optimistic UI) dan akses offline.