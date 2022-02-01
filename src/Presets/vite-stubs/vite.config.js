import { defineConfig } from 'vite';
import path             from 'path';

export default defineConfig({
    base: '',

    publicDir: false,

    build: {
        manifest: true,
        outDir: 'public/dist',
        rollupOptions: {
            input: 'resources/js/app.js',
        },
    },

    server: {
        host: '127.0.0.1:8000',
        strictPort: true,
        port: 3001,
    },

    resolve: {
        alias: {
            '@': path.resolve('./resources/js'),
        },
    },

    optimizeDeps: {
        include: [
            'axios',
        ],
    },

    plugins: [],
});