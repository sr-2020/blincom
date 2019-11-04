<?php

use App\Http\Controllers\ItemController;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Illuminate\Http\JsonResponse;

class ItemTest extends TestCase
{
    use DatabaseMigrations;

    const CONTROLLER = ItemController::class;

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
    public function testShopItemsAllAllowSuccess()
    {
        $user = App\User::find(1);

        $this->json('GET', '/api/v1/items', [], [
            'Authorization' => $user->api_key
        ])
            ->seeStatusCode(JsonResponse::HTTP_OK);

        $json = json_decode($this->response->content());
        $this->assertEquals(true, $json[0]->allow);
        $this->assertEquals(10.0, $json[0]->price);
        $this->assertEquals(true, $json[1]->allow);
        $this->assertEquals(100.0, $json[1]->price);
        $this->assertEquals(true, $json[2]->allow);
        $this->assertEquals(50.0, $json[2]->price);
    }

    /**
     * A basic test create.
     *
     * @return void
     */
    public function testShopItemsAnyAllowSuccess()
    {
        $user = App\User::find(3);

        $this->json('GET', '/api/v1/items', [], [
            'Authorization' => $user->api_key
        ])
            ->seeStatusCode(JsonResponse::HTTP_OK);

        $json = json_decode($this->response->content());
        $this->assertEquals(true, $json[0]->allow);
        $this->assertEquals(10.0, $json[0]->price);
        $this->assertEquals(false, $json[1]->allow);
        $this->assertEquals(100.0, $json[1]->price);
        $this->assertEquals(true, $json[2]->allow);
        $this->assertEquals(50.0, $json[2]->price);
    }
}
