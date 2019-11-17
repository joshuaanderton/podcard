@php
$input_classes = 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline';
$label_classes = 'block text-gray-700 text-sm font-bold mb-2';
@endphp

@extends('layouts.auth')

@section('content')
    <h1 class="text-2xl mb-4 text-center">{{ __('Login') }}</h1>
    {{ Form::open() }}
        <div class="mb-2">
            {{ Form::label('Email', null, ['class' => $label_classes]) }}
            {{ Form::email('email', '', ['placeholder' => 'Email', 'class' => $input_classes]) }}
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="mb-2">
            <div class="flex">
                {{ Form::label('password', null, ['class' => $label_classes]) }}
                <a href="{{ route('password.request') }}" class="ml-auto text-xs text-underline">@lang('Forgot?')</a>
            </div>
            {{ Form::password('password', ['placeholder' => 'Password', 'class' => $input_classes]) }}
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="flex items-center justify-between">
            {{ Form::submit('Sign in', ['class' => 'w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline']) }}
        </div>
        <div class="text-center pt-3">
            <a href="{{ route('register') }}" class="text-sm">Need an account? Sign up.</a>
        </div>

    {{ Form::close() }}
@endsection
