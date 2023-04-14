@php $showFields = $feedUrl && $episodes !== null @endphp

<div class="flex flex-wrap lg:flex-nowrap items-start justify-center flex-1">
    
    <div class="min-w-full flex-1 lg:min-w-0 lg:max-w-sm">
      <x-jal::input label="RSS Feed URL:" wire:model.lazy="feedUrl" placeholder="e.g. https://feeds.transistor.fm/my-podcast">
        @if ($feedUrl)
          <div class="my-auto">
            <button class="text-black/60 rounded-full w-6 h-6 border-none hover:bg-black/10 mr-1 inline-flex items-center justify-center" wire:click="$set('feedUrl', null)">
              <x-jal::icon name="x-mark" />
            </button>
          </div>
        @endif
      </x-jal::input>
    </div>

    @if ($showFields)

      <div class="flex-1 mt-6 lg:mt-0 lg:ml-6">
        <x-jal::select wire:model="currentEpisodeId" label="Episode:">
            @forelse ($episodes ?: [] as $episode)
                <option value="{{ $episode->id }}">{{ $episode->title }}</option>
            @empty
                <option disabled selected value="null">None found</option>
            @endforelse
        </x-jal::select>
          
        {{-- <x-jal::choices id="episode" wire:model="currentEpisodeId" label="Episode:" :options="$episodes ? $episodes->all() : []" /> --}}
      </div>

      <div class="flex-1 mt-6 lg:mt-0 ml-6">
        <x-jal::color-picker wire:model.lazy="color" label="Player Color:" />
      </div>

    @endif

</div>