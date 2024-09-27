import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/tshirt-customization.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        chunkSizeWarningLimit: 1000, // Set to 1000 KB (1 MB) or your desired limit
    },
});
