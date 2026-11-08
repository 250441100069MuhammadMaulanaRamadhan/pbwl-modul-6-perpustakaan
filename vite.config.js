import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',            // dipakai halaman Breeze (login, register, profil)
                'resources/js/app.js',
                'resources/css/perpustakaan.css',   // dipakai halaman perpustakaan (Modul 1-5)
            ],
            refresh: true,
        }),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
