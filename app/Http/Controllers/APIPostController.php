<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Http;

class APIPostController extends Controller
{
    public function index(): View
    {
        // Call external placeholder API and show results in a simple view
        $response = Http::get('https://jsonplaceholder.typicode.com/posts');

        if (! $response->successful()) {
            abort(502, 'Upstream API error');
        }

        $posts = $response->json();

    // Render the Blade file by absolute path to avoid view discovery issues in some analyzers.
    return view()->file(resource_path('views/posts/index.blade.php'), ['posts' => $posts]);
    }


    /**
     * Store a simple JSON payload for demonstration.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
        ]);

        // In a real app you would persist this. For now return the validated payload.
        return response()->json(['status' => 'ok', 'data' => $data], 201);
    }
}
