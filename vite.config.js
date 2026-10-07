import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/styles.css',
                'resources/css/funcionalidades.css',
                'resources/css/login.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
