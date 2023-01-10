<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;

class Podcast extends Model
{
    protected $fillable = [
        'title',
        'email',
        'image_url',
        'feed_url',
    ];

    public function episodes()
    {
        return $this->hasMany(PodcastEpisode::class);
    }

    public static function firstOrImport(string $feedUrl): self
    {
        if (! $podcast = Podcast::where('feed_url', $feedUrl)->first()) {
            $podcast = new Podcast;
            $podcast->feed_url = $feedUrl;
            $podcast->import();
        }

        return $podcast;
    }

    public function import(): void
    {
        try {
            $feed = file_get_contents($this->feed_url);
        } catch (Exception $e) {
            return;
        }

        $feed = str_replace('itunes:', '', $feed);
        $feed = simplexml_load_string($feed);
        $feed = $feed->channel;

        $this->title = $feed->title;
        $this->description = $feed->description;
        $this->link = strval($feed->link) ? $feed->link : null;
        $this->link = ! $this->link && ! empty($feed->link['href']) && strval($feed->link['href']) ? $feed->link['href'] : null;
        $this->owner_name = $feed->owner->name;
        $this->owner_email = $feed->owner->email;

        // TODO: fallback to null and migrate field to be nullable
        $this->image_url = ! empty($feed->image['href']) ? strval($feed->image['href']) : '';
        $this->image_url = ! $this->image_url && ! empty($feed->image->url) ? $feed->image->url : $this->image_url;

        $this->save();

        $episodes = [];
        foreach ($feed->item as $episode) {
            $episodes[] = $episode;
        }

        $episodes = array_reverse($episodes);

        foreach ($episodes as $episode) {
            if (empty($episode->enclosure['url'])) {
                continue;
            }

            $image_url = ! empty($episode->image['href']) && strval($episode->image['href']) ? $episode->image['href'] : null;
            $image_url = ! $image_url && ! empty($episode->image->url) ? $episode->image->url : null;

            $episode = $this->episodes()->updateOrCreate(['guid' => $episode->guid], [
                'title' => $episode->title,
                'image_url' => $image_url,
                'file_url' => $episode->enclosure['url'],
                'number' => $episode->episode ?: null,
                'season' => $episode->season ?: null,
                'episode_type' => $episode->episodeType,
                'published_at' => date('Y-m-d H:i:s', strtotime($episode->pubDate)),
            ]);
        }
    }

    public static function hexToRgb($color)
    {
        if ($color[0] == '#') {
            $color = substr($color, 1);
        }

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

    public static function isColorLight($color)
    {
        $rgb = explode(',', $color);
        $lightness = (max($rgb[0], $rgb[1], $rgb[2]) + min($rgb[0], $rgb[1], $rgb[2])) / 510.0;

        return $lightness >= .8;
    }
}
