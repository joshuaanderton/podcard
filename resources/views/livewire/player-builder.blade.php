<div class="min-h-screen flex flex-col relative bg-white dark:bg-black text-black dark:text-white">

    @if ($color)
        <div class="z-0 absolute inset-0 opacity-25 dark:opacity-60" style="background: {{ $color }}"></div>
    @endif

    <div class="overlay-noise"></div>

    @livewire('jal-notifications')
    
    <div class="px-8 w-full max-w-3xl mx-auto">
    
        <div class="relative z-10 container max-w-3xl mx-auto space-y-8">

            <div class="relative bg-white dark:bg-black p-8 lg:-mx-8 rounded-b-3xl overflow-hidden">

                @if ($color)
                    <div class="z-0 absolute inset-0 opacity-25 dark:opacity-60" style="background: {{ $color }}"></div>
                @endif
                
                <div class="z-1 absolute inset-0 opacity-25 dark:opacity-60 bg-gradient-to-l from-white/30 dark:from-black/30 to-white/10 dark:to-black/10 mix-blend-overlay"></div>

                <div class="z-30 lg:fixed lg:top-[8.3rem] lg:-left-[5rem] max-w-xs pb-8 lg:pb-0">
                    @include('branding', ['class' => 'relative lg:rotate-90'])
                </div>

                <div class="relative z-[2] space-y-8" x-data>

                    <div class="relative rounded-[8px] overflow-hidden">

                        @if ($feedUrl && $currentEpisode)

                            <iframe
                                id="iframe"
                                class="relative z-[2] opacity-0 transition-opacity duration-500"
                                onload="document.getElementById('iframe').classList.remove('opacity-0')"
                                src="{{ $this->playerUrl }}"
                                frameBorder="0"
                                height="180"
                                width="100%"></iframe>

                            <div class="absolute z-1 inset-0 flex items-center justify-center animate-pulse bg-black/10">
                                <x-jal::icon-loading class="h-14 w-14 opacity-60" />
                            </div>

                        @else

                            <div class="relative z-[2] h-[180px] p-6 bg-black/10 flex items-center justify-center">
                                <div class="w-full max-w-xs space-y-6 text-center">
                                    <p>Paste your podcast's rss feed URL below to select an episode...</p>
                                    <span class="rounded-full w-12 h-12 mx-auto flex justify-center items-center bg-black opacity-20">
                                        <x-jal::icon name="arrow-down" md />
                                    </span>
                                </div>
                            </div>
                            
                        @endif

                    </div>
                    
                    @include('toolbar')

                </div>
                
            </div>
            
            @if ($feedUrl && $currentEpisode)
                <div class="space-y-2 overflow-hidden mb-5">
                    <x-jal::label text="Copy the iframe snippet..." />
                    <pre class="copy-snippet text-xs whitespace-nowrap overflow-auto bg-gradient-to-r from-black/50 to-black/40 p-4 rounded-lg text-white hover:cursor-pointer"><code class="bg-transparent"><span class="opacity-60"><span class="token punctuation">&lt;</span>iframe src="</span>{{
                            $this->playerUrl
                        }}<span class="opacity-60">" style="border:none;height:180px;width:100%"<span class="token punctuation">&gt;</span><span class="token punctuation">&lt;</span>/iframe<span class="token punctuation">&gt;</span></span></code></pre>
                </div>

                <div class="space-y-2">
                    <x-jal::label for="episode" text="Or load episodes dynamically..." />
                    <pre class="copy-snippet text-xs whitespace-pre overflow-auto bg-gradient-to-r from-black/50 to-black/40 p-4 rounded-lg text-white hover:cursor-pointer"><code><span class="opacity-60">{{ $this->playerDynamicUrl }}</span>
    <span class="opacity-60">?feed=</span>{{ $feedUrl }}
    <span class="opacity-60">&episode=</span>{{ $currentEpisode->title }}
    <span class="opacity-60">&color=</span>{{ Str::remove('#', $color) }}</code></pre>
                </div>
            @endif

        </div>

    </div>

    <footer class="py-8 mt-auto max-w-7xl mx-auto relative z-20 flex items-end space-x-14">
        @include('product-hunt')
        @include('made-by')
    </footer>

</div>