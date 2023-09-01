<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Podcast;
use App\Services\PodcastIndex;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PodcastsController extends Controller
{
    public function show(Podcast $podcast): JsonResponse
    {
        return response()->json($podcast->toArray());
    }

    public function categories(): JsonResponse
    {
        $categories = (new PodcastIndex)->categories();

        return response()->json(['categories' => $categories]);
    }

    public function showByFeedId(int $id): JsonResponse
    {
        $podcastData = (new PodcastIndex)->podcastByFeedId($id);

        return response()->json($podcastData);
    }

    public function search(Request $request): JsonResponse
    {
        $request->validate(['q' => 'required|string']);

        $results = (new PodcastIndex)->searchPodcasts($request->q);

        return response()->json($results);
    }

    public function trending(): JsonResponse
    {
        $results = (new PodcastIndex)->trendingPodcasts();

        return response()->json($results);
    }
}
