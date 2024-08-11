<?php

namespace App\Http\Controllers;

use App\Api\Ghipy;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GifController extends Controller
{
    public function __construct(
        private Ghipy $ghipy,
    ) {
    }

    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string',
            'limit' => 'numeric',
            'offset' => 'numeric',
        ]);

        $query = $request->get('query');
        $limit = $request->get('limit', 25);
        $offset = $request->get('offset', 0);

        try {
            $gifs = $this->ghipy->searchGif(
                $query,
                $limit,
                $offset
            );

            return response()->json($gifs);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function find(string $id): JsonResponse
    {
        try {
            $gif = $this->ghipy->findGif($id);

            if (empty($gif['data'])) {
                return response()->json(['message' => 'Gif not found'], 404);
            }

            return response()->json($gif);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function save(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|numeric',
            'alias' => 'required|string',
        ]);

        $userId = $request->get('user_id');
        $alias = $request->get('alias');

        try {
            $gif = $this->ghipy->findGif($id);

            if (empty($gif['data'])) {
                return response()->json(['message' => 'Gif not found'], 404);
            }

            /** @var User|null $user */
            $user = User::query()->find($userId);

            if (!$user instanceof User) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $user->gif_id = $id;
            $user->gif_alias = $alias;

            $user->save();

            return response()->json(null, 204);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }
}
