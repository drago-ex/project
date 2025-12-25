import { defineConfig } from 'vite';
import fg from 'fast-glob';
import path from 'path';

export default defineConfig(({ mode }) => {
	const DEV = mode === 'development';
	const jsFiles = fg.sync('assets/*.js');
	const input = {};
	jsFiles.forEach(file => {
		const name = path.basename(file, '.js');
		input[name] = path.resolve(__dirname, file);
	});

	return {
		publicDir: './assets/public',
		base: '/dist/',
		server: {
			open: false,
			hmr: false,
		},
		css: {
			preprocessorOptions: {
				scss: {
					api: 'modern-compiler',
					silenceDeprecations: [
						'import',
						'mixed-decls',
						'color-functions',
						'global-builtin',
						'legacy-js-api',
					],
					quietDeps: true,
				},
				sass: {
					api: 'modern-compiler',
					silenceDeprecations: [
						'import',
						'mixed-decls',
						'color-functions',
						'global-builtin',
						'legacy-js-api',
					],
					quietDeps: true,
				},
			},
		},
		build: {
			assetsDir: '',
			outDir: './www/dist/',
			emptyOutDir: true,
			minify: DEV ? false : 'esbuild',
			rollupOptions: {
				output: {
					manualChunks: undefined,
					chunkFileNames: '[name].js',
					entryFileNames: '[name].js',
					assetFileNames: '[name].[ext]',
				},
				input,
			}
		},
	};
});
