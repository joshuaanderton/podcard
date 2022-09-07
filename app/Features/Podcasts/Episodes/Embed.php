<?php

namespace App\Features\Podcasts\Episodes;

use Illuminate\Http\Request;

class Embed
{
  public function handle(Request $request)
  {

    if (!$request->url) abort(404);

    $parts = parse_url($request->url);
    parse_str($parts['query'], $passed_params);

    foreach($passed_params as $key => $val) :
        if (in_array($key, ['feed', 'color', 'episode'])) :
            $params[$key] = $val;
        endif;
    endforeach;

    $url = 'https://player.' . config('app.host') . '?' . http_build_query($params);

    return [
        'version'          => '1.0',
        'provider_name'    => 'Podcard',
        'provider_url'     => config('app.url'),
        'title'            => '',
        'html'             => "<iframe width=\"100%\" height=\"180\" scrolling=\"no\" frameborder=\"0\" src=\"{$url}?as_embed\"></iframe>",
        'height'           => '180',
        'width'            => '800',
        'type'             => 'rich',
        //'thumbnail_url'    => "http://api.screenshotlayer.com/api/capture?access_key=" . env('SCREENSHOT_LAYER_ACCESS_KEY') . "&viewport=1024x612&width=1000&url=" . urlencode($url),
        //'thumbnail_width'  => 1200,
        //'thumbnail_height' => 630
    ];
  }
}