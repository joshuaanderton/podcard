import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue2'
import blazervel from './vendor/blazervel/ui/vite.config'

export default blazervel({
	plugins: [
    vue(),
		laravel([
      './resources/js/app.js',
      './resources/js/player.js'
    ])
	]
})
