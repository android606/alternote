import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import { resolve } from 'path'

export default defineConfig({
	plugins: [
		vue()
	],
	build: {
		lib: false,
		rollupOptions: {
			input: {
				main: resolve(__dirname, 'src/main.js')
			},
			output: {
				format: 'iife',
				name: 'AlternoteApp',
				entryFileNames: 'js/main.js',
				chunkFileNames: 'js/[name]-[hash].js',
				assetFileNames: (assetInfo) => {
					if (assetInfo.name.endsWith('.css')) {
						return 'css/[name][extname]'
					}
					return 'assets/[name][extname]'
				}
			}
		},
		outDir: resolve(__dirname, 'dist'),
		emptyOutDir: true,
		copyPublicDir: false
	},
	resolve: {
		alias: {
			'@': resolve(__dirname, 'src')
		}
	}
})
