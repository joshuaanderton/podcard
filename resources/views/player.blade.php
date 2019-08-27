@extends('layouts.player')

@section('content')
    <div id="audio" class="player-wrapper">
        <audio-player
            podcast="{{ $podcast }}"
            title="{{ $title }}"
            episode="{{ $episode }}"
            cover="{{ $cover_url }}"
            file="{{ $file_url }}"></audio-player>
    </div>
@endsection