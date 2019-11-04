<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
    use DatabaseMigrations;
    use TestCrudTrait;

    const ROUTE = '/api/v1/users';
    const CONTROLLER = UserController::class;

    /**
     * A basic test index.
     *
     * @return void
     */
    public function testIndex()
    {
        $model = $this->makeFactory();
        $model->save();

        $this->json('GET', static::ROUTE)
            ->seeStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * A basic test create.
     *
     * @return void
     */
    public function testCreate()
    {
        $model = $this->makeFactory();
        $model->makeVisible('email');

        $this->json('POST', static::ROUTE, $model->toArray(), [
            'Authorization' => 'Token ' . self::getToken()
        ])
            ->seeStatusCode(JsonResponse::HTTP_CREATED)
            ->seeJson($model->makeHidden(['email'])->toArray());
    }

    /**
     * A basic test read.
     *
     * @return void
     */
    public function testRead()
    {
        $model = $this->makeFactory();
        $model->save();

        $this->json('GET', static::ROUTE . '/' . $model->id)
            ->seeStatusCode(JsonResponse::HTTP_OK)
            ->seeJson($model->toArray());
    }

    /**
     * A basic test update.
     *
     * @return void
     */
    public function testUpdate()
    {
        $model = $this->makeFactory();
        $model->save();

        $newModel = $this->makeFactory();

        $this->json('PUT', static::ROUTE . '/' . $model->id, $newModel->toArray(), [
            'Authorization' => 'Token ' . self::getToken()
        ])
            ->seeStatusCode(JsonResponse::HTTP_OK)
            ->seeJson($newModel->toArray());
    }
}
