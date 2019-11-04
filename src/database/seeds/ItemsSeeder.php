<?php

use Illuminate\Database\Seeder;

class ItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prices = [10, 50, 100];
        $names = ['Axe', 'Helm', 'Budda'];

        for ($i = 0; $i < 3; $i++) {
            $user = factory(App\Item::class)->make();
            $user->price = $prices[$i];
            $user->name = $names[$i];
            $user->save();
        }
    }
}
