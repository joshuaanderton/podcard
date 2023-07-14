<div class="min-h-screen flex flex-col relative bg-white dark:bg-black text-black dark:text-white">

    @if ($color)
        <div class="z-0 absolute inset-0 opacity-25 dark:opacity-60" style="background: {{ $color }}"></div>
    @endif

    <div class="overlay-noise"></div>

    @livewire('jal-notifications')

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
                                <x-jal::icon-loading class="h-14 w-14 opacity-60 !fill-white" />
                            </div>

                        @else

                            <div class="relative z-[2] h-[180px] p-6 bg-black/10 flex items-center justify-center">
                                <div class="w-full max-w-xs space-y-6 text-center">
                                    <p>Paste your podcast's rss feed URL below to select an episode...</p>
                                    <span class="rounded-full w-12 h-12 mx-auto flex justify-center items-center bg-black opacity-20">
                                        <x-jal::icon name="arrow-down" md class="!fill-white" />
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
                    <x-jal::label text="Copy the iframe snippet..." />
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
                    <x-jal::label for="episode" text="Or load episodes dynamically..." />
                    <div
                        x-data="{
                            content: '{{ urlencode($this->playerDynamicUrl) }}',
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

</div>