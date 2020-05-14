<?php

namespace SamiXSous\Printful\Database\Seeders;

use Illuminate\Database\Seeder;
use SamiXSous\Printful\Database\Seeders\AtrributeOptionsTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AtrributeOptionsTableSeeder::class);
    }
}