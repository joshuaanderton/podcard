import './bootstrap'
import Vue from 'vue/dist/vue.esm'
import playerjs from 'player.js'
import 'clipboard'

import '../css/player.css'

new Vue({
  el: "#player",
  props: {
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
  data: () => {

    const playerEl = document.querySelector('#player'),
          playerData = eval(playerEl.dataset.episode)

    playerEl.removeAttribute('data-episode')

    return {
      podcast: playerData.podcast,
      title: playerData.title,
      episode: playerData.episode,
      season: playerData.season,
      cover: playerData.cover_url,
      file: playerData.file_url,
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
    }
  },
  computed: {
    currentTime() {
      return this.convertTimeHHMMSS(this.currentSeconds)
    },
    durationTime() {
      return this.convertTimeHHMMSS(this.durationSeconds)
    },
    percentComplete() {
      if (!this.durationSeconds) {
        return 0
      }

      const percent = (this.currentSeconds / this.durationSeconds) * 100

      if (isNaN(percent)) {
        return 0
      }

      return percent
    },
  },
  watch: {
    playing(value) {
      if (!this.audio.readyState) {
        this.audio.load()
        this.audio.onloadeddata = () => this.playing = true
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
      //
    }
  },
  methods: {
    convertTimeHHMMSS: (val) => {
      let hhmmss = new Date(val * 1000).toISOString().substr(11, 8)
      return hhmmss.indexOf("00:") === 0 ? hhmmss.substr(3) : hhmmss
    },
    load() {
      if (this.audio.readyState >= 2) {
        this.loaded = true
        this.durationSeconds = this.audio.duration
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

      const el = e.target.getBoundingClientRect(),
            seekPos = (e.clientX - el.left) / el.width

      if (!this.audio.readyState) {

        this.audio.load()
        this.audio.onloadeddata = () => this.audio.currentTime = parseInt(this.audio.duration * seekPos)

      } else {

        this.audio.currentTime = parseInt(this.audio.duration * seekPos)

      }

    },
    setSpeed(newSpeed) {
      this.speed = newSpeed
      this.audio.playbackRate = this.speed / 100
    },
    pause() {
      this.audio.pause()
    },
    stop() {
      this.playing = false
      this.audio.currentTime = 0
    },
    update(e) {
      this.currentSeconds = this.audio.currentTime
    }
  },
  created() {
    this.innerLoop = this.loop
  },
  mounted() {

    const receiver = new playerjs.Receiver()

    this.audio = this.$el.querySelectorAll('audio')[0]
    this.audio.addEventListener('timeupdate', this.update)
    this.audio.addEventListener('loadeddata', this.load)
    this.audio.addEventListener('pause', () => { this.playing = false })
    this.audio.addEventListener('play', () => { this.playing = true })
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
