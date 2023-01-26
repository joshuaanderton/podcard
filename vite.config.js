import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue2 from '@vitejs/plugin-vue2'
import preact from '@preact/preset-vite'
import jaLivewire from './vendor/joshuaanderton/livewire'
import jaInertia from './vendor/joshuaanderton/inertia'

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
    jaLivewire(),

    // For Video Builder
    preact(),
    jaInertia({progress: {color: '#4B5563'}}),
	]
})
