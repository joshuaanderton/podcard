<div class="relative bg-white dark:bg-black text-black dark:text-white">

    <div class="overlay-noise"></div>
    
    <div class="lg:h-screen flex flex-col lg:flex-row items-stretch">

        <div class="relative flex-1 overflow-y-scroll lg:max-w-xs bg-white dark:bg-black p-6">

            @if ($color)
                <div class="z-0 absolute inset-0 opacity-25 dark:opacity-60" style="background: {{ $color }}"></div>
            @endif

            <div class="z-1 absolute inset-0 opacity-25 dark:opacity-60 bg-gradient-to-l from-white dark:from-black/30 mix-blend-overlay"></div>

            <div class="h-full relative z-10 space-y-8 flex flex-col">
                <div>
                    <img class="inline-block h-auto w-14 -mt-2 -ml-3 opacity-50" src="{{ asset('favicon.png') }}" />

                    <h1 class="font-bold text-black dark:text-white text-2xl leading-none">
                        Podcard
                    </h1>
                    <div class="text-sm font-light opacity-90 text-black dark:text-white mt-2">
                        Customize your embeddable player and paste the snippet anywhere
                    </div>
                </div>

                <div class="space-y-6 text-black flex-1">
                    <x-jal::input wire:model="feedUrl" label="RSS Feed URL:" />

                    @if ($feedUrl && count($episodes ?: []) > 0)
                        <x-jal::select wire:model="currentEpisodeId" label="Episode:">
                            @foreach ($episodes as $episode)
                                <option value="{{ $episode->id }}">{{ $episode->title }}</option>
                            @endforeach
                        </x-jal::select>

                        {{--
                        <x-jal::choices wire:model="currentEpisodeId" label="Episode" :options="$episodes->pluck('id', 'title')->all()" />
                        --}}

                        <x-jal::color-picker wire:model="color" label="Player Color:" />
                    @endif
                </div>

                <div>
                    @include('made-by')
                </div>
            </div>

        </div>

        <div class="relative flex-1 overflow-y-scroll p-6">

            @if ($color)
                <div class="z-0 absolute inset-0 opacity-25 dark:opacity-60" style="background: {{ $color }}"></div>
            @endif
            
            @if ($feedUrl && $currentEpisode)
                <div class="relative z-10 container max-w-3xl mx-auto space-y-8">
                    <div class="space-y-2">
                        <x-jal::label text="Preview" />
                        <iframe src="{{ $this->playerUrl }}" frameBorder="0" height="180" width="100%"></iframe>
                    </div>
                    
                    <div class="space-y-2 pt-5 overflow-hidden mb-5">
                        <x-jal::label text="Snippet" />
                        <code class="copy-snippet bg-black p-4 block rounded-lg select-all text-white text-sm hover:cursor-pointer">
                            <span class="token tag">
                            <span class="token tag">
                                <span class="token punctuation">&lt;</span>iframe
                            </span>
                            <span class="token attr-name">frameBorder</span><span class="token attr-value"><span class="token punctuation">=</span><span class="token punctuation">"</span>0<span class="token punctuation">"</span></span>
                            <span class="token attr-name">height</span><span class="token attr-value"><span class="token punctuation">=</span><span class="token punctuation">"</span>180<span class="token punctuation">"</span></span>
                            <span class="token attr-name">width</span><span class="token attr-value"><span class="token punctuation">=</span><span class="token punctuation">"</span>100%<span class="token punctuation">"</span></span>
                            <span class="token attr-name">src</span><span class="token attr-value"><span class="token punctuation">=</span><span class="token punctuation">"</span>{{ $this->playerUrl }}<span class="token punctuation">"</span></span><span class="token punctuation">&gt;</span><span class="token tag"><span class="token punctuation">&lt;</span>/iframe</span><span class="token punctuation">&gt;</span></span>
                        </code>
                    </div>

                    <div class="space-y-2">
                        <x-jal::label for="episode" text="Or build one your self dynamically using the URL..." />
                        <code class="copy-snippet bg-black p-4 block rounded-lg select-all text-white text-sm hover:cursor-pointer">
                            <span class="opacity-70">https://player.podcard.co?</span>feed=<strong>FEED_URL</strong><span class="opacity-70">&</span>episode=<strong>TITLE_OR_NUMBER</strong><span class="opacity-70">&</span>color=<strong>HEX_CODE</strong>
                        </code>
                    </div>
                </div>
            @else

                <div class="h-full flex items-center max-w-xs mx-auto">
                    <div class="space-y-6 text-center">
                        <span class="rounded-full w-12 h-12 mx-auto flex justify-center items-center bg-black opacity-20">
                            <x-jal::icon name="arrow-left" md />
                        </span>
                        <div>Paste a valid podcast rss feed in the sidebar to pull in episodes...</div>
                    </div>
                </div>
                
            @endif

        </div>

    </div>

</div>