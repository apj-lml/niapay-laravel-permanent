<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class AgencyUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('agency_units')->insert([
            ['id'=>1, 'unit_code'=>'ADU', 'unit_description'=>'ADMINISTRATIVE UNIT', 'agency_section_id' => 1],
            ['id'=>2, 'unit_code'=>'FIN', 'unit_description'=>'FINANCE UNIT', 'agency_section_id' => 1],
            ['id'=>3, 'unit_code'=>'CAS', 'unit_description'=>'CASHIERING UNIT', 'agency_section_id' => 1],
            ['id'=>4, 'unit_code'=>'PRO', 'unit_description'=>'PROPERTY UNIT', 'agency_section_id' => 1],
            ['id'=>5, 'unit_code'=>'AFS-N/A', 'unit_description'=>'N/A', 'agency_section_id' => 1],
            ['id'=>6, 'unit_code'=>'PLA', 'unit_description'=>'PLANNING UNIT', 'agency_section_id' => 2],
            ['id'=>7, 'unit_code'=>'DES', 'unit_description'=>'DESIGN UNIT', 'agency_section_id' => 2],
            ['id'=>8, 'unit_code'=>'CON', 'unit_description'=>'CONSTRUCTION UNIT', 'agency_section_id' => 2],
            ['id'=>9, 'unit_code'=>'IDU', 'unit_description'=>'INSTITUTIONAL DEVELOPMENT UNIT', 'agency_section_id' => 2],
            ['id'=>10, 'unit_code'=>'EQU', 'unit_description'=>'EQUIPMENT UNIT', 'agency_section_id' => 2],
            ['id'=>11, 'unit_code'=>'ENG-N/A', 'unit_description'=>'N/A', 'agency_section_id' => 2],
            ['id'=>12, 'unit_code'=>'ASRIS', 'unit_description'=>'AGNO-SINOCALAN RIS', 'agency_section_id' => 10],
            ['id'=>13, 'unit_code'=>'SFDRIS', 'unit_description'=>'SAN FABIAN-DUMOLOC RIS', 'agency_section_id' => 9],
            ['id'=>14, 'unit_code'=>'LARIS', 'unit_description'=>'LOWER AGNO RIS', 'agency_section_id' => 8],
            ['id'=>15, 'unit_code'=>'ADRIS', 'unit_description'=>'AMBAYOAN-DIPALO RIS', 'agency_section_id' => 11],
            ['id'=>17, 'unit_code'=>'SUR', 'unit_description'=>'SURVEY TEAM', 'agency_section_id' => 2],
            ['id'=>18, 'unit_code'=>'CARP-N/A', 'unit_description'=>'N/A', 'agency_section_id' => 14],
            ['id'=>19, 'unit_code'=>'OIM-N/A', 'unit_description'=>'N/A', 'agency_section_id' => 12],
            ['id'=>20, 'unit_code'=>'SRIP', 'unit_description'=>'SMALL RESERVOIR IRRIGATION PROJECT', 'agency_section_id' => 7],
        ]);
    }
}
