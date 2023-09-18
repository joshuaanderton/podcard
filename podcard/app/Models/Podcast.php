<?php

declare(strict_types=1);

namespace App\Models;

use App\Actions\Podcasts\Import;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Podcast extends Model
{
    protected $fillable = [
        'feed_url',
        'guid',
        'title',
        'owner_name',
        'owner_email',
        'image_url',
        'description',
        'link',
    ];

    public function getRouteKeyName()
    {
        return 'guid';
    }

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
        return
            $this->updated_at === null || (
                $this->updated_at->timestamp < Carbon::now()->subDays(1)->timestamp
            );
    }

    public function import(): int|null
    {
        return Import::run($this);
    }
}
