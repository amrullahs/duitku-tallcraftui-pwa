---
name: finance-logic
description: Logika bisnis, validasi, dan skema sinkronisasi data keuangan
alwaysApply: false
globs: ["app/Livewire/**", "app/Models/**", "resources/js/db.js"]
---
# Finance Logic & Sync Rules - Update v1.2

## 1. Schema & Models
- **UUID Standard**: Semua entitas keuangan wajib menggunakan `HasUuids`.
- **Sync Marker**: Kolom `synced` (boolean) wajib ada. Pada sisi server, data baru dari sinkronisasi ditandai `synced: true`.

## 2. Validation & State Management
- **Validation**: Strict validation di Form Objects (`amount`, `category`, `type`).
- **Post-Action State**: Selalu panggil `$this->form->reset()` setelah aksi simpan untuk menjaga konsistensi state UI di Livewire.
- **Computed Cache**: Gunakan `unset($this->computedProperty)` di Livewire setelah aksi delete/sync untuk memaksa re-fetching data SQLite jika diperlukan (Server-side hydration).

## 3. Sync Logic
- **Optimistic UI**: Utamakan perubahan pada Dexie & Alpine state sebelum memicu instruksi ke server.
