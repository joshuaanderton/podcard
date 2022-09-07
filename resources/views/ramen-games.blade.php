@extends('layouts.ramen-games')

@section('content')
    <div class="relative pt-48 pb-32 md:py-32">
        <div class="container mx-auto px-4">
            <div class="max-w-full pb-6">
                <div class="relative fade-up pb-6 mb-6 text-center">
                    <div style="font-size:6rem">🍜</div>
                    <h1 class="font-sans font-bold text-black text-4xl sm:text-5xl -mt-5 ">The Ramen Games</h1>
                    <div class="font-sans text-xl sm:text-2xl text-black mb-12 md:max-w-md mx-auto">A curated list of podcasts following brave bootstrappers and their journeys to ramen profitability and beyond.</div>
                </div>

                @foreach($podcasts as $podcast)
                    @php $episode = $podcast->episodes()->latest('published_at')->first() @endphp
                    <a href="{{ $podcast->link }}" class="font-sans py-6 border-t border-yellow border-dashed flex flex-col lg:flex-row overflow-hidden align-items-start">
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
                <a target="_blank" href="https://twitter.com/gettingtoramen" class="text-xs inline-block text-center text-black border-black rounded px-4 py-2 mr-auto">
                    <span class="text-black font-bold flex align-items-center pt-1">
                        <img class="rounded-full mr-2" height="40" width="40" src="https://ucarecdn.com/4dc7ec09-5c05-43e9-8fc0-889834614778//-/resize/150/"/>
                        <code class="opacity-75">Joshua Anderton</code>
                    </span>
                </a>
            </div>
        </div>
    </div>
@endsection