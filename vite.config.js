import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue2 from '@vitejs/plugin-vue2'
import livewirekit from './vendor/joshuaanderton/livewire'

export default defineConfig({
	plugins: [
		laravel({
      input: [
        './resources/js/app.js',
        './resources/js/player.js'
      ],
      refresh: true
    }),

    // For Player
    vue2(),

    // For Player Builder
    livewirekit(),
	]
})
