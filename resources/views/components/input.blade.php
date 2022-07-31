<div class="{{ $wrapperClass }}">
  @if($label)
    <label for="{{ $id }}" class="block tracking-wide font-bold mb-2">{{ $label }}</label>
  @endif

  <div class="{{ $label ? 'mt-2' : '' }} flex border border-gray-200 rounded {{ $disabled ? 'bg-gray-200' : 'bg-white' }} focus-within:ring-1 focus-within:ring-gray-200 focus-within:border-gray-200 {{ $small ? 'h-8' : 'h-10' }}">
    @if($icon || $prepend)
      <label onclick="this.nextElementSibling.select()" class="flex m-0 select-none cursor-text {{ $small ? 'text-sm' : '' }}">
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
      </label>
    @endif

    <input 
      type="{{ $type }}"
      {{ $attributes->merge([
        'id' => $id,
        'name' => $name, 
        'autocomplete' => $autocomplete ?: null,
        'disabled' => $disabled ?: null,
        'class' => $class . ' h-full', 
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