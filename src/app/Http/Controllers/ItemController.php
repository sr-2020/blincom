<?php

namespace App\Http\Controllers;

use App\User;
use App\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    const MODEL = Item::class;

    /**
     * Get user auth info
     *
     * @return JsonResponse
     */
    public function list(Request $request)
    {
        $user = $request->user();
        $model = self::MODEL;

        $items = $model::orderBy('name', 'asc')->get()->toArray();
        $result = array_map(function ($item) use ($user) {
            $item['allow'] = ($user->amount >= $item['price']);
            return $item;
        }, $items);

        return new JsonResponse($result, JsonResponse::HTTP_OK);
    }
}
