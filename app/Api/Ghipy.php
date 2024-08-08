<?php

namespace App\Api;

use Illuminate\Support\Facades\Http;

class Ghipy
{
    private string $baseUrl;
    private string $apiKey;

    public function __construct()
    {
        $this->baseUrl = 'https://api.giphy.com/v1';
        $this->apiKey = env('GHIPY_API_KEY');
    }

    public function searchGif(string $query, int $limit, int $offset): array
    {
        $url = $this->baseUrl . '/gifs/search';

        $response = Http::get($url, [
            'api_key' => $this->apiKey,
            'q' => $query,
            'limit' => $limit,
            'offset' => $offset,
        ]);

        return $response->json()['data'] ?? [];
    }

    public function findGif(string $id): array
    {
        $url = $this->baseUrl . '/gifs/' . $id;

        $response = Http::get($url, [
            'api_key' => $this->apiKey,
        ]);

        return $response->json()['data'] ?? [];
    }
}
