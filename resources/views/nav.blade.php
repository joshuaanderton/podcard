<div class="absolute top-0 left-0 text-black z-20 w-full">
    <div class="p-6 flex flex-col md:flex-row text-center">
        <a class="font-sans text-xl mb-4 md:mb-0" href="{{ env('SITE_URL') }}"><strong>Podcard</strong> {{ $title }}</a>
        <div class="flex flex-col md:flex-row mx-auto md:mr-0">
            <a class="text-black hover:text-gray-700 font-semibold md:ml-6 py-1" href="//editing.{{ env('SESSION_DOMAIN') }}">Podcast Editing Service</a>
            <a class="text-black hover:text-gray-700 font-semibold md:ml-6 py-1" href="//player.{{ env('SESSION_DOMAIN') }}">Embeddable Player</a>
            <a class="text-black hover:text-gray-700 font-semibold md:ml-6 py-1" href="//ramengames.{{ env('SESSION_DOMAIN') }}">Ramen Games</a>
        </div>
    </div>
</div>