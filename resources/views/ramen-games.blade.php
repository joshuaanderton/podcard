@extends('layouts.ramen-games')

@section('content')
    <div class="relative pt-48 pb-32 md:py-32">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto pb-6">

                <div class="relative fade-up pb-6 mb-6 text-center">
                    <div class="text-[6rem]">🍜</div>
                    <h1 class="font-sans font-bold text-4xl sm:text-5xl -mt-2 text-black dark:text-white">The Ramen Games</h1>
                    <div class="font-sans text-xl sm:text-2xl mb-12 md:max-w-md mx-auto mt-3">A curated list of podcasts following brave bootstrappers and their journeys to ramen profitability and beyond.</div>
                </div>

                <div class="divide-y divide-orange-300 dark:divide-orange-400 divide-dashed border-t border-orange-300 dark:border-orange-400 border-dashed">
                    @foreach($podcasts as $podcast)
                        <a href="{{ $podcast->link }}" class="group font-sans py-6 flex flex-col lg:flex-row overflow-hidden align-items-start">
                            <div class="w-1/1 lg:w-auto mb-4 lg:mb-0">
                                <img width="100" height="100" class="rounded" src="{{ $podcast->image_url }}" alt="Sunset in the mountains">
                            </div>
                            <div class="w-1/1 lg:w-2/3 lg:mb-0 lg:pr-6 h-1/1 lg:px-6">
                                <h2 class="font-semibold text-2xl mb-1 lg:-mt-1">{{ $podcast->title }}</h2>
                                <div class="mb-6 lg:mb-0">{{ $podcast->description }}</div>
                            </div>
                            <div class="my-auto mr-auto lg:mr-0 lg:ml-auto">
                                <span class="inline-flex bg-gradient-to-r from-orange-200 to-orange-100 text-orange-400 opacity-50 group-hover:opacity-100 transition-all dark:from-orange-700 dark:to-orange-800 dark:text-orange-400 dark:group-hover:from-orange-800 dark:group-hover:to-orange-800 group-hover:from-orange-200 group-hover:to-orange-200 py-2 px-4 rounded-full items-center text-xs uppercase tracking-wider font-bold">
                                    Check it out
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{ $podcasts->links() }}
            </div>
            
            @include('made-by')
            
        </div>
    </div>
@endsection