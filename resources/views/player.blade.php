@extends('layouts.player')

@section('content')
    @if($color)
        <style>
            .player-progress { background-color: #{{ str_replace('#', '', $color) }}; }
            .player-progress .player-seeker { background-color: rgba(0,0,0,.3) !important; }
            .player-cover > a { background-color: #{{ str_replace('#', '', $color) }}; opacity: .9; }
        </style>
    @endif

    <div id="audio" style="{{ $border === 0 ? 'border:none !important' : ''  }}" class="player-wrapper">
        <player
            podcast="{{ $podcast }}"
            title="{{ $title }}"
            episode="{{ $episode }}"
            cover="{{ $cover_url }}"
            file="{{ $file_url }}"></player>
    </div>
@endsection