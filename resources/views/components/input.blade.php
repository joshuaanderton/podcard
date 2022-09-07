<div class="{{ $wrapperClass }}">
  @if($label)
    <label for="{{ $id }}" class="block tracking-wide text-sm font-bold mb-2">{{ $label }}</label>
  @endif

  <div class="{{ $label ? 'mt-2' : '' }} relative flex border border-gray-200 rounded {{ $disabled ? 'bg-gray-200' : 'bg-white' }} focus-within:ring-1 focus-within:ring-gray-200 focus-within:border-gray-200 {{ $small ? 'h-8' : 'h-10' }}">
    @if($icon || $prepend)
      <div onclick="this.nextElementSibling.select()" class="flex m-0 select-none cursor-text {{ $small ? 'text-sm' : '' }}">
    @endif
    @if($icon)
      <div class="flex items-center justify-center pl-3 text-gray-300">
        <i class="min-w-4 text-center fa-sm {{ $icon }}"></i>
      </div>
    @endif
    @if($prepend)
      <div class="flex items-center whitespace-nowrap {{ $icon ? 'pl-2' : 'pl-3' }} text-gray-400">
        {!! $prepend !!}
      </div>
    @endif
    @if($icon || $prepend)
      </div>
    @endif

    @if($type === 'color')

      <input 
        type="color"
        {{ $attributes->merge([
          'id' => $id,
          'name' => $name, 
          'autocomplete' => $autocomplete ?: null,
          'disabled' => $disabled ?: null,
          'class' => 'opacity-0 absolute inset-0 w-full h-full z-10 text-black',
        ]) }}
      />

      <span class="absolute z-[1] left-1 bottom-0 h-full flex items-center">
        <span
          class="h-7 w-7 rounded border border-gray-200"
          @if($attributes['v-model'] ?? false)
            v-bind:style="{ backgroundColor: {{ $attributes['v-model'] }} || 'transparent' }"
          @else
            style="background-color: {{ $value ?? 'transparent' }}"
          @endif
        ></span>
      </span>

    @endif

    <input 
      type="{{ $type === 'color' ? 'text' : $type }}"
      {{ $attributes->merge([
        'id' => $id,
        'name' => $name, 
        'autocomplete' => $autocomplete ?: null,
        'disabled' => $disabled ?: null,
        'class' => $class . ($type === 'color' ? 'pl-10' : '') . ' h-full text-black',
      ]) }}
    />
    @if($slot)
      {!! $slot !!}
    @endif
  </div>

  @if($disclaimer)
    <div class="mt-1 text-xs text-gray-500">{!! $disclaimer !!}</div>
  @endif
    
</div>