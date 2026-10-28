import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            /* Стили сайта по-прежнему в public/css/app.css, через Vite собираем только js и Vue. */
            input: ['resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
