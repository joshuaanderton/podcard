<template>
    <div class="player">
        <div class="player-controls">
            <div class="player-cover" v-bind:style="{ 'background-image': 'url(' + cover + ')', 'background-size': 'cover' }">
                <a v-if="playing" v-on:click.prevent="pause" title="Pause" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path v-if="playing" fill="currentColor" d="M15,3h-2c-0.553,0-1,0.048-1,0.6v12.8c0,0.552,0.447,0.6,1,0.6h2c0.553,0,1-0.048,1-0.6V3.6C16,3.048,15.553,3,15,3z M7,3H5C4.447,3,4,3.048,4,3.6v12.8C4,16.952,4.447,17,5,17h2c0.553,0,1-0.048,1-0.6V3.6C8,3.048,7.553,3,7,3z"/>
                    </svg>
                </a>
                <a v-if="!playing" v-on:click.prevent="playing = !playing" title="Play" href="#">
                    <svg style="right:-3px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path v-if="!playing" fill="currentColor" d="M15,10.001c0,0.299-0.305,0.514-0.305,0.514l-8.561,5.303C5.51,16.227,5,15.924,5,15.149V4.852c0-0.777,0.51-1.078,1.135-0.67l8.561,5.305C14.695,9.487,15,9.702,15,10.001z"/>
                        <path v-else fill="currentColor" d="M15,3h-2c-0.553,0-1,0.048-1,0.6v12.8c0,0.552,0.447,0.6,1,0.6h2c0.553,0,1-0.048,1-0.6V3.6C16,3.048,15.553,3,15,3z M7,3H5C4.447,3,4,3.048,4,3.6v12.8C4,16.952,4.447,17,5,17h2c0.553,0,1-0.048,1-0.6V3.6C8,3.048,7.553,3,7,3z"/>
                    </svg>
                </a>
            </div>
            <div class="player-options">
                <div class="player-heading">
                    <div class="player-meta" v-if="podcast || episode"><strong>{{podcast}}</strong> <small>{{ episode && podcast ? '—' : '' }} {{ season && season > 1 ? 'S' + season + ' ' : '' }}{{episode ? 'EP' + episode : ''}}</small></div>
                    <h2 v-if="title">{{title}}</h2>
                </div>
                <div>
                    <div v-on:click="seek" class="player-progress" title="Time played : Total time">
                        <div :style="{ width: this.percentComplete + '%' }" v-if="currentSeconds > 0" class="player-seeker"></div>
                    </div>
                </div>
                <div class="player-time">
                    <div class="player-time-current">{{ currentTime }}</div>
                    <div class="player-time-total">{{ durationTime }}</div>
                </div>
                <a class="player-mute" v-on:click.prevent="mute" title="Mute" href="#">
                    <svg width="18px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path v-if="!muted" fill="currentColor" d="M5.312,4.566C4.19,5.685-0.715,12.681,3.523,16.918c4.236,4.238,11.23-0.668,12.354-1.789c1.121-1.119-0.335-4.395-3.252-7.312C9.706,4.898,6.434,3.441,5.312,4.566z M14.576,14.156c-0.332,0.328-2.895-0.457-5.364-2.928C6.745,8.759,5.956,6.195,6.288,5.865c0.328-0.332,2.894,0.457,5.36,2.926C14.119,11.258,14.906,13.824,14.576,14.156zM15.434,5.982l1.904-1.906c0.391-0.391,0.391-1.023,0-1.414c-0.39-0.391-1.023-0.391-1.414,0L14.02,4.568c-0.391,0.391-0.391,1.024,0,1.414C14.41,6.372,15.043,6.372,15.434,5.982z M11.124,3.8c0.483,0.268,1.091,0.095,1.36-0.388l1.087-1.926c0.268-0.483,0.095-1.091-0.388-1.36c-0.482-0.269-1.091-0.095-1.36,0.388L10.736,2.44C10.468,2.924,10.642,3.533,11.124,3.8z M19.872,6.816c-0.267-0.483-0.877-0.657-1.36-0.388l-1.94,1.061c-0.483,0.268-0.657,0.878-0.388,1.36c0.268,0.483,0.877,0.657,1.36,0.388l1.94-1.061C19.967,7.907,20.141,7.299,19.872,6.816z"/>
                        <path v-else fill="currentColor" d="M14.201,9.194c1.389,1.883,1.818,3.517,1.559,3.777c-0.26,0.258-1.893-0.17-3.778-1.559l-5.526,5.527c4.186,1.838,9.627-2.018,10.605-2.996c0.925-0.922,0.097-3.309-1.856-5.754L14.201,9.194z M8.667,7.941c-1.099-1.658-1.431-3.023-1.194-3.26c0.233-0.234,1.6,0.096,3.257,1.197l1.023-1.025C9.489,3.179,7.358,2.519,6.496,3.384C5.568,4.31,2.048,9.261,3.265,13.341L8.667,7.941z M18.521,1.478c-0.39-0.391-1.023-0.391-1.414,0L1.478,17.108c-0.391,0.391-0.391,1.024,0,1.414c0.391,0.391,1.023,0.391,1.414,0l15.629-15.63C18.912,2.501,18.912,1.868,18.521,1.478z"/>
                    </svg>
                </a>
            </div>
        </div>
        <audio :loop="innerLoop" ref="audiofile" :src="file" preload="auto" style="display: none;"></audio>
    </div>
