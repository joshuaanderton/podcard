<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class PodcastEpisode extends Model
{
    const defaultColor = '#008080';

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
        'ramen_games' => 'boolean',
        'published_at' => 'date:Y-m-d H:i:s',
    ];

    public function podcast(): BelongsTo
    {
        return $this->belongsTo(Podcast::class);
    }

    public function imageUrl(): ?string
    {
        if (! $imageUrl = $this->image_url) {
            $firstEpisodeWithImage = $this->podcast()->whereNotNull('image_url')->first();
            $imageUrl = $firstEpisodeWithImage->image_url ?? null;
        }

        return $imageUrl;
    }

    public static function hexToRgb(string $color): string
    {
        $color = Str::remove('#', $color);

        if (strlen($color) == 6) {
            [$r, $g, $b] = [$color[0].$color[1], $color[2].$color[3], $color[4].$color[5]];
        } elseif (strlen($color) == 3) {
            [$r, $g, $b] = [$color[0].$color[0], $color[1].$color[1], $color[2].$color[2]];
        } else {
            return false;
        }

        $r = hexdec($r);
        $g = hexdec($g);
        $b = hexdec($b);

        return "{$r},{$g},{$b}";
    }

    public static function isColorLight(string $color): bool
    {
        $rgb = explode(',', $color);

        // Check if hex code
        if (count($rgb) === 1) {
            $color = static::hexToRgb($color);
            $rgb = explode(',', $color);
        }

        $lightness = (max($rgb[0], $rgb[1], $rgb[2]) + min($rgb[0], $rgb[1], $rgb[2])) / 510.0;

        return $lightness >= .8;
    }

    public static function isColorHex(?string $color): bool
    {
        return $color !== null && Str::length(Str::remove('#', $color)) === 6;
    }

    public function playerData(): array
    {
        $color = request()->color;

        if (! static::isColorHex($color)) {
            $color = PodcastEpisode::defaultColor;
        }

        $color = PodcastEpisode::hexToRgb($color);

        $border = ((int) request()->border) !== 0;

        return [
            'file_url' => $this->file_url,
            'cover_url' => $this->imageUrl(),
            'title' => $this->title,
            'podcast' => $this->podcast->title,
            'episode' => $this->number,
            'season' => $this->season,
            'border' => $border,
            'color' => $color,
            'is_light' => PodcastEpisode::isColorLight($color),
            'playerData' => [
                'podcast' => $this->podcast->title ?: '',
                'title' => $this->title,
                'episode' => $this->number,
                'cover_url' => $this->imageUrl(),
                'file_url' => $this->file_url,
            ],
        ];
    }
}
