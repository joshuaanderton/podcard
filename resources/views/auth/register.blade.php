@php
$input_classes = 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-0 leading-tight focus:outline-none focus:shadow-outline';
$label_classes = 'block text-gray-700 text-sm font-bold mb-2';
@endphp

@extends('layouts.auth')

@section('content')
    <h1 class="text-2xl mb-4 text-center">{{ __('Register') }}</h1>
    {{ Form::open() }}
        <div class="mb-4">
            {{ Form::label('Subdomain', null, ['class' => $label_classes]) }}
            <div class="flex">
                {{ Form::text('subdomain', '', ['placeholder' => 'Subdomain', 'class' => $input_classes, 'required' => true]) }}<span class="pl-1 my-auto">.podcard.fm</span>
            </div>
            @error('subdomain')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="mb-4">
            {{ Form::label('Podcast RSS Feed URL', null, ['class' => $label_classes]) }}
            {{ Form::text('feed_url', '', ['placeholder' => 'https://feeds.transistor.fm/build-your-saas', 'class' => $input_classes, 'required' => true]) }}
            @error('subdomain')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="mb-4">
            {{ Form::label('Email', null, ['class' => $label_classes]) }}
            {{ Form::email('email', '', ['placeholder' => 'your@email.com', 'class' => $input_classes]) }}
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="mb-4">
            {{ Form::label('password', null, ['class' => $label_classes]) }}
            {{ Form::password('password', ['placeholder' => '••••••••••', 'class' => $input_classes]) }}
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="flex items-center justify-between">
            {{ Form::submit('Sign Up', ['class' => 'w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline']) }}
        </div>
        <div class="text-center pt-3">
            <a href="{{ route('login') }}" class="text-sm">Already have an account? Sign in.</a>
        </div>
    {{ Form::close() }}
@endsection
