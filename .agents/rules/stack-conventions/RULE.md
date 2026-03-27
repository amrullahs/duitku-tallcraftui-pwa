---
name: stack-conventions
description: Standar Arsitektur DuitKu PWA (Laravel 12 + Livewire 4)
alwaysApply: true
---
# Stack Conventions - DuitKu PWA

## Arsitektur File & Folder
- **IndexedDB Config**: `resources/js/db.js` (LOKASI SERTIFIKASI UNTUK DEXIE).
- **Service Worker**: Dikelola secara otomatis oleh `vite-plugin-pwa` (Workbox) via `vite.config.js`.
- **Logic Encapsulation**: Gunakan **Livewire Form Objects** (`app/Livewire/Forms`) untuk memisahkan logika validasi dan penyimpanan dari komponen UI (Volt).
- **Models**: Semua model keuangan diletakkan di `app/Models` dengan trait `HasUuids`.

## UI Tools
- **Icons**: Blade Heroicons.
- **Charts**: ApexCharts (Alpine.js integration).
- **Styling**: Tailwind CSS 4.x (CSS-only config).
