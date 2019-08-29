@extends('layouts.player')

@section('content')
    @if (!$podcast)
        <div style="text-align:center">
            <h1>Oops!</h1>
            <p style="color:#999">Please provide a <strong>?feed=</strong> parameter with your RSS feed url!</p>
        </div>
    @else
        <style>.player-wrapper {height:180px;}</style>
        @if($color)
            <style>
                .player-wrapper { border-color: rgba({{ $color }},.3) !important; }
                .player-wrapper:before { content:' '; height:100%; width:100%; position:absolute; left:0; right:0; background-color: rgba({{ $color }},.15); }
                .player-progress { background-color: rgba({{ $color }},1); }
                .player-progress .player-seeker { background-color: rgba(0,0,0,.3) !important; }
                .player-cover > a { background-color: rgba({{ $color }},0.8); }
                .player-mute svg { color: rgba({{ $color }},.7) !important; }
                .player-time-current, .player-time-total { color: rgba({{ $color }},1); }
            </style>
        @endif

        <div id="audio" style="{{ $border === 0 ? 'border:none !important' : ''  }}" class="player-wrapper">
            <player
                podcast="{{ $podcast }}"
                title="{{ $title }}"
                season="{{ $season }}"
                episode="{{ $episode }}"
                cover="{{ $cover_url }}"
                file="{{ $file_url }}"></player>
        </div>
    @endif
@endsection