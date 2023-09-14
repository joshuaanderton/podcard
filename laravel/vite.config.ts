import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import react from '@vitejs/plugin-react'
import vue2 from '@vitejs/plugin-vue2'

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/js/app.tsx',
        'resources/js/app.js',
        'resources/js/player.js'
      ],
      ssr: 'resources/js/ssr.tsx',
      refresh: true,
    }),
    react(),
    vue2(),
  ],
  resolve: {
    alias: {
      '@': '/resources/js',
    },
  },
  ssr: {
    noExternal: ['@inertiajs/server'],
  },
})
