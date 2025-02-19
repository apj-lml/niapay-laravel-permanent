<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgencySectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('agency_sections')->insert([
            ['id'=>1, 'section_code'=>'AFS', 'section_description'=>'ADMINISTRATIVE & FINANCE', 'office' => 'PIMO'],
            ['id'=>2, 'section_code'=>'ES', 'section_description'=>'ENGINEERING', 'office' => 'PIMO'],
            ['id'=>12, 'section_code'=>'OMS', 'section_description'=>'OPERATION & MAINTENANCE', 'office' => 'PIMO'],
            ['id'=>14, 'section_code'=>'CARP', 'section_description'=>'CARP-IC', 'office' => 'PIMO'],
            ['id'=>15, 'section_code'=>'OIM', 'section_description'=>'OFFICE OF THE IRRIGATION MANAGER', 'office' => 'PIMO'],
            ['id'=>7, 'section_code'=>'SRIP', 'section_description'=>'SRIP', 'office' => 'SRIP'],
            ['id'=>8, 'section_code'=>'LARIS', 'section_description'=>'LOWER AGNO RIS', 'office' => 'ADRIS-LARIS'],
            ['id'=>9, 'section_code'=>'SFDRIS', 'section_description'=>'SAN FABIAN-DUMOLOC RIS', 'office' => 'ASRIS-SFDRIS'],
            ['id'=>10, 'section_code'=>'ASRIS', 'section_description'=>'AGNO-SINOCALAN RIS', 'office' => 'ASRIS-SFDRIS'],
            ['id'=>11, 'section_code'=>'ADRIS', 'section_description'=>'AMBAYOAN-DIPALO RIS', 'office' => 'ADRIS-LARIS']
        ]);
    }
}