</template>

<script>
    import playerjs from 'player.js'

    export default {
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
                type: Number,
                default: null
            },
            autoPlay: {
                type: Boolean,
                default: false
            },
            loop: {
                type: Boolean,
                default: false
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
                return convertTimeHHMMSS(this.currentSeconds);
            },
            durationTime() {
                return convertTimeHHMMSS(this.durationSeconds);
            },
            percentComplete() {
                return parseInt(this.currentSeconds / this.durationSeconds * 100);
            },
            muted() {
                return this.volume / 100 === 0;
            }
        },
        watch: {
            playing(value) {
                if (value) { return this.audio.play(); }
                this.audio.pause();
            },
            volume(value) {
                this.showVolume = false;
                this.audio.volume = this.volume / 100;
            }
        },
        methods: {
            load() {
                if (this.audio.readyState >= 2) {
                    this.loaded = true;
                    this.durationSeconds = parseInt(this.audio.duration);
                    return this.playing = this.autoPlay;
                }

                throw new Error('Failed to load sound file.');
            },
            mute() {
                if (this.muted) {
                    this.unmute();
                }

                this.previousVolume = this.volume;
                this.volume = 0;
            },
            unmute() {
                return this.volume = this.previousVolume;
            },
            seek(e) {
                if (e.target.tagName === 'SPAN') {
                    return;
                }

                const el = e.target.getBoundingClientRect();
                const seekPos = (e.clientX - el.left) / el.width;

                this.audio.currentTime = parseInt(this.audio.duration * seekPos);
            },
            pause() {
                this.audio.pause();
            },
            stop() {
                this.playing = false;
                this.audio.currentTime = 0;
            },
            update(e) {
                this.currentSeconds = parseInt(this.audio.currentTime);
            }
        },
        created() {
            this.innerLoop = this.loop;
        },
        mounted() {
            this.audio = this.$el.querySelectorAll('audio')[0];
            this.audio.addEventListener('timeupdate', this.update);
            this.audio.addEventListener('loadeddata', this.load);
            this.audio.addEventListener('pause', () => { this.playing = false; });
            this.audio.addEventListener('play', () => { this.playing = true; });

            // Player.js stuff
            const receiver = new playerjs.Receiver();

            this.audio.addEventListener('ended', () => receiver.emit('ended'));
            this.audio.addEventListener('timeupdate', () => {
                receiver.emit('timeupdate', {
                    seconds: this.audio.currentTime,
                    duration: this.audio.duration
                });
            });

            receiver.on('play', () => {
                this.audio.play();
                receiver.emit('play');
            });

            receiver.on('pause', () => {
                this.audio.pause();
                receiver.emit('pause');
            });

            receiver.on('getDuration', callback => callback(this.audio.duration));
            receiver.on('getVolume', callback => callback(this.audio.volume*100));
            receiver.on('setVolume', value => this.audio.volume = (value/100));
            receiver.on('mute', () => this.audio.mute())
            receiver.on('unmute', () => this.audio.unmute());
            receiver.on('getMuted', callback => callback(this.audio.muted));
            receiver.on('getLoop', callback => callback(this.audio.loop));
            receiver.on('setLoop', value => this.audio.loop = value);

            receiver.ready();
        }
    }
</script>
