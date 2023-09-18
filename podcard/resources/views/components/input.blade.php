@props([
  'label' => null,
  'wrapperClass' => '',
  'prepend' => ''
])
@if ($label)
  <x-label :text="$label" />
@endif
<div @class(array_merge(explode(' ', $wrapperClass), [
  'mt-2' => ! ! $label,
  'bg-neutral-50',
  'dark:bg-neutral-800',
  'flex',
  'border',
  'border-transparent',
  'rounded',
  'overflow-hidden',
  'focus-within:ring-1',
  'focus-within:ring-neutral-200',
  'focus-within:border-neutral-200'
]))>
  {{ $prepend }}
  <input {{ $attributes->merge(['class' => 'block w-full placeholder-gray-400 border-none focus:ring-0 bg-transparent h-full pr-0']) }} />
</div>
