<?php

use App\Http\Controllers\UserController;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Illuminate\Http\JsonResponse;

class ProfileTest extends TestCase
{
    use DatabaseMigrations;

    const CONTROLLER = UserController::class;

    /**
     * Create factory.
     *
     * @return \Laravel\Lumen\Application
     */
    protected function makeFactory()
    {
        $controller = static::CONTROLLER;
        $model = factory($controller::MODEL)->make();
        return $model;
    }

    /**
     * A basic test create.
     *
     * @return void
     */
    public function testProfileAuthorizationSuccess()
    {
        $user = App\User::find(1);
        $data = $user->toArray();
        $data['following'] = [2, 3];
        $data['followers'] = [2];
        $data['items'] = [];

        $this->json('GET', '/api/v1/profile', $user->toArray(), [
            'Authorization' => $user->api_key
        ])
            ->seeStatusCode(JsonResponse::HTTP_OK)
            ->seeJsonEquals($data);
    }

    /**
     * A basic test create.
     *
     * @return void
     */
    public function testProfileBearerAuthorizationSuccess()
    {
        $user = App\User::find(1);
        $data = $user->toArray();
        $data['following'] = [2, 3];
        $data['followers'] = [2];
        $data['items'] = [];

        $this->json('GET', '/api/v1/profile', $user->toArray(), [
            'Authorization' => 'Bearer ' . $user->api_key
        ])
            ->seeStatusCode(JsonResponse::HTTP_OK)
            ->seeJsonEquals($data);
    }

