@php $showFields = $feedUrl && $episodes !== null @endphp

<div class="flex flex-wrap lg:flex-nowrap items-start justify-center flex-1">

    <div class="min-w-full flex-1 lg:min-w-0 lg:max-w-sm">
      <x-input label="RSS Feed URL:" wire:model.lazy="feedUrl" placeholder="e.g. https://feeds.transistor.fm/my-podcast">
        @if ($feedUrl)
          <div class="my-auto">
            <button class="text-black/60 rounded-full w-6 h-6 border-none hover:bg-black/10 mr-1 inline-flex items-center justify-center" wire:click="$set('feedUrl', null)">
              <x-heroicon-s-x-mark />
            </button>
          </div>
        @endif
      </x-input>
    </div>

    @if ($showFields)

      <div class="flex-1 mt-6 lg:mt-0 lg:ml-6">
        <x-select wire:model="selectedEpisodeId" label="Episode:">
            @if (count($episodes ?: []))
              <option value="latest">Latest Episode</option>
            @endif
            @forelse ($episodes ?: [] as $episode)
                <option value="{{ $episode['guid'] }}">{{ $episode['title'] }}</option>
            @empty
                <option disabled selected value="null">None found</option>
            @endforelse
        </x-select>
      </div>

      <div class="flex-1 mt-6 lg:mt-0 ml-6">
        <x-color-picker wire:model.lazy="color" label="Player Color:" />
      </div>

    @endif

</div>
