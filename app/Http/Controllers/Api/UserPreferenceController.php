<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserPreferenceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPreferenceController extends Controller
{
    public function __construct(protected UserPreferenceService $service)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json($this->service->getPreferences(Auth::user()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'preferences' => 'required|array',
            'preferences.*.type' => 'required|string|in:source,category,author',
            'preferences.*.value' => 'required|string',
        ]);

        $this->service->savePreferences(Auth::user(), $validated['preferences']);

        return response()->json(['message' => 'Preferences saved successfully.']);
    }
}