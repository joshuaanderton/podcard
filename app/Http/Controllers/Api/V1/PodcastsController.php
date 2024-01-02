<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Podcast;
use App\Services\PodcastIndexService;
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
        $categories = (new PodcastIndexService)->categories();

        return response()->json(['categories' => $categories]);
    }

    public function showByFeedId(int $id): JsonResponse
    {
        $podcastData = (new PodcastIndexService)->podcastByFeedId($id);

        return response()->json($podcastData);
    }

    public function search(Request $request): JsonResponse
    {
        $request->validate(['q' => 'required|string']);

        $results = (new PodcastIndexService)->searchPodcasts($request->q);

        return response()->json($results);
    }

    public function trending(): JsonResponse
    {
        $results = (new PodcastIndexService)->trendingPodcasts();

        return response()->json($results);
    }
}
