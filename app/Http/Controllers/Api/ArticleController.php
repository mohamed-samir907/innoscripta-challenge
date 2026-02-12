<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetArticlesRequest;
use App\Services\ArticleService;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    public function __construct(protected ArticleService $service)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetArticlesRequest $request): JsonResponse
    {
        $articles = $this->service->getArticles($request->validated());

        return response()->json($articles);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        return response()->json($this->service->getArticle($id));
    }
}
