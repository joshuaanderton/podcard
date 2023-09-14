import './bootstrap'
import { copy } from 'clipboard'
import '../css/app.css'

document.addEventListener('alpine:init', () => {
  window.copyToClipboard = copy
})
