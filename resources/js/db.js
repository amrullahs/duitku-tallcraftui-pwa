import Dexie from 'dexie';

/**
 * DuitKu IndexedDB Schema
 * Version 1 (Migrated): Initial schema with auto-increment ID
 * Version 2 (Current): Using UUID as Primary Key (Match Livewire/Eloquent UUID)
 */
export const db = new Dexie('DuitKuDBv2'); // Increment DB Name to avoid migration conflict during dev

db.version(1).stores({
    transactions: 'id, amount, category, type, date, synced',
    categories: 'id, name, type, icon, color'
});

export default db;
