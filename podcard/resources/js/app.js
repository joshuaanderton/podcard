import './bootstrap'
import { copy } from 'clipboard'
import '../css/app.css'

document.addEventListener('alpine:init', () => {
  window.copyToClipboard = (text) => {
    copy(text)
    Livewire?.emit('success-message', 'Copied text to clipboard')
  }
})
