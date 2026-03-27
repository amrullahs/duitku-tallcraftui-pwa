---
name: error-fixes
description: Solusi untuk masalah instalasi Tailwind 4 dan Livewire 4
alwaysApply: true
---
# Solusi Error Teknis DuitKu

## 1. Blade Component Syntax Error
- **Issue**: Penggunaan `:class` di dalam komponen Blade (seperti `<x-tc-td>`) terkadang menyebabkan Blade mencoba mengevaluasi ekspresi JavaScript sebagai konstanta PHP.
- **Solution**: Gunakan sintaks lengkap `x-bind:class` untuk memastikan ekspresi diproses oleh Alpine.js di sisi client.

## 2. Node Package ETARGET (Tailwind 4)
- **Issue**: `npm install` gagal dengan error `ETARGET` saat mencoba menginstal `autoprefixer` versi rendah bersama Tailwind 4.
- **Solution**: Selalu pasang `tailwindcss@^4.0.0` dan `@tailwindcss/vite@^4.0.0` serta hapus versi spesifik pada `autoprefixer` (biarkan `latest`) untuk kompatibilitas penuh dengan Vite 6.

## 3. Livewire 4 Hydration Error
- **Issue**: `Cannot redefine property` pada plugin Alpine.
- **Solution**: Pindahkan deklarasi `Alpine.plugin()` ke dalam listener `livewire:init` bukan di tingkat root `app.js`.
