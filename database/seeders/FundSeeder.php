<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('funds')->insert([
            ['fund_description' => '501 COB', 'fund_obligation_description' => 'BASIC PAY', 'fund_uacs_code' => '705 (5-01-01-020)'],
            ['fund_description' => '501 CARP [Capital Outlay]', 'fund_obligation_description' => 'BASIC PAY', 'fund_uacs_code' => '705 (2-02-01-020)'],
        ]);
    }
}
