import './bootstrap'
import { copy } from 'clipboard'
import '../css/app.css'

window.copyToClipboard = (text) => {
  copy(text)
  Livewire.emit('success-message', "You've copied some text")
}