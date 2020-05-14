<?php

namespace SamiXSous\Printful\Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AtrributeOptionsTableSeeder extends Seeder
{
    public function run()
    {

        $id = DB::table('attribute_options')->insertGetId([
                'admin_name'         => '2XL',
                'sort_order'   => 5,
                'attribute_id'      =>  23,
        ]);



        DB::table('attribute_option_translations')->insert([
            [
                'locale'         => 'en',
                'label'   => 'XXL',
                'attribute_option_id'      =>  $id,
            ]
        ]);
    }
}