<template>
    <div class="container mx-auto px-4">
        <div class="py-6"><h1 class="text-xl font-bold">Podcard Player</h1></div>
        <div class="flex flex-col lg:flex-row py-8">
            <div class="w-full lg:w-1/3 mx-auto lg:ml-0">
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full px-3">
                        <label class="block tracking-wide text-gray-700 font-bold mb-2" for="feed_url">Feed URL</label>
                        <input v-model="feed_url" type="text" id="feed_url" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" placeholder="">
                    </div>
                </div>

                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full px-3">
                        <label class="block tracking-wide text-gray-700 font-bold mb-2" for="episode">Episode Number or Title <span class="text-gray-500 font-normal">(leave blank for latest)</span></label>
                        <input v-model="episode" type="text" id="episode" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" placeholder="">
                    </div>
                </div>

                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full px-3">
                        <label class="block tracking-wide text-gray-700 font-bold mb-2" for="episode">Color</label>
                        <input v-model="color" type="text" id="color" class="color_input appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" placeholder="">
                    </div>
                </div>
            </div>
            <div v-if="feed_url && feed_url.length > 0" class="w-full lg:w-1/2">
                <div class="border rounded p-6 bg-gray-100">
                    <label class="block tracking-wide text-gray-700 font-bold mb-2" for="episode">Preview</label>
                    <iframe frameBorder="0" height="180" width="100%" v-bind:src="player_url + '?color=' + color.replace('#', '') + '&episode=' + episode + '&feed=' + feed_url"></iframe>
                    <div class="pt-5">
                        <label class="block tracking-wide text-gray-700 font-bold mb-2" for="episode">Snippet</label>
                        <code class="bg-gray-700 p-4 block rounded text-white text-sm">
                            <span class="token tag">
                                <span class="token tag">
                                    <span class="token punctuation">&lt;</span>iframe
                                </span>

                                <span class="token attr-name">frameBorder</span><span class="token attr-value"><span class="token punctuation">=</span><span class="token punctuation">"</span>0<span class="token punctuation">"</span></span>

                                <span class="token attr-name">height</span><span class="token attr-value"><span class="token punctuation">=</span><span class="token punctuation">"</span>180<span class="token punctuation">"</span></span>

                                <span class="token attr-name">width</span><span class="token attr-value"><span class="token punctuation">=</span><span class="token punctuation">"</span>100%<span class="token punctuation">"</span></span>

                                <span class="token attr-name">src</span><span class="token attr-value"><span class="token punctuation">=</span><span class="token punctuation">"</span>{{ player_url + '?color=' + color.replace('#', '') + '&episode=' + episode + '&feed=' + feed_url }}<span class="token punctuation">"</span></span><span class="token punctuation">&gt;</span><span class="token tag"><span class="token punctuation">&lt;</span>/iframe</span><span class="token punctuation">&gt;</span>
                            </span>
                        </code>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-xs text-gray-500 pb-6">Made by <a class="text-gray-600 font-bold" target="_blank" href="https://twitter.com/joshuaanderton"><code>@joshanderton</code></a></div>
    </div>
</template>

<script>
    export default {
        props: {
            feed_url: {
                type: String,
                default: ''
            },
            episode: {
                type: String,
                default: ''
            },
            color: {
                type: String,
                default: ''
            },
        },
        data: () => ({
            player_url: window.location.href.indexOf('.test') > 0 ? 'http://player.podcard.test' : 'https://player.podcard.co',
            demos: [
                {feed_url: 'https://feeds.podhunt.app/feeds/daily/rss',   color: '#8772c7', episode: '37signals'},
                {feed_url: 'https://feeds.transistor.fm/founderquest',    color: '',        episode: 'We\'re Going on Summer Vacation!'},
                {feed_url: 'https://feeds.transistor.fm/build-your-saas', color: '#fbc85c', episode: '70'},
                {feed_url: 'https://anchor.fm/s/d5d3614/podcast/rss',     color: '#ff4500', episode: 'growth hacking'},
            ]
        }),
        methods: {
        },
        created() {
            var random_demo = Math.floor((Math.random() * this.demos.length) + 1) - 1;
            this.feed_url = this.demos[random_demo].feed_url;
            this.episode  = this.demos[random_demo].episode;
            this.color    = this.demos[random_demo].color;
        },
        mounted() {
            var _this = this;
            $('.color_input').minicolors({theme: 'bootstrap'});
            $('.color_input').change(function(){ _this.color = $(this).val(); });
        }
    }
</script>