    /**
     * A basic test create.
     *
     * @return void
     */
    public function testProfileAuthorizationFail()
    {
        $this->json('GET', '/api/v1/profile', [], [
            'Authorization' => 'Bearer test'
        ])
            ->seeStatusCode(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * A basic test create.
     *
     * @return void
     */
    public function testUpdateProfileAuthorizationSuccess()
    {
        $user = factory(App\User::class)->make();
        $user->save();

        $this->json('PUT', '/api/v1/profile', $user->toArray(), [
            'Authorization' => $user->api_key
        ])
            ->seeStatusCode(JsonResponse::HTTP_OK)
            ->seeJson($user->toArray());
    }

    /**
     * A basic test create.
     *
     * @return void
     */
    public function testUpdateProfileAuthorizationFail()
    {
        $user = factory(App\User::class)->make();
        $user->save();

        $this->json('PUT', '/api/v1/profile', $user->toArray(), [
            'Authorization' => 'Bearer test'
        ])
            ->seeStatusCode(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * A basic test create.
     *
     * @return void
     */
    public function testAttachUserToVisibleSuccess()
    {
        $user = factory(App\User::class)->make();
        $user->save();

        $followerId = 1;
        $this->json('POST', '/api/v1/profile/followers/' . $followerId, [], [
            'Authorization' => $user->api_key
        ])
            ->seeStatusCode(JsonResponse::HTTP_OK)
            ->seeJson([]);

        $data = $user->toArray();
        $data['followers'] = [$followerId];
        $data['following'] = [];
        $data['items'] = [];
        $this->json('GET', '/api/v1/profile', [], [
            'Authorization' => $user->api_key
        ])
            ->seeStatusCode(JsonResponse::HTTP_OK)
            ->seeJsonEquals($data);
    }

    /**
     * A basic test create.
     *
     * @return void
     */
    public function testDetachUserToVisibleSuccess()
    {
        $user = App\User::find(2);
        $data = $user->toArray();
        $data['followers'] = [1, 3];
        $data['following'] = [1, 4];
        $data['items'] = [];
        $this->json('GET', '/api/v1/profile', [], [
            'Authorization' => 'Bearer ' . $user->api_key
        ])
            ->seeStatusCode(JsonResponse::HTTP_OK)
            ->seeJsonEquals($data);

        $this->json('DELETE', '/api/v1/profile/followers/1', $user->toArray(), [
            'Authorization' => $user->api_key
        ])
            ->seeStatusCode(JsonResponse::HTTP_OK)
            ->seeJson([]);

        $data = $user->toArray();
        $data['followers'] = [3];
        $data['following'] = [1, 4];
        $data['items'] = [];
        $this->json('GET', '/api/v1/profile', [], [
            'Authorization' => 'Bearer ' . $user->api_key
        ])
            ->seeStatusCode(JsonResponse::HTTP_OK)
            ->seeJsonEquals($data);
    }

    /**
     * A basic test create.
     *
     * @return void
     */
    public function testAttachExistsUserToVisibleSuccess()
    {
        $user = App\User::find(2);
        $data = $user->toArray();
        $data['followers'] = [1, 3];
        $data['following'] = [1, 4];
        $data['items'] = [];
        $this->json('GET', '/api/v1/profile', [], [
            'Authorization' => 'Bearer ' . $user->api_key
        ])
            ->seeStatusCode(JsonResponse::HTTP_OK)
            ->seeJsonEquals($data);

        $this->json('POST', '/api/v1/profile/followers/1', [], [
            'Authorization' => $user->api_key
        ])
            ->seeStatusCode(JsonResponse::HTTP_OK)
            ->seeJson([]);

        $data = $user->toArray();
        $data['followers'] = [1, 3];
        $data['following'] = [1, 4];
        $data['items'] = [];
        $this->json('GET', '/api/v1/profile', [], [
            'Authorization' => $user->api_key
        ])
            ->seeStatusCode(JsonResponse::HTTP_OK)
            ->seeJsonEquals($data);
    }

    /**
     * A basic test create.
     *
     * @return void
     */
    public function testBuyItemSuccess()
    {
        $user = App\User::find(2);
        $this->assertEquals('100.0', $user->amount);

        $this->json('POST', '/api/v1/profile/items/1', [], [
            'Authorization' => $user->api_key
        ])
            ->seeStatusCode(JsonResponse::HTTP_OK)
            ->seeJson([]);

        $user = App\User::find(2);
        $this->assertEquals('90.0', $user->amount);

        $data = $user->toArray();
        $data['followers'] = [1, 3];
        $data['following'] = [1, 4];
        $data['items'] = [App\Item::find(1)->toArray()];
        $this->json('GET', '/api/v1/profile', [], [
            'Authorization' => $user->api_key
        ])
            ->seeStatusCode(JsonResponse::HTTP_OK)
            ->seeJsonEquals($data);
    }

    /**
     * A basic test create.
     *
     * @return void
     */
    public function testBuyItemFailed()
    {
        $userId = 3;
        $user = App\User::find($userId);
        $this->assertEquals('50.0', $user->amount);

        $this->json('POST', '/api/v1/profile/items/3', [], [
            'Authorization' => $user->api_key
        ])
            ->seeStatusCode(JsonResponse::HTTP_BAD_REQUEST)
            ->seeJson([
                'amount' => 'Not enough.'
            ]);

        $user = App\User::find($userId);
        $this->assertEquals('50.0', $user->amount);
    }

    /**
     * A basic test create.
     *
     * @return void
     */
    public function testDropItemSuccess()
    {
        $userId = 3;
        $user = App\User::find($userId);
        $this->assertEquals('50.0', $user->amount);

        $this->json('POST', '/api/v1/profile/items/1', [], [
            'Authorization' => $user->api_key
        ])
            ->seeStatusCode(JsonResponse::HTTP_OK);

        $data = $user->toArray();
        $data['followers'] = [1];
        $data['following'] = [2];
        $data['items'] = [App\Item::find(1)->toArray()];
        $this->json('GET', '/api/v1/profile', [], [
            'Authorization' => $user->api_key
        ])
            ->seeStatusCode(JsonResponse::HTTP_OK)
            ->seeJsonEquals($data);

        $user = App\User::find($userId);
        $this->assertEquals('40.0', $user->amount);
         $this->json('DELETE', '/api/v1/profile/items/1', [], [
            'Authorization' => $user->api_key
        ])
            ->seeStatusCode(JsonResponse::HTTP_OK);

        $user = App\User::find($userId);
        $this->assertEquals('40.0', $user->amount);

        $data = $user->toArray();
        $data['followers'] = [1];
        $data['following'] = [2];
        $data['items'] = [];
        $this->json('GET', '/api/v1/profile', [], [
            'Authorization' => $user->api_key
        ])
            ->seeStatusCode(JsonResponse::HTTP_OK)
            ->seeJsonEquals($data);
    }
}
