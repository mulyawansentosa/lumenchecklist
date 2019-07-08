<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // TEMPLATE SEEDER
        factory(App\User::class, 2)->create();
        // TEMPLATE SEEDER
        factory(App\Template::class, 10)->create();
        // CHECKLIST SEEDER
        factory(App\Checklist::class, 10)->create();
        // ITEM SEEDER
        factory(App\Item::class, 10)->create();
    }
}
