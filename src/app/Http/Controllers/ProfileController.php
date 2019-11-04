<?php

namespace App\Http\Controllers;

use App\User;
use App\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Get(
 *     tags={"Profile"},
 *     path="/api/v1/profile",
 *     description="Returns a user info which auth for token",
 *     operationId="profileUser",
 *     @OA\Response(
 *         response=200,
 *         description="User response",
 *         @OA\JsonContent(ref="#/components/schemas/User"),
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="unexpected error",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorModel"),
 *     ),
 *     security={
 *         {"bearerAuth": {}}
 *     }
 * )
 */

/**
 * @OA\Put(
 *     tags={"Profile"},
 *     path="/api/v1/profile",
 *     operationId="updateProfile",
 *     description="Update user profile.",
 *     @OA\RequestBody(
 *         description="Creds for registeration.",
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(ref="#/components/schemas/User"),
 *             example={"email": "api-test@email.com", "password": "secret", "name": "Api Tim Cook", "status": "free"}
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Register user",
 *         @OA\JsonContent(ref="#/components/schemas/User")
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="unexpected error",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorModel"),
 *     ),
 *     security={
 *         {"bearerAuth": {}}
 *     }
 * )
 */

class ProfileController extends Controller
{
    const MODEL = User::class;

    /**
     * Get user auth info
     *
     * @return JsonResponse
     */
    public function read(Request $request)
    {
        $user = $request->user();
        $model = User::with('items')->findOrFail($user->id);
        $data = $model->toArray();
        $data['followers'] = $model->followers()
            ->select('id')
            ->get()
            ->pluck('id');

        $data['following'] = $model->following()
            ->select('id')
            ->get()
            ->pluck('id');

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    /**
     * Update user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $user = $request->user();
        $user = User::find($user->id);

        $validator = Validator::make($request->all(), [
            'role' => 'in:budda,runner,patrol',
        ]);

        if ($validator->fails()) {
            return new JsonResponse($validator->errors(), JsonResponse::HTTP_BAD_REQUEST);
        }

        if ('budda' === $request->get('role')) {
            if (!$user->items->contains(3)) {
                return new JsonResponse([
                    'role' => ['The selected role is invalid.']
                ], JsonResponse::HTTP_BAD_REQUEST);
            }
        }

        $model= User::findOrFail($user->id);

        $fillable = array_diff($model->getFillable(), ['amount']);
        $model->fillable($fillable);
        $model->fill($request->all());
        $model->save();
        return new JsonResponse($model, JsonResponse::HTTP_OK);
    }

    /**
     * Add to user visible list.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function addFollower(Request $request, int $id)
    {
        $user = $request->user();

        $model= User::findOrFail($user->id);
        $model->followers()->syncWithoutDetaching($id);

        return new JsonResponse([], JsonResponse::HTTP_OK);
    }

    /**
     * Delete from user visible list.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFollower(Request $request, int $id)
    {
        $user = $request->user();

        $model= User::findOrFail($user->id);
        $model->followers()->detach($id);

        return new JsonResponse([], JsonResponse::HTTP_OK);
    }

    /**
     * Buy item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function buyItem(Request $request, int $id)
    {
        $user = $request->user();
        $item = Item::find($id);
        if (null === $item) {
            return new JsonResponse([], JsonResponse::HTTP_NOT_FOUND);
        }

        $total = $user->amount - $item->price;
        if ($total < 0) {
            return new JsonResponse([
                'amount' => 'Not enough.'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $user->amount = $total;
        $user->save();

        $user->items()->attach($id);

        return new JsonResponse([], JsonResponse::HTTP_OK);
    }

    /**
     * Drop item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteItem(Request $request, int $id)
    {
        $user = $request->user();
        $item = Item::find($id);
        if (null === $item) {
            return new JsonResponse([], JsonResponse::HTTP_NOT_FOUND);
        }

        $user->items()->detach($id);

        return new JsonResponse([], JsonResponse::HTTP_OK);
    }
}
