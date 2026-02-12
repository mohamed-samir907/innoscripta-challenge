<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FeedService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedController extends Controller
{
    public function __construct(protected FeedService $service)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $feed = $this->service->getFeed(Auth::user());

        if ($feed->isEmpty()) {
            return response()->json(['message' => 'No preferences set. Please add some preferences to see your feed.'], 200);
        }

        return response()->json($feed);
    }
}
