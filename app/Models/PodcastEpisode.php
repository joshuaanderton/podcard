<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    protected $casts = [
        'ramen_games' => 'boolean'
    ];

    public function podcast()
    {
        return $this->belongsTo(Podcast::class);
    }

    public function imageUrl()
    {
        return $this->image_url ?: $this->podcast()->first()->image_url;
    }
}