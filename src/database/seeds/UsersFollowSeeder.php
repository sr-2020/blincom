<?php

use Illuminate\Database\Seeder;

class UsersFollowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = App\User::find(1);
        $user->following()->attach([2, 3]);

        $user = App\User::find(2);
        $user->following()->attach([1, 4]);

        $user = App\User::find(3);
        $user->following()->attach([2]);
    }
}
