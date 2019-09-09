@extends('layouts.ramen-games')

@section('content')
    <style>.fade-up { transition: ease all .8s; opacity: 0; position: relative; top: 5rem; } .fade-up.active { opacity: 1; top: 0; }</style>
    {{--
    <div class="hidden fixed top-0 left-0 w-screen h-screen bg-center bg-cover opacity-25" style="background-image:url('ramen.jpg');content:''"></div>
    --}}
    <div class="container mx-auto px-4 py-8 font-sans">
        <div class="max-w-full pb-6">
            <div class="relative fade-up pb-6 mb-6 text-center">
                <div style="font-size:6rem">🍜</div>
                <h1 class="font-bold text-black tracking-wider text-4xl sm:text-5xl md:text-6xl mb-5 leading-none -mt-4 uppercase">The Ramen Games</h1>
                <div class="text-xl sm:text-2xl text-black text-blue-light leading-normal max-w-xl mx-auto tracking-wide">A curated list of podcasts following brave bootstrappers and their journeys to ramen profitability and beyond.</div>
            </div>

            @foreach($podcasts as $podcast)
                @php $episode = $podcast->episodes()->latest('published_at')->first() @endphp
                <a href="{{ $podcast->link }}" class="py-6 border-t border-yellow border-dashed flex flex-col lg:flex-row overflow-hidden align-items-start">
                    <div class="w-1/1 lg:w-auto mb-4 lg:mb-0">
                        <img width="100" height="100" class="rounded" src="{{ $podcast->image_url }}" alt="Sunset in the mountains">
                    </div>
                    <div class="w-1/1 lg:w-2/3 lg:mb-0 lg:pr-6 h-1/1 lg:px-6">
                        <h2 class="font-semibold text-2xl text-black mb-1 lg:-mt-2">{{ $podcast->title }}</h2>
                        <div class="text-black mb-6 lg:mb-0">{{ $podcast->description }}</div>
                    </div>
                    <div class="my-auto mr-auto lg:mr-0 lg:ml-auto">
                        <span class="inline-flex text-black bg-yellow hover:bg-black hover:text-white py-2 px-4 rounded-full align-items-center text-sm uppercase tracking-wider font-bold">
                            Check it out
                        </span>
                    </div>
                </a>
            @endforeach

            {{ $podcasts->links() }}
        </div>
        <div class="flex my-6">
            <a target="_blank" href="https://twitter.com/joshuaanderton" class="text-xs inline-block text-center text-black border-black rounded px-4 py-2 mr-auto">
                <span class="text-black font-bold flex align-items-center pt-1">
                    <img class="rounded-full mr-2" height="40" width="40" src="https://pbs.twimg.com/profile_images/1124918005719113733/nhF5z17L_400x400.png"/>
                    <code class="opacity-75">@joshuaanderton</code>
                </span>
            </a>
        </div>
    </div>
@endsection