@props([
  'name' => null,
  'label' => null,
  'disclaimer' => null,
  'options' => [],
  'wrapperClass' => null,
])
@php
  $model = $attributes->wire('model')->value();
  $id = $model ?? $name ?? (string) Str::orderedUuid();
  $attributes['class'] = implode(' ', [
    'min-h-[2.25rem]',
    'block',
    'w-full',
    'rounded-md',
    'border-transparent',
    'bg-neutral-50',
    'dark:bg-neutral-800',
    'py-1',
    'pl-3',
    'pr-10',
    'text-base',
    'focus:ring-0',
    'focus:outline-none',
    'focus:ring-1',
    'focus:ring-neutral-200',
    'focus:border-neutral-200'
  ]);

  if ($label) {
    $attributes['class'] = "{$attributes['class']} mt-2";
  }
@endphp

<div class="{{ $wrapperClass }}">

  @if ($label)
    <x-label for="{{ $id }}" :text="$label" />
  @endif

  <select {{ $attributes->merge(compact('name', 'id')) }}>
    @if ($slot->isNotEmpty())
      {!! $slot !!}
    @endif
    @foreach ($options as $value => $label)
      <option value="{{ $value }}">{{ $label }}</option>
    @endforeach
  </select>

  @if ($disclaimer)
    @if ($disclaimer->attributes ?? false)
    <div {{ $disclaimer->attributes->merge(['class' => 'mt-1 text-xs text-neutral-500']) }}>
    @else
    <div class="mt-1 text-xs text-neutral-500">
    @endif
      {!! $disclaimer !!}
    </div>
  @endif

  @if ($model ?? $name ?? false)
    @error($model ?? $name)
      <div class="text-sm text-red-600 dark:text-red-400">{{ $message }}</div>
    @enderror
  @endif

</div>
