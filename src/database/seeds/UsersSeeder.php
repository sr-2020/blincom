<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(App\User::class)->make();
        $user->admin = true;
        $user->amount = 1000;
        $user->name = 'Мистер X';
        $user->email = 'admin@evarun.ru';
        $user->password = 'secret';
        $user->api_key = 'TkRVem4yTERSQTNQRHFxcmo4SUozNWZp';
        $user->save();

        $user = factory(App\User::class)->make();
        $user->admin = false;
        $user->amount = 100;
        $user->name = 'Мистер A';
        $user->email = 'a@evarun.ru';
        $user->password = 'secret';
        $user->api_key = 'M3GVem4ySWESQTNQRHFxcmo4SUozNWKA';
        $user->save();

        $user = factory(App\User::class)->make();
        $user->admin = false;
        $user->amount = 50;
        $user->name = 'Мистер B';
        $user->email = 'b@evarun.ru';
        $user->password = 'secret';
        $user->api_key = 'M3GVem4ySWESQTNQRHFxcmo4SUozNWKB';
        $user->save();

        $user = factory(App\User::class)->make();
        $user->admin = false;
        $user->amount = 0;
        $user->name = 'Мистер C';
        $user->email = 'c@evarun.ru';
        $user->password = 'secret';
        $user->api_key = 'M3GVem4ySWESQTNQRHFxcmo4SUozNWKC';
        $user->save();
    }
}
