<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PodcastEpisode extends Model
{
    protected $fillable = [
        'podcast_id',
        'published_at',
        'guid',
        'title',
        'image_url',
        'file_url',
        'number',
        'season',
        'episode_type',
    ];

    public function podcast()
    {
        return $this->belongsTo('App\Podcast');
    }

    public function imageUrl()
    {
        return $this->image_url ?: $this->podcast()->first()->image_url;
    }
}