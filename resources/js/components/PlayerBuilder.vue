<template>
    <div class="container mx-auto px-4 py-8">
        <div class="w-full max-w-lg mx-auto">
            <!-- https://anchor.fm/s/d5d3614/podcast/rss -->
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="feed_url">Feed URL</label>
                    <input v-model="feed_url" type="text" id="feed_url" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" placeholder="">
                </div>
            </div>

            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="episode">Episode (leave blank for latest)</label>
                    <input v-model="episode" type="number" id="episode" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" placeholder="">
                </div>
            </div>

            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="episode">Color</label>
                    <input v-model="color" type="text" id="color" class="color_input appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" placeholder="">
                </div>
            </div>

            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/3"></div>
                <label class="md:w-2/3 block text-gray-500 font-bold">
                    <input class="mr-2 leading-tight" type="checkbox">
                    <span>Show border</span>
                </label>
            </div>

            <iframe v-if="feed_url && feed_url.length > 0" frameBorder="0" height="180px" width="100%" v-bind:src="'https://player.podcard.co?color=' + color.replace('#', '') + '&episode=' + episode + '&feed=' + feed_url"></iframe>

        </div>
    </div>
</template>

<script>
    export default {
        props: {
            feed_url: {
                type: String,
                default: 'https://feeds.podhunt.app/feeds/daily/rss'
            },
            episode: {
                type: Number,
                default: 0
            },
            color: {
                type: String,
                default: ''
            },
        },
        data: () => ({
        }),
        methods: {
        },
        created() {
        },
        mounted() {
        }
    }
</script>
