@php
$input_classes = 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline';
$label_classes = 'block text-gray-700 text-sm font-bold mb-2';
@endphp

@extends('layouts.app')

@section('content')
    <div class="container mx-auto pt-12">
        <h1 class="text-2xl mb-4 text-black">{{ __('Edit Account') }}</h1>

        <div class="w-full max-w-md mb-12">
            {{ Form::open(['method' => 'PUT', 'route' => 'accounts.edit']) }}
                <div class="mb-2">
                    {{ Form::label('Email', null, ['class' => $label_classes]) }}
                    {{ Form::email('email', Auth::user()->email, ['placeholder' => 'Email', 'class' => $input_classes]) }}
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="flex items-center justify-between">
                    {{ Form::submit('Update', ['class' => 'cursor-pointer bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline']) }}
                </div>
            {{ Form::close() }}
        </div>

        <h2 class="text-2xl mb-4 text-black">{{ __('Podcasts') }}</h2>
        @if(count($podcasts))
            @foreach($podcasts as $p)
                {{ $p->name }}<br/>
            @endforeach
        @else
            No podcasts!
        @endif
    </div>
@endsection
