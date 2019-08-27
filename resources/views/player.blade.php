@extends('layouts.player')

@section('content')
    <div id="audio" class="player-wrapper">
        <audio-player file="{{ $file_url }}"></audio-player>
    </div>
@endsection