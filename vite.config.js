import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],

    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,

        origin: 'http://192.168.0.141:5173',

        cors: {
            origin: [
                'http://127.0.0.1:8000',
                'http://192.168.0.141:8000',
            ],
        },

        hmr: {
            host: '192.168.0.141',
        },
    },
});
