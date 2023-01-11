import './bootstrap'

import alpine from 'alpinejs'
import ClipboardJS from 'clipboard'
// import Choices from 'choices.js'

import '../css/app.css'

// window.Choices = Choices
window.Alpine = alpine

const clipboard = new ClipboardJS('.copy-snippet')

clipboard.on('success', function(event) {
  
  Livewire.emit('success-message', "You've copied some text")

  event.clearSelection()
})

Alpine.start()