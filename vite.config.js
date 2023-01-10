import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue2'
import jaLivewire from './vendor/joshuaanderton/livewire'

export default defineConfig({
	plugins: [
		laravel({
      input: [
        './resources/js/app.js',
        './resources/js/player.js'
      ],
      refresh: true
    }),
    vue(),
    jaLivewire()
	]
})
