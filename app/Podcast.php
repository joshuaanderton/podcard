<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        return $this->hasMany('App\PodcastEpisode');
    }

    public function import()
    {
        $feed = file_get_contents($this->feed_url);
        $feed = str_replace('itunes:', '', $feed);
        $feed = simplexml_load_string($feed);
        $feed = $feed->channel;

        $this->title       = $feed->title;
        $this->owner_name  = $feed->owner->name;
        $this->owner_email = $feed->owner->email;
        $this->image_url   = !empty($feed->image['href']) ? strval($feed->image['href']) : null;
        $this->image_url   = !$this->image_url && !empty($feed->image->url) ? $feed->image->url : $this->image_url;

        $this->save();

        $episodes = [];
        foreach ($feed->item as $episode) $episodes[] = $episode;

        $episode_number = 0;
        $current_season = 1;
        $episodes = array_reverse($episodes);

        foreach ($episodes as $episode) :
            if ($episode->episodeType == 'full') $episode_number++;

            $episode = $this->episodes()->updateOrCreate(['guid' => $episode->guid], [
                'title'        => $episode->title,
                'image_url'    => !empty($episode->image['href']) ? strval($episode->image['href']) : !empty($episode->image->url) ? $episode->image->url : null,
                'file_url'     => $episode->enclosure['url'],
                'number'       => $episode->episode ?: $episode_number > 0 ? $episode_number : null,
                'season'       => $episode->season  ?: null,
                'episode_type' => $episode->episodeType,
                'published_at' => date('Y-m-d H:i:s', strtotime($episode->pubDate))
            ]);
        endforeach;
    }

    static function hexToRgb($colour) {
        if ($colour[0] == '#') $colour = substr($colour, 1);

        if (strlen($colour) == 6) :
            list($r, $g, $b) = [$colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5]];
        elseif (strlen($colour) == 3) :
            list($r, $g, $b) = [$colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]];
        else :
            return false;
        endif;

        $r = hexdec($r);
        $g = hexdec($g);
        $b = hexdec($b);

        return "{$r},{$g},{$b}";
    }
}