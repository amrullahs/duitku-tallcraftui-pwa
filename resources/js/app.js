import './bootstrap';
import { db } from './db';
import { registerSW } from 'virtual:pwa-register';
import ApexCharts from 'apexcharts';

// 1. Expose Global Libraries ASAP
window.db = db;
window.ApexCharts = ApexCharts;

// 2. Register Service Worker
const updateSW = registerSW({
    onNeedRefresh() {
        if (confirm('Aplikasi versi baru tersedia. Refresh sekarang?')) {
            updateSW(true);
        }
    },
    onOfflineReady() {
        console.log('Aplikasi sudah siap untuk digunakan secara offline.');
    },
});

/**
 * Configure Alpine Store & Magic.
 * Note: We DO NOT import or register @alpinejs/persist manually.
 * Livewire 4 already includes it.
 */
document.addEventListener('livewire:init', () => {
    // Magic $db
    Alpine.magic('db', () => window.db);

    // Connectivity Store
    Alpine.store('connectivity', {
        isOffline: !navigator.onLine,
        init() {
            window.addEventListener('online', () => this.isOffline = false);
            window.addEventListener('offline', () => this.isOffline = true);
        }
    });

    /**
     * Theme Store
     * Using native Alpine.$persist which should be available via Livewire's Alpine.
     */
    const persist = Alpine.$persist || ((val) => val); // Fallback to no-persist if not found

    Alpine.store('theme', {
        darkMode: persist(window.matchMedia('(prefers-color-scheme: dark)').matches).as('darkMode'),
        toggle() { this.darkMode = !this.darkMode }
    });
});

/**
 * Ensure initial theme is applied on navigation
 */
document.addEventListener('livewire:navigated', () => {
    if (window.Alpine && Alpine.store('theme')) {
        const darkMode = Alpine.store('theme').darkMode;
        document.documentElement.classList.toggle('dark', darkMode);
    }
});
