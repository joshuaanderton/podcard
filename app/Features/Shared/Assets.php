<?php

namespace App\Features\Shared;

use Illuminate\Support\Facades\{ File, Config };

class Assets
{
  public function handle(string $script = null)
  {
    $output        = '';
    $kitId         = Config::get('app.font_awesome_kit') ?: '17567a200a';
    $fontAwesome   = "https://kit.fontawesome.com/{$kitId}.js";
    $assetPath     = $script ?: 'resources/js/app.js';
    $devAssetsPath = "//127.0.0.1:5173/{$assetPath}";
    $manifestPath  = public_path('build/manifest.json');
    $hasManifest   = File::exists($manifestPath);

    if ($manifest = json_decode(File::get($manifestPath), true)[$assetPath] ?? null) :
      $jsPath = "/build/{$manifest['file']}";
      $cssPath = "/build/{$manifest['css'][0]}";
    endif;

    if (env('APP_ENV') === 'local') :

      $output.= "
<script type=\"module\" src=\"{$devAssetsPath}\"></script>";

    elseif($hasManifest) :

      $output.= "
<script type=\"module\" src=\"{$jsPath}\"></script>
<link rel=\"stylesheet\" href=\"{$cssPath}\">";

    endif;

    $output.= "
<script src=\"{$fontAwesome}\" crossorigin=\"anonymous\"></script>";

    return $output;
  }
}