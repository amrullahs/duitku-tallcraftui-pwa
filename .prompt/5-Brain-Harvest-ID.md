Tinjau percakapan dan perubahan kode terakhir. Identifikasi pola, keputusan, atau solusi yang harus disimpan secara permanen di folder `.agents/`.

Identifikasi berdasarkan 6 kategori:
1. **Rules** — Batasan atau aturan yang harus dipatuhi secara konsisten.
2. **Skills** — Prosedur langkah-demi-langkah atau alur kerja yang dapat digunakan kembali.
3. **Error Solutions** — Masalah teknis yang berhasil dipecahkan beserta akar penyebabnya.
4. **Stack Conventions** — Standar arsitektur, struktur folder, atau pemilihan alat.
5. **Anti-Patterns** — Praktik yang dilarang atau terbukti tidak efektif dalam proyek ini.
6. **Calculations / Logic** — Aturan bisnis, formula, atau logika domain inti.

Tampilkan setiap temuan dalam format berikut:
---
**Category:** [one of the 6 above]
**Status:** 🆕 New File | ✏️ Update to `[existing file path]`
**File Path:** `.agents/rules/[slug]/RULE.md` or `.agents/skills/[slug]/SKILL.md`
**Content:**
```markdown
---
name: [slug]
description: [one-line description]
alwaysApply: [true/false]
globs: [optional glob patterns]
---
[Ready-to-save file content]
```
---
Jika tidak ada pola yang signifikan untuk disimpan, informasikan bahwa tidak ada temuan.
Ringkasan di akhir:
  - Total temuan per kategori.
  - Daftar file baru vs file yang diperbarui.
Lakukan "inventory check" terhadap file yang sudah dibuat pada folder `.agents/`.