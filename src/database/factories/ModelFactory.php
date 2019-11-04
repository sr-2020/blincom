<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'admin' => false,
        'email' => $faker->email,
        'password' => $faker->password,
        'status' => $faker->safeColorName,
        'role' => 'runner',
        'amount' => $faker->randomFloat(1, 100, 1000),
        'options' => (object)['a' => true, 'b' => 1, 'c' => 'on']
    ];
});

$factory->define(App\Item::class, function (Faker\Generator $faker) {
    return [
        'activate' => false,
        'name' => $faker->name,
        'price' => $faker->numberBetween(10, 100),
        'options' => (object)['a' => true, 'b' => 1, 'c' => 'on']
    ];
});
