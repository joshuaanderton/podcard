import './bootstrap'

import Vue from 'vue/dist/vue.esm'
import 'clipboard'
import playerjs from 'player.js'

import '../css/player.css'

const convertTimeHHMMSS = (val) => {
  let hhmmss = new Date(val * 1000).toISOString().substring(11, 8)
  return hhmmss.indexOf("00:") === 0 ? hhmmss.substring(3) : hhmmss
}

new Vue({
  el: "#player",
  props: {
    file: {
      type: String,
      default: null
    },
    cover: {
      type: String,
      default: null
    },
    title: {
      type: String,
      default: null
    },
    podcast: {
      type: String,
      default: null
    },
    season: {
      type: Number,
      default: null
    },
    episode: {
      type: String,
      default: null
    },
    autoPlay: {
      type: Boolean,
      default: false
    },
    muted: {
      type: Boolean,
      default: false
    },
    loop: {
      type: Boolean,
      default: false
    },
    speed: {
      type: Number,
      default: 100
    }
  },
  data: () => ({
    audio: undefined,
    currentSeconds: 0,
    durationSeconds: 0,
    cover_url: '',
    innerLoop: false,
    loaded: false,
    playing: false,
    previousVolume: 35,
    showVolume: false,
    volume: 100
  }),
  computed: {
    currentTime() {
      return convertTimeHHMMSS(this.currentSeconds)
    },
    durationTime() {
      return convertTimeHHMMSS(this.durationSeconds)
    },
    percentComplete() {
      return parseInt(this.currentSeconds / this.durationSeconds * 100)
    },
  },
  watch: {
    playing(value) {
      var _this = this
      if (!this.audio.readyState) {
        this.audio.load()
        this.audio.onloadeddata = function(){
          _this.playing = true
        }
      } else {
        if (value) { return this.audio.play() }
        this.audio.pause()
      }
    },
    volume(value) {
      this.showVolume = false
      this.audio.volume = this.volume / 100
    },
    speed() {
      // console.log(this.speed)
    }
  },
  methods: {
    load() {
      if (this.audio.readyState >= 2) {
        this.loaded = true
        this.durationSeconds = parseInt(this.audio.duration)
        return this.playing = this.autoPlay
      }

      throw new Error('Failed to load sound file.')
    },
    mute() {
      if (this.muted) {
        this.unmute()
        return
      }

      this.previousVolume = this.volume
      this.volume = 0
      this.muted = true
    },
    unmute() {
      this.muted = false
      this.volume = this.previousVolume
    },
    seek(e) {
      if (e.target.tagName === 'SPAN') {
        return
      }

      const el = e.target.getBoundingClientRect()
      const seekPos = (e.clientX - el.left) / el.width

      this.audio.currentTime = parseInt(this.audio.duration * seekPos)
    },
    setSpeed(newSpeed) {
      this.speed = newSpeed
      this.audio.playbackRate = this.speed / 100
      // console.log(this.audio.playbackRate)
    },
    pause() {
      this.audio.pause()
    },
    stop() {
      this.playing = false
      this.audio.currentTime = 0
    },
    update(e) {
      this.currentSeconds = parseInt(this.audio.currentTime)
    }
  },
  created() {
    this.innerLoop = this.loop
  },
  mounted() {

    const data = PlayerData

    // console.log(data)

    this.podcast = data.podcast
    this.title   = data.title
    this.episode = data.episode
    this.cover   = data.cover_url
    this.file    = data.file_url

    this.audio = this.$el.querySelectorAll('audio')[0]
    this.audio.addEventListener('timeupdate', this.update)
    this.audio.addEventListener('loadeddata', this.load)
    this.audio.addEventListener('pause', () => { this.playing = false })
    this.audio.addEventListener('play', () => { this.playing = true })

    // Player.js stuff
    const receiver = new playerjs.Receiver()

    this.audio.addEventListener('ended', () => receiver.emit('ended'))
    this.audio.addEventListener('timeupdate', () => {
      receiver.emit('timeupdate', {
        seconds: this.audio.currentTime,
        duration: this.audio.duration
      })
    })

    receiver.on('play', () => {
      this.audio.play()
      receiver.emit('play')
    })

    receiver.on('pause', () => {
      this.audio.pause()
      receiver.emit('pause')
    })

    receiver.on('getDuration', callback => callback(this.audio.duration))
    receiver.on('getVolume', callback => callback(this.audio.volume*100))
    receiver.on('setVolume', value => this.audio.volume = (value/100))
    receiver.on('getPaused', () => !this.audio.playing)
    receiver.on('mute', () => this.audio.mute())
    receiver.on('unmute', () => this.audio.unmute())
    receiver.on('getMuted', callback => callback(this.audio.muted))
    receiver.on('getLoop', callback => callback(this.audio.loop))
    receiver.on('setLoop', value => this.audio.loop = value)

    receiver.ready()
  }
})