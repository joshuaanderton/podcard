<?php

namespace App\Models;

use App\Actions\Podcasts\Import;
use App\Actions\Podcasts\ImportNew;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Podcast extends Model
{
    protected $fillable = [
        'feed_url',
        'title',
        'owner_name',
        'owner_email',
        'image_url',
        'description',
        'link',
    ];

    protected static function booted(): void
    {
        static::creating(function ($podcast) {
            $podcast->feed_url = static::cleanFeedUrl($podcast->feed_url);
        });
    }

    public static function cleanFeedUrl(string $feedUrl): string
    {
        $feedUrl = trim($feedUrl);
        $feedUrl = Str::lower($feedUrl);
        $feedUrl = rtrim($feedUrl, '/');

        return $feedUrl;
    }

    public function episodes(): HasMany
    {
        return $this->hasMany(PodcastEpisode::class);
    }

    public function getNeedsImportAttribute(): bool
    {
        return (
            $this->updated_at === null || (
                $this->updated_at->timestamp < Carbon::now()->subDays(1)->timestamp
            )
        );
    }

    public function import(): int|null
    {
        return Import::run($this);
    }
}
