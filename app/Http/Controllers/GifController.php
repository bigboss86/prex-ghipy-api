<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GifController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string',
            'limit' => 'numeric',
            'offset' => 'numeric',
        ]);

        $data = $request->only('query', 'limit', 'offset');

        return response()->json($data);
    }

    public function find(int $id): JsonResponse
    {
        return response()->json($id);
    }

    public function save(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|numeric',
            'alias' => 'required|string',
        ]);

        $data = $request->only('user_id', 'alias');

        return response()->json($data);
    }
}
