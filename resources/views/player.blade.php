@extends('layouts.player')

@section('content')

    <style>
        [x-cloak] {
            display: none;
        }
    
        .player,
        h2,
        .player-time-current,
        .player-time-total,
        .player-mute svg {
            color: {{ $dark ? '#ffffff' : '#000000' }};
        }

        .player-progress {
            background-color: {{ $dark ? '#ffffff' : '#000000' }};
        }

        .player-cover,
        .player-wrapper {
            background-color: rgba({{ $color }},1);
        }

        .player-progress .player-seeker {
            background-color: rgba({{ $dark ? $color . ', .4' : '255, 255, 255, .55' }}) !important;
        }

        .player-cover > a {
            background-color: {{ $dark ? 'none' : "rgba({$color}, 0.8)" }};
        }
    </style>

    <div x-cloak class="player-wrapper" x-data="{

        playerData: @js($playerData),

        fileUrl: null,

        cover: null,

        title: null,

        podcast: null,

        season: null,

        episode: null,

        autoPlay: false,

        muted: false,

        loop: false,

        speed: 100,

        currentSeconds: 0,

        durationSeconds: 0,

        innerLoop: false,

        loaded: false,

        playing: false,

        previousVolume: 35,

        showVolume: false,

        volume: 100,

        get currentTime() {
            return this.convertTimeHHMMSS(this.currentSeconds)
        },

        get durationTime() {
            return this.convertTimeHHMMSS(this.durationSeconds)
        },

        get percentComplete() {
            return parseInt(this.currentSeconds / this.durationSeconds * 100)
        },

        load() {
            if ($refs.audio.readyState >= 2) {
                this.loaded = true
                this.durationSeconds = parseInt($refs.audio.duration)
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

            if (!$refs.audio.readyState) {

                $refs.audio.load()
                $refs.audio.onloadeddata = () => $refs.audio.currentTime = parseInt($refs.audio.duration * seekPos)

            } else {

                $refs.audio.currentTime = parseInt($refs.audio.duration * seekPos)

            }

        },

        setSpeed(newSpeed) {
            this.speed = newSpeed
            $refs.audio.playbackRate = this.speed / 100
        },

        pause() {
            $refs.audio.pause()
        },

        update() {
            this.currentSeconds = parseInt($refs.audio.currentTime)
            console.log(this.currentSeconds)
        },

        created() {
            this.innerLoop = this.loop
        },

        convertTimeHHMMSS(val) {
            let hhmmss = new Date(val * 1000).toISOString().substr(11, 8)
            return hhmmss.indexOf('00:') === 0 ? hhmmss.substr(3) : hhmmss
        },

        init() {

            const data = this.playerData,
                  receiver = new PlayerJS.Receiver()

            this.podcast  = data.podcast
            this.title    = data.title
            this.episode  = data.episode
            this.coverUrl = data.coverUrl
            this.fileUrl  = data.fileUrl

            $refs.audio.addEventListener('timeupdate', this.update)

            $refs.audio.addEventListener('loadeddata', this.load)

            $refs.audio.addEventListener('pause', event => this.playing = false)

            $refs.audio.addEventListener('play', event => this.playing = true)

            $refs.audio.addEventListener('ended', event => receiver.emit('ended'))

            $refs.audio.addEventListener('timeupdate', event => {
                receiver.emit('timeupdate', {
                    seconds: $refs.audio.currentTime,
                    duration: $refs.audio.duration
                })
            })

            receiver.on('play', () => {
                $refs.audio.play()
                receiver.emit('play')
            })

            receiver.on('pause', () => {
                $refs.audio.pause()
                receiver.emit('pause')
            })

            receiver.on('getDuration', callback => callback($refs.audio.duration))

            receiver.on('getVolume', callback => callback($refs.audio.volume*100))

            receiver.on('setVolume', value => $refs.audio.volume = (value/100))

            receiver.on('getPaused', () => !$refs.audio.playing)

            receiver.on('mute', () => $refs.audio.mute())

            receiver.on('unmute', () => $refs.audio.unmute())

            receiver.on('getMuted', callback => callback($refs.audio.muted))

            receiver.on('getLoop', callback => callback($refs.audio.loop))

            receiver.on('setLoop', value => $refs.audio.loop = value)

            receiver.ready()

            $watch('playing', value => {

                if (!$refs.audio.readyState) {
                    $refs.audio.load()
                    $refs.audio.onloadeddata = () => $data.playing = true
                    return
                }

                if (value) {
                    $refs.audio.play()
                    return
                }

                $refs.audio.pause()
                
            })

            $watch('volume', value => {
                $data.showVolume = false
                $refs.audio.volume = $data.volume / 100
            })
        }
    }">

        <div class="player">
            <div class="player-controls">
                <div class="player-cover" x-bind:style="{ backgroundImage: `url(${coverUrl})`, backgroundSize: 'cover' }">
                    <a x-show="playing" x-on:click.prevent="pause" title="Pause" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path x-show="playing" fill="currentColor" d="M15,3h-2c-0.553,0-1,0.048-1,0.6v12.8c0,0.552,0.447,0.6,1,0.6h2c0.553,0,1-0.048,1-0.6V3.6C16,3.048,15.553,3,15,3z M7,3H5C4.447,3,4,3.048,4,3.6v12.8C4,16.952,4.447,17,5,17h2c0.553,0,1-0.048,1-0.6V3.6C8,3.048,7.553,3,7,3z"/>
                        </svg>
                    </a>
                    <a x-show="!playing" x-on:click.prevent="playing = !playing" title="Play" href="#">
                        <svg style="right:-3px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path x-show="!playing" fill="currentColor" d="M15,10.001c0,0.299-0.305,0.514-0.305,0.514l-8.561,5.303C5.51,16.227,5,15.924,5,15.149V4.852c0-0.777,0.51-1.078,1.135-0.67l8.561,5.305C14.695,9.487,15,9.702,15,10.001z"/>
                            <path x-show="playing" fill="currentColor" d="M15,3h-2c-0.553,0-1,0.048-1,0.6v12.8c0,0.552,0.447,0.6,1,0.6h2c0.553,0,1-0.048,1-0.6V3.6C16,3.048,15.553,3,15,3z M7,3H5C4.447,3,4,3.048,4,3.6v12.8C4,16.952,4.447,17,5,17h2c0.553,0,1-0.048,1-0.6V3.6C8,3.048,7.553,3,7,3z"/>
                        </svg>
                    </a>
                </div>
                <div class="player-options">
                    <div class="player-heading">
                        <div class="player-meta" x-show="podcast || episode">
                            <strong x-text="podcast"></strong>
                            <small x-text="` ${episode && podcast ? '—' : ''} ${season && season > 1 ? 'S' + season + ' ' : ''} ${episode ? 'EP' + episode : ''}`"></small>
                        </div>
                        <h2 x-show="title" x-text="title"></h2>
                    </div>
                    <div>
                        <div x-on:click="seek" class="player-progress" title="Time played : Total time">
                            <div :style="{ width: `${percentComplete}%` }" class="player-seeker"></div>
                        </div>
                    </div>
                    <div class="player-time">
                        <div class="player-time-current" x-text="currentTime"></div>
                        <div class="player-time-total" x-text="durationTime"></div>
                    </div>
                    <span class="player-speed">
                        <span x-on:click="setSpeed(125)" x-show="speed == 100">1x</span>
                        <span x-on:click="setSpeed(150)" x-show="speed == 125">1.25x</span>
                        <span x-on:click="setSpeed(200)" x-show="speed == 150">1.5x</span>
                        <span x-on:click="setSpeed(50)"  x-show="speed == 200">2x</span>
                        <span x-on:click="setSpeed(75)"  x-show="speed == 50">.5x</span>
                        <span x-on:click="setSpeed(100)" x-show="speed == 75">.75x</span>
                    </span>
                    <a class="player-mute" x-on:click="mute" title="Mute" href="#">
                        <svg width="18px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path x-show="!muted" fill="currentColor" d="M5.312,4.566C4.19,5.685-0.715,12.681,3.523,16.918c4.236,4.238,11.23-0.668,12.354-1.789c1.121-1.119-0.335-4.395-3.252-7.312C9.706,4.898,6.434,3.441,5.312,4.566z M14.576,14.156c-0.332,0.328-2.895-0.457-5.364-2.928C6.745,8.759,5.956,6.195,6.288,5.865c0.328-0.332,2.894,0.457,5.36,2.926C14.119,11.258,14.906,13.824,14.576,14.156zM15.434,5.982l1.904-1.906c0.391-0.391,0.391-1.023,0-1.414c-0.39-0.391-1.023-0.391-1.414,0L14.02,4.568c-0.391,0.391-0.391,1.024,0,1.414C14.41,6.372,15.043,6.372,15.434,5.982z M11.124,3.8c0.483,0.268,1.091,0.095,1.36-0.388l1.087-1.926c0.268-0.483,0.095-1.091-0.388-1.36c-0.482-0.269-1.091-0.095-1.36,0.388L10.736,2.44C10.468,2.924,10.642,3.533,11.124,3.8z M19.872,6.816c-0.267-0.483-0.877-0.657-1.36-0.388l-1.94,1.061c-0.483,0.268-0.657,0.878-0.388,1.36c0.268,0.483,0.877,0.657,1.36,0.388l1.94-1.061C19.967,7.907,20.141,7.299,19.872,6.816z"/>
                            <path x-show="muted" fill="currentColor" d="M14.201,9.194c1.389,1.883,1.818,3.517,1.559,3.777c-0.26,0.258-1.893-0.17-3.778-1.559l-5.526,5.527c4.186,1.838,9.627-2.018,10.605-2.996c0.925-0.922,0.097-3.309-1.856-5.754L14.201,9.194z M8.667,7.941c-1.099-1.658-1.431-3.023-1.194-3.26c0.233-0.234,1.6,0.096,3.257,1.197l1.023-1.025C9.489,3.179,7.358,2.519,6.496,3.384C5.568,4.31,2.048,9.261,3.265,13.341L8.667,7.941z M18.521,1.478c-0.39-0.391-1.023-0.391-1.414,0L1.478,17.108c-0.391,0.391-0.391,1.024,0,1.414c0.391,0.391,1.023,0.391,1.414,0l15.629-15.63C18.912,2.501,18.912,1.868,18.521,1.478z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <audio
                x-ref="audio"
                :loop="innerLoop"
                :src="fileUrl"
                preload="none"
                style="display: none;"></audio>

        </div>
    </div>
@endsection