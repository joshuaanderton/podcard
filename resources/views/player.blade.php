@extends('layouts.player')

@section('content')
    @if (!$podcast)
        <div style="text-align:center">
            <h1>Oops!</h1>
            <p style="color:#999">Please provide a <strong>?feed=</strong> parameter with your RSS feed url!</p>
        </div>
    @else
        <style>
        @if($color)
            .player,
            h2,
            .player-time-current,
            .player-time-total,
            .player-mute svg { color: {{ $is_light ? '#000' : "#fff" }}; }
            .player-progress { background-color: {{ $is_light ? '#000' : "#fff" }}; }
            .player-cover,
            .player-wrapper { background-color: rgba({{ $color }},1); }
            .player-progress .player-seeker { background-color: rgba({{ $is_light ? '255,255,255,.55' : $color . ',.4' }}) !important; }
            @if(!$is_light)
                .player-cover > a { background-color: rgba({{ $color }},0.8); }
            @endif
        @else
            .player-wrapper { border: 1px solid #e0e0e0; }
        @endif
        </style>

        <div id="audio" class="player-wrapper">
            <player
                podcast="{{ $podcast }}"
                title="{{ $title }}"
                episode="{{ $episode }}"
                cover="{{ $cover_url }}"
                file="{{ $file_url }}"></player>
        </div>
    @endif
@endsection