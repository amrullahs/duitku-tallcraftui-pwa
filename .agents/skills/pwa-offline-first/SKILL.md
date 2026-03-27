---
name: pwa-offline-first
description: Alur kerja implementasi fitur offline-first dengan Livewire & Dexie
---
# Skill: PWA Offline-First Implementation (v1.1)

Gunakan alur kerja ini untuk fitur yang memerlukan performa instan dan akses offline.

## 1. Data Initialization (The Seeding Pattern)
Saat komponen `init()`, cek penyimpanan lokal:
- Jika kosong, tarik data awal dari server menggunakan `@json($this->computedProperty)`.
- Simpan data tersebut ke IndexedDB (Dexie) dengan marker `synced: 1`.

## 2. The Optimistic UI Pattern (Create & Delete)
Aksi harus dilakukan di sisi klien terlebih dahulu sebelum sinkronisasi:

### Create:
1. **Local Update**: Update array `offlineData` di Alpine.js secara instan.
2. **Local Persistence**: Simpan ke Dexie dengan marker `synced: 0`.
3. **Reset Form**: Panggil `$wire.form.reset()` untuk mengosongkan input Livewire segera.
4. **Async Sync**: Panggil method Livewire `$wire.syncToServer()` jika `navigator.onLine` true.

### Delete:
1. **Local Delete**: Filter array `offlineData` di Alpine untuk menghapus item secara visual.
2. **Local Persistence**: Hapus dari Dexie menggunakan `db.transactions.delete(id)`.
3. **Async Sync**: Panggil method Livewire `$wire.delete(id)` jika online.

## 3. Chart Persistence
- Render grafik menggunakan data dari IndexedDB agar tetap interaktif saat offline.
- Gunakan palet warna yang luas (`colors: [...]`) untuk mendukung penambahan kategori baru tanpa tabrakan visual.
- Gunakan `updateOptions` untuk sinkronisasi tema (`darkMode`) dan meta-data.
