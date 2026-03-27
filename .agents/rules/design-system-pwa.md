---
name: design-system-pwa
description: Aturan UI/UX dan arsitektur TALL Stack v4 PWA
alwaysApply: true
---
# Design System Rules (TALL Stack PWA) - Update v1.2

## Core Implementation
- **Alpine Shorthand Fix**: Pada komponen Blade (seperti TallCraft UI), gunakan `x-bind:class` alih-alih shorthand `:class` untuk menghindari potensi error parsing oleh engine Blade.
- **Global DB Access**: Selalu ekspos instance Dexie ke `window.db` di `app.js` agar dapat diakses langsung oleh komponen Alpine tanpa import ulang.

## Core Updates (Laravel 12 / Livewire 4)
- **Framework v4 Integration**: Pastikan `livewire/livewire` menggunakan versi `^4.0`.
- **Alpine.js Fix**: Daftarkan plugin Alpine (seperti `$persist`) di dalam event `livewire:init` di `app.js` guna menghindari error `Cannot redefine property` pada re-hydration.
- **Tailwind 4 Strategy**: Gunakan `@import "tailwindcss";` dan blok `@theme` di `app.css`. Hindari penggunaan `tailwind.config.js` karena v4 berbasis CSS-first.
- **Dark Mode Syntax**: Gunakan `@variant dark (&:where(.dark, .dark *))` untuk mendukung strategi class-based dark mode pada Tailwind 4.

## Offline-First Logic (Dexie.js)
- **Primary Source**: UI harus selalu membaca data dari IndexedDB (Dexie) terlebih dahulu.
- **Sync Column**: Setiap tabel IndexedDB wajib memiliki kolom `synced` (0/1) untuk melacak status sinkronisasi ke server SQLite.
- **Initial Seeding**: Gunakan pola "Fetch if Empty" saat mount komponen (Dexie -> SQLite backup).

## Komponen UI
- Gunakan **TallCraft UI** untuk Card, Button, Input, Select, Tabs, Table, dan Badge.
- Gunakan prefix `tc-` untuk komponen TallCraft UI (misal: `<x-tc-card />`) untuk menghindari konflik dengan Breeze/Jetstream.
- **Icons**: Gunakan Blade Heroicons.

## Visualisasi Data
- Gunakan **ApexCharts.js** via Alpine integration dengan strategi **'Single Container'**.
- Bungkus inisialisasi di `$nextTick()` untuk mencegah **Hydration Mismatch**.
- Gunakan Alpine watcher: `x-effect="$watch('darkMode', () => renderChart())"`.

## Layout & Styling
- **Navigation**: Aktifkan "SPA Mode" menggunakan `wire:navigate` secara global.
- **ThemeToggle**: Pasang ikon Sun/Moon dari Blade Heroicons di pojok kanan atas.
- **Indikator Koneksi**: Tampilkan status "Offline Mode" jika `Alpine.store('connectivity').isOffline` aktif.