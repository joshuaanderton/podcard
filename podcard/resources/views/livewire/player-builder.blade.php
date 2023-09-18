<div class="min-h-screen flex flex-col relative bg-white dark:bg-black text-black dark:text-white">
    <div aria-live="assertive" class="flex-col pointer-events-none fixed inset-0 items-end p-4 sm:items-start sm:p-6 z-[100]">
        @if (session()->has('success-message'))
            <x-notification id="success" :text="session('success-message')" success />
        @endif
    </div>

    @if ($color)
        <div class="z-0 absolute inset-0 opacity-25 dark:opacity-60" style="background: {{ $color }}"></div>
    @endif

    <div class="overlay-noise"></div>

    <div class="px-8 w-full max-w-3xl mx-auto">

        <div class="relative z-10 container max-w-3xl mx-auto space-y-8">

            <div class="relative bg-white dark:bg-black p-8 lg:-mx-8 rounded-b-3xl">

                @if ($color)
                    <div class="z-0 absolute inset-0 opacity-25 dark:opacity-60 rounded-b-3xl" style="background: {{ $color }}"></div>
                @endif

                <div class="z-1 absolute inset-0 opacity-25 dark:opacity-60 bg-gradient-to-l from-white/30 dark:from-black/30 to-white/10 dark:to-black/10 mix-blend-overlay rounded-b-3xl"></div>

                <div class="z-30 lg:fixed lg:top-[8.3rem] lg:-left-[5rem] max-w-xs pb-8 lg:pb-0">
                    @include('livewire.player-builder.branding', ['class' => 'relative lg:rotate-90'])
                </div>

                <div class="relative z-[2] space-y-8" x-data>

                    <div class="relative rounded-[8px] overflow-hidden">

                        @if ($feedUrl && $previewEpisode)
                            <iframe
                                id="iframe"
                                class="relative z-[2]"
                                src="{{ $this->playerUrl }}"
                                frameBorder="0"
                                height="180"
                                width="100%"></iframe>

                            <div class="absolute z-1 inset-0 flex items-center justify-center animate-pulse bg-black/10">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="relative animate-spin h-14 w-14 opacity-60 !fill-white">
                                    <path class="fill-current" d="M256 32C256 14.33 270.3 0 288 0C429.4 0 544 114.6 544 256C544 302.6 531.5 346.4 509.7 384C500.9 399.3 481.3 404.6 465.1 395.7C450.7 386.9 445.5 367.3 454.3 351.1C470.6 323.8 480 291 480 255.1C480 149.1 394 63.1 288 63.1C270.3 63.1 256 49.67 256 31.1V32z"></path>
                                    <path class="fill-current opacity-40" d="M287.1 64C181.1 64 95.1 149.1 95.1 256C95.1 362 181.1 448 287.1 448C358.1 448 419.3 410.5 452.9 354.4L453 354.5C446.1 369.4 451.5 387.3 465.1 395.7C481.3 404.6 500.9 399.3 509.7 384C509.9 383.7 510.1 383.4 510.2 383.1C466.1 460.1 383.1 512 288 512C146.6 512 32 397.4 32 256C32 114.6 146.6 0 288 0C270.3 0 256 14.33 256 32C256 49.67 270.3 64 288 64H287.1z"></path>
                                </svg>
                            </div>

                        @else

                            <div class="relative z-[2] h-[180px] p-6 bg-black/10 flex items-center justify-center">
                                <div class="w-full max-w-xs space-y-6 text-center">
                                    <p>Paste your podcast's rss feed URL below to select an episode...</p>
                                    <span class="rounded-full w-12 h-12 mx-auto flex justify-center items-center bg-black opacity-20">
                                        <x-heroicon-s-chevron-down class="!fill-white" />
                                    </span>
                                </div>
                            </div>

                        @endif

                    </div>

                    @include('livewire.player-builder.toolbar')

                </div>

            </div>

            @if ($feedUrl && $previewEpisode)
                <div class="space-y-2 overflow-hidden mb-5">
                    <x-label text="Copy the iframe snippet..." />
                    <div
                        x-data='{
                            content: "<iframe src=\"{{ urldecode($this->playerUrl) }}\" style=\"border:none;height:180px;width:100%\"></iframe>",
                            copied: false,
                            copy() {
                                window.copyToClipboard($data.content)
                                this.copied = true
                                setTimeout(() => this.copied = false, 1000)
                            }
                        }'
                        x-on:click="copy"
                        class="relative"
                    >
                        <x-button-copy :color="$color" class="absolute top-1.5 right-1.5" />
                        <pre class="text-xs whitespace-nowrap overflow-auto bg-gradient-to-r from-black/50 to-black/40 p-4 rounded-lg text-white hover:cursor-pointer"><code class="bg-transparent"><span class="opacity-60"><span class="token punctuation">&lt;</span>iframe src="</span>{{ urldecode($this->playerUrl) }}<span class="opacity-60">" style="border:none;height:180px;width:100%"<span class="token punctuation">&gt;</span><span class="token punctuation">&lt;</span>/iframe<span class="token punctuation">&gt;</span></span></code></pre>
                    </div>
                </div>

                <div class="space-y-2">
                    <x-label for="episode" text="Or load episodes dynamically..." />
                    <div
                        x-data="{
                            content: '{{ urldecode($this->playerDynamicUrl) }}',
                            copied: false,
                            copy() {
                                window.copyToClipboard($data.content)
                                this.copied = true
                                setTimeout(() => this.copied = false, 1000)
                            }
                        }"
                        x-on:click="copy"
                        class="copy-snippet relative"
                    >
                        <x-button-copy :color="$color" class="absolute top-1.5 right-1.5" />
                        <pre class="text-xs whitespace-nowrap overflow-auto bg-gradient-to-r from-black/50 to-black/40 p-4 rounded-lg text-white hover:cursor-pointer">
                            <code>
                                <span class="opacity-60">{{ config('app.player_url') }}/</span>
                                <span class="block pl-4"><span class="opacity-60">?feed=</span>{{ $feedUrl }}</span>
                                <span class="block pl-4"><span class="opacity-60">&color=</span>{{ Str::remove('#', $color) }}</span>
                                @if($selectedEpisodeId !== 'latest')
                                <span class="block pl-4"><span class="opacity-60">&number=</span>{{ urlencode($previewEpisode['number']) }}</span>
                                @endif
                            </code>
                        </pre>
                    </div>
                </div>
            @endif

        </div>

    </div>

    <footer class="py-8 mt-auto max-w-7xl mx-auto relative z-20 flex items-end space-x-14">
        @include('livewire.player-builder.product-hunt')
        @include('livewire.player-builder.made-by')
    </footer>


    @if ($salestreamTeam = env('SALESTREAM_TEAM_ID'))
      <script type="text/javascript">window.$salestream=[];(function(){d=document;s=d.createElement("script");s.src="https://salestream.app/{{ $salestreamTeam }}/embed.js";s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})();</script>
    @endif

</div>
