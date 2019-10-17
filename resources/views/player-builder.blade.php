@extends('layouts.site')

@section('content')
    <div id="player_builder" class="relative">
        @include('nav', ['title' => 'Player'])
        <player-builder></player-builder>
    </div>
@endsection