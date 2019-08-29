@extends('layouts.site')

@section('content')
    <div id="player_builder" class="container mx-auto px-4 py-8">
        <div class="max-w-full lg:flex mb-5">
            <div class="w-1/1 lg:w-2/4">
                <div class="h-0 pb-1/1 w-1/1 flex-none bg-cover rounded-t lg:rounded-t-none lg:rounded-l text-center overflow-hidden" style="background-image: url('{{ $site->image->url }}')">
                </div>
            </div>
            <div class="border-r border-b border-l border-gray-400 lg:border-l-0 lg:border-t lg:border-gray-400 bg-white rounded-b lg:rounded-b-none lg:rounded-r p-6 flex justify-between leading-normal lg:w-2/4">
                <div class="my-auto">
                    <div class="text-gray-900 font-bold text-xl mb-2">Can coffee make you a better developer?</div>
                    <p class="text-gray-700 text-base">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatibus quia, nulla! Maiores et perferendis eaque, exercitationem praesentium nihil.</p>
                </div>
            </div>
        </div>
        <iframe frameBorder="0" height="180px" width="100%" src="https://player.podcard.co?feed={{ 'https://anchor.fm/s/d5d3614/podcast/rss' }}"></iframe>
    </div>
@endsection