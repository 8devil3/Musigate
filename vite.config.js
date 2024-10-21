import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        VitePWA({
            registerType: 'autoUpdate',
            // includeAssets: ['/offline.html'],
            disable: false,
            devOptions: {
                enabled: false,
                type: 'module',
            },
            outDir: 'public/build',
            base: 'public',
            buildBase: '/build/',
            scope: '/build/',
            workbox: {
                navigateFallback: null,
                cleanupOutdatedCaches: true,
                directoryIndex: null, // this prevents fallback to index.html,
                additionalManifestEntries: [
                    {
                        url : '/offline.html',
                        revision: `${Date.now()}`
                    },
                ]
            },
            manifest: {
                name: 'Musigate',
                short_name: 'Musigate',
                description: 'Musigate | SuonoErgoSono.',
                orientation: 'portrait-primary',
                theme_color: '#020617',
                id: '/',
                start_url: '/',
                scope: '/',
                display: 'standalone',
                background_color: '#020617',
                lang: 'it',
                icons: [
                    {
                        src: '/img/pwa/pwa-512x512.png',
                        sizes: '512x512',
                        type: 'image/png'
                    },
                    {
                        src: '/img/pwa/pwa-192x192.png',
                        sizes: '192x192',
                        type: 'image/png'
                    },
                    {
                        src: '/img/pwa/mask-icon.png',
                        sizes: '196x196',
                        type: 'image/png',
                        purpose: 'maskable'
                    },
                ],
                screenshots: [
                    {
                        src: '/img/pwa/screenshot_desktop.png',
                        sizes: '1920x891',
                        type: 'image/png',
                        form_factor: 'wide',
                        label: 'Musigate'
                    },
                    {
                        src: '/img/pwa/screenshot_mobile.png',
                        sizes: '375x667',
                        type: 'image/png',
                        form_factor: 'narrow',
                        label: 'Musigate'
                    },
                ],
            }
        }),
    ],
});
