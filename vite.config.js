import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue2'

export default defineConfig({
	plugins: [
    vue(),
		laravel({
      input: [
        './resources/js/app.js',
        './resources/js/player.js'
      ],
      refresh: true
    })
	]
})
