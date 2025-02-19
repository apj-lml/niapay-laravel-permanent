<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AllowanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('allowances')->insert([
            ['description' => 'CHILDREN', 'allowance_type' => 'CHILD', 'allowance_group'=>'CHILD', 'allowance_for'=>'monthly', 'sort_position' => 1, 'sort_position' => 1, 'uacs_code_lfps' => '414(2-02-01-030)', 'uacs_code_cob' => '414(2-02-01-030)'],
            ['description' => 'MEAL', 'allowance_type' => 'MEAL', 'allowance_group'=>'MEAL', 'allowance_for'=>'monthly', 'sort_position' => 1, 'uacs_code_lfps' => '414(2-02-01-030)', 'uacs_code_cob' => '414(2-02-01-030)'],
            ['description' => 'MEDICAL', 'allowance_type' => 'MEDICAL', 'allowance_group'=>'MEDICAL', 'allowance_for'=>'monthly', 'sort_position' => 1, 'uacs_code_lfps' => '414(2-02-01-030)', 'uacs_code_cob' => '414(2-02-01-030)'],
            ['description' => 'PERA', 'allowance_type' => 'PERA', 'allowance_group'=>'PERA', 'allowance_for'=>'monthly', 'sort_position' => 1, 'uacs_code_lfps' => '414(2-02-01-030)', 'uacs_code_cob' => '414(2-02-01-030)']
        ]
        );
    }
}
