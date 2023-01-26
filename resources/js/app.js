import './bootstrap'
import alpine from 'alpinejs'
import ClipboardJS from 'clipboard'
import '../css/app.css'

const clipboard = new ClipboardJS('.copy-snippet')

clipboard.on('success', function(event) {
  Livewire.emit('success-message', "You've copied some text")
  event.clearSelection()
})

window.Alpine = alpine

Alpine.start()