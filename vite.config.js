import { defineConfig } from 'vite';
import nette from '@nette/vite-plugin';
import fg from 'fast-glob';
import path from 'path';

const files = fg.sync('assets/*.js', {
    ignore: ['assets/naja.*.js', 'assets/base.js'],
});

const entries = files.map(
    file => path.relative(process.cwd(), file)
);

export default defineConfig({
    root: 'assets',
    publicDir: 'public',
    build: {
        outDir: '../www/dist',
        emptyOutDir: true,

        rollupOptions: {
            input: entries,
        },
    },
    css: {
        devSourcemap: true,
        preprocessorOptions: {
            scss: { quietDeps: true },
        },
    },
    plugins: [
        nette(),
    ],
});
