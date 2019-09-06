@extends('layouts.ramen-games')

@section('content')
    <style>.fade-up { transition: ease all .8s; opacity: 0; position: relative; top: 5rem; } .fade-up.active { opacity: 1; top: 0; }</style>
    {{--
    <div class="hidden fixed top-0 left-0 w-screen h-screen bg-center bg-cover opacity-25" style="background-image:url('ramen.jpg');content:''"></div>
    --}}
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-full mb-5">
            <div class="p-6 mb-5 relative fade-up">
                <div style="font-size:8rem">🍜</div>
                <h1 class="font-semibold text-white text-4xl sm:text-5xl md:text-6xl mb-5 leading-none font-serif opacity-75 -mt-4">The Ramen Games</h1>
                <div class="text-xl sm:text-2xl text-white text-blue-light leading-normal max-w-lg opacity-75">A curated list of brave bootstrappers who are <strong>podcasting</strong> their journey to <strong>ramen profitability</strong> and beyond.</div>
            </div>

            @foreach($podcasts as $podcast)
                @php $episode = $podcast->episodes()->latest('published_at')->first() @endphp
                <div class="mb-6 rounded bg-white shadow lg:flex overflow-hidden">
                    <img width="400" height="400" class="hidden max-w-full mr-4 mb-4 rounded-right-bottom" src="{{ $podcast->image_url }}" alt="Sunset in the mountains">
                    <div class="w-1/1 lg:w-1/2 mb-6 lg:mb-0 lg:pr-6 h-1/1 flex flex-col p-6">
                        <h2 class="font-bold text-3xl text-black mb-3">{{ $podcast->title }}</h2>
                        <div class="mb-auto pb-4">{{ $podcast->description }}</div>
                        <div>
                            <a href="{{ $podcast->link }}" class="inline-flex hover:bg-black border border-black text-black hover:text-white py-2 px-4 rounded-full align-items-center">
                                <span class="mr-2">Check out the podcast</span>
                                <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                            </a>
                        </div>
                    </div>
                    <div class="w-1/1 lg:w-1/2 flex flex-col justify-content-center p-6">
                        <h3 class="mt-auto uppercase tracking-wide text-sm font-bold text-gray-500 mb-3">Latest Episode</h3>
                        <iframe class="mb-auto" frameBorder="0" height="180px" width="100%" src="https://player.podcard.co?feed={{ $episode->podcast()->first()->feed_url }}"></iframe>
                    </div>
                </div>
            @endforeach

            {{ $podcasts->links() }}
        </div>
        <div class="flex my-6">
            <a target="_blank" href="https://twitter.com/joshuaanderton" class="text-xs inline-block text-center text-white border-white rounded px-4 py-2 mx-auto">
                <span class="text-white font-bold flex align-items-center pt-1">
                    <img class="rounded-full mr-2" height="40" width="40" src="https://pbs.twimg.com/profile_images/1124918005719113733/nhF5z17L_400x400.png"/>
                    <code class="opacity-75">@joshuaanderton</code>
                </span>
            </a>
        </div>
    </div>
@endsection