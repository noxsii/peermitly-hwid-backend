import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import inertia from '@inertiajs/vite'
import path from "node:path";
import docs from "./vite-plugin-docs.mjs";

export default defineConfig({
    plugins: [
        docs(),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/filament/admin/theme.css',
                'resources/js/app.ts',
            ],
            refresh: true,
        }),
        tailwindcss(),
        vue(),
        inertia(),
    ],
    server: {
        watch: {
            ignored: ['**/.junie/**', '**/.superpowers/**'],
        },
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
            'ziggy-js': path.resolve('vendor/tightenco/ziggy'),
        },
    },
});
