<template>
    <div class="flex align-items-center overflow-hidden pt-48 pb-32 md:py-32" style="min-height:720px">
        <div class="h-full w-full absolute top-0 left-0 opacity-25" v-bind:style="color ? 'background: linear-gradient(' + '#' + color.replace('#', '') + ', #ffffff)' : ''"></div>
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row py-8 align-items-center">
                <div class="w-full lg:w-1/3 mx-auto lg:ml-0 z-20">
                    <div class="pb-6 mb-6 fade-up">
                        <h1 class="font-sans font-bold text-black text-4xl sm:text-5xl -mt-5">Podcard Player</h1>
                        <div class="font-sans text-xl sm:text-2xl text-black mb-12">Customize the embeddable player and copy & paste the snippet to any HTML page.</div>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3">
                            <label class="block tracking-wide font-bold mb-2" for="feed_url">Paste your feed URL</label>
                            <input v-model="feed_url" type="text" id="feed_url" class="appearance-none block w-full bg-white text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:border" placeholder="">
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3">
                            <label class="block tracking-wide font-bold mb-2" for="episode">Episode number or title <span class="text-sm font-normal">(leave blank for latest)</span></label>
                            <input v-model="episode" type="text" id="episode" class="appearance-none block w-full bg-white text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:border" placeholder="">
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3">
                            <label class="block tracking-wide font-bold mb-2" for="episode">Player Color</label>
                            <input v-model="color" type="text" id="color" class="color_input appearance-none block w-full bg-white text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:border" placeholder="">
                        </div>
                    </div>
                </div>
                <div v-if="feed_url && feed_url.length > 0" class="w-full lg:w-1/2">
                    <svg class="bg-texture" width="700" height="700" viewBox="0 0 156 159" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6.81116 60.239C7.31116 59.53 7.90115 58.866 8.29715 58.102C9.43415 55.902 10.3812 53.598 11.6092 51.453C13.1932 48.69 14.9122 45.997 16.6932 43.356C18.3842 40.847 20.0892 38.319 22.0472 36.02C25.1492 32.374 28.3672 28.812 31.7232 25.398C34.7972 22.272 38.0242 19.28 41.3582 16.433C43.7172 14.419 46.3642 12.738 48.8912 10.924C49.6492 10.38 50.4442 9.88499 51.1212 9.20899C48.9962 9.30999 47.5602 10.816 45.6132 11.487C46.3102 10.867 46.9252 10.1 47.7182 9.65199C53.0772 6.62599 58.4682 3.65297 64.3722 1.78297C64.6632 1.69097 64.9792 1.68298 65.3472 1.78398C61.4882 4.32098 56.8802 5.36497 52.4762 8.12397C53.4332 8.24397 53.8862 8.44497 54.2032 8.31397C55.2362 7.89097 56.2172 7.34298 57.2212 6.84998C61.5302 4.73198 66.0322 3.12597 70.5492 1.51597C75.3952 -0.21203 80.2012 -0.0660178 85.1602 0.659982C90.6312 1.46198 95.9592 2.81399 101.281 4.24699C104.906 5.22299 108.551 6.12599 112.158 7.16099C119.318 9.21199 125.765 12.666 131.603 17.269C133.171 18.506 134.644 19.869 136.234 21.079C139.023 23.201 141.045 25.948 142.882 28.872C143.304 29.543 143.841 30.143 144.345 30.761C146.118 32.939 147.763 35.196 148.761 37.853C150.111 41.439 151.693 44.959 152.738 48.632C154.287 54.083 155.381 59.643 155.406 65.374C155.429 70.735 155.285 76.08 154.351 81.364C153.31 87.28 151.794 93.055 149.023 98.452C146.984 102.422 145.482 106.722 143.068 110.435C138.359 117.674 132.839 124.309 126.634 130.373C122.089 134.814 117.114 138.648 111.763 142.037C109.872 143.234 107.956 144.412 106.181 145.769C101.13 149.627 95.3472 151.865 89.3352 153.644C87.6492 154.143 85.9952 154.743 84.3272 155.294C80.0542 156.71 75.6202 157.37 71.1822 157.952C68.4962 158.305 65.7742 158.411 63.0652 158.568C61.8111 158.64 60.5692 158.815 59.3172 158.163C58.7452 157.863 57.8312 158.217 56.9132 158.286C56.9032 157.451 57.0182 156.724 56.8522 156.069C56.6962 155.451 56.2432 154.909 55.9202 154.333C55.7602 154.427 55.6022 154.522 55.4432 154.616C55.6312 154.897 55.8812 155.155 55.9942 155.462C56.6292 157.196 55.7071 158.462 53.9202 158.04C51.1252 157.38 48.3652 156.538 45.6392 155.631C38.8642 153.374 32.9892 149.534 27.6022 144.944C23.8932 141.784 20.2392 138.51 17.3172 134.581C14.7902 131.185 12.4282 127.657 10.1592 124.083C8.62215 121.661 7.25515 119.114 6.01315 116.527C3.74915 111.816 2.49715 106.763 1.40215 101.681C0.914148 99.42 0.586152 97.123 0.256152 94.834C-0.335848 90.729 0.344149 86.719 1.26215 82.731C1.88915 80.005 2.38715 77.247 3.09015 74.541C4.13315 70.524 5.31716 66.544 6.41216 62.54C6.61716 61.786 6.68315 60.994 6.81415 60.22L6.81116 60.239ZM53.1272 59.27C53.1371 58.631 53.2292 58.354 53.1292 58.196C52.0742 56.511 51.9511 54.618 51.6702 52.711C51.1842 49.408 50.4282 46.143 49.7562 42.868C49.4862 41.549 49.1742 40.236 48.8182 38.938C48.6722 38.405 48.3592 37.917 47.9432 37.025C47.5972 37.766 47.4332 38.024 47.3512 38.305C46.8142 40.154 47.5872 41.872 47.9332 43.626C48.2551 45.262 48.8242 46.862 49.0172 48.51C49.4612 52.297 50.6861 55.757 53.1272 59.27ZM41.8812 37.513C41.3242 35.125 40.8502 32.713 40.1802 30.357C39.7642 28.899 38.9572 27.64 36.9772 27.715C37.3772 28.959 37.8622 30.049 38.0592 31.188C38.2742 32.433 38.1762 33.729 38.2992 34.995C38.4062 36.082 38.6332 37.157 38.9042 38.838C39.7622 37.256 40.5662 36.609 41.8812 37.513ZM94.7522 133.431C93.7322 132.085 92.1702 131.551 90.7132 131.127C89.8932 130.889 88.9552 131.037 88.0222 130.67C86.6612 130.133 85.1432 129.984 83.6861 129.713C83.5241 129.682 83.3111 129.92 83.1242 130.033C83.9382 131.465 83.9442 131.449 85.3372 131.939C87.7841 132.8 90.2222 133.685 92.7652 134.596C93.3121 134.274 94.0042 133.869 94.7522 133.431ZM32.2682 58.123C32.1082 58.191 31.9462 58.26 31.7862 58.328C32.1002 59.032 32.4152 59.737 32.7272 60.441C32.8702 60.381 33.0122 60.321 33.1532 60.26C32.8572 59.548 32.5622 58.835 32.2682 58.123Z" v-bind:fill="color ? '#' + color.replace('#', '') : '#f7f7f7'"></path>
                    </svg>
                    <div class="py-6 z-5">
                        <label class="block tracking-wide font-bold mb-2" for="episode">Preview</label>
                        <iframe frameBorder="0" height="180" width="100%" v-bind:src="player_url + '?color=' + color.replace('#', '') + '&episode=' + episode + '&feed=' + feed_url"></iframe>
                        <div class="pt-5 overflow-hidden mb-5">
                            <label class="block tracking-wide font-bold mb-2" for="episode">Snippet</label>
                            <code class="copy-snippet bg-black p-4 block rounded text-white text-sm hover:cursor-pointer">
                                <span class="token tag">
                                    <span class="token tag">
                                        <span class="token punctuation">&lt;</span>iframe
                                    </span>

                                    <span class="token attr-name">frameBorder</span><span class="token attr-value"><span class="token punctuation">=</span><span class="token punctuation">"</span>0<span class="token punctuation">"</span></span>

                                    <span class="token attr-name">height</span><span class="token attr-value"><span class="token punctuation">=</span><span class="token punctuation">"</span>180<span class="token punctuation">"</span></span>

                                    <span class="token attr-name">width</span><span class="token attr-value"><span class="token punctuation">=</span><span class="token punctuation">"</span>100%<span class="token punctuation">"</span></span>

                                    <span class="token attr-name">src</span><span class="token attr-value"><span class="token punctuation">=</span><span class="token punctuation">"</span>{{ player_url + '?color=' + color.replace('#', '') + '&episode=' + encodeURIComponent(episode) + '&feed=' + feed_url }}<span class="token punctuation">"</span></span><span class="token punctuation">&gt;</span><span class="token tag"><span class="token punctuation">&lt;</span>/iframe</span><span class="token punctuation">&gt;</span>
                                </span>
                            </code>
                        </div>
                        <div class="">
                            <label class="block tracking-wide font-bold mb-2" for="episode">Or build one your self dynamically using the URL...</label>
                            <code class="bg-black p-4 block rounded text-white text-sm">
                                https://player.podcard.fm?episode=<strong>TITLE_OR_NUMBER</strong>&color=<strong>HEX_CODE</strong>&feed=<strong>FEED_URL</strong>
                            </code>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex align-items-center mb-6">
                <a target="_blank" href="https://twitter.com/joshuaanderton" class="text-xs text-gray-700 inline-block bg-white rounded px-4 py-2 text-gray-400 mr-auto">
                    Made by
                    <span class="text-gray-600 font-bold flex align-items-center pt-1">
                        <img class="rounded-full mr-1" height="40" width="40" src="https://ucarecdn.com/4dc7ec09-5c05-43e9-8fc0-889834614778//-/resize/150/"/>
                        <code>@joshuaanderton</code>
                    </span>
                </a>
            </div>
        </div>
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
            player_url: window.location.href.indexOf('.test') > 0 ? 'http://player.podcard.test' : 'https://player.podcard.fm',
            demos: [
                {feed_url: 'https://feeds.podhunt.app/feeds/daily/rss',   color: '#8772c7', episode: '37signals'},
                {feed_url: 'https://feeds.transistor.fm/founderquest',    color: '#b8702d', episode: 'We\'re Going on Summer Vacation!'},
                {feed_url: 'https://feeds.transistor.fm/build-your-saas', color: '#fbc85c', episode: '70'},
                {feed_url: 'https://feeds.transistor.fm/ramen',           color: '#ff4500', episode: 'tuple.app'},
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

            $('.copy-snippet').click(function(){

            });
            /*
            new Clipboard('.copy-snippet').on('success', function(e){
                var $button = $(e.trigger),
                    text = $button.data('initial-value', $button.text());
                $button.text('Copied!');
                setTimeout(function(){ $button.text($button.data('initial-value')) }, 1000);
            });
            */
        }
    }
</script>
