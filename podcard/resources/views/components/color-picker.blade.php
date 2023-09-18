@props([
  'label' => null,
  'model' => null,
  'wrapperClass' => null,
])

<div
  class="{{ $wrapperClass }}"
  x-data="{
    model: '{{ $model }}',
    color: null,
    init() {
      this.color = $wire.get('{{ $model = $attributes->wire('model')->value() }}')
      $watch('color', value => $wire.set('{{ $model }}', value))
    }
  }"
>
  <x-label :text="$label" />
  <div class="mt-2 relative flex items-stretch">
    <x-input x-model="color" class="uppercase">
      <x-slot name="prepend">
        <div class="relative w-8 rounded-sm overflow-hidden border-white" :style="{ backgroundColor: color }">
          <input type="color" x-model="color" class="cursor-pointer absolute inset-0 opacity-0" />
        </div>
      </x-slot>
    </x-input>
  </div>
</div>
