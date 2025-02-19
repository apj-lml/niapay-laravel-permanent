<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('deductions')->insert([
            [
                'description' => 'HDMF_PREMIUM',
                'account_title' => 'DUE TO PAG-IBIG - PREMIUM',
                'deduction_type' => 'HDMF_PREMIUM', 
                'deduction_group'=>'HDMF', 
                'deduction_for'=>'both', 
                'sort_position' => 1, 
                'uacs_code_lfps' => '414(2-02-01-030)', 
                'uacs_code_cob' => '414(2-02-01-030)',
                'status' => 'ACTIVE'
            ],
            [
                'description' => 'MP2', 
                'account_title' => 'DUE TO PAG-IBIG - MP2', 
                'deduction_type' => 'HDMF_MP2', 
                'deduction_group'=>'HDMF', 
                'deduction_for'=>'both', 
                'sort_position' => 2, 
                'uacs_code_lfps' => '414-5(2-02-01-030)', 
                'uacs_code_cob' => '414-5(2-02-01-030)',
                'status' => 'ACTIVE'
            ],
            [
                'description' => 'MPL', 
                'account_title' => 'DUE TO PAG-IBIG - MPL', 
                'deduction_type' => 'HDMF_MPL', 
                'deduction_group'=>'HDMF', 
                'deduction_for'=>'both', 
                'sort_position' => 3, 
                'uacs_code_lfps' => '414-2(2-02-01-030)', 
                'uacs_code_cob' => '414(2-02-01-030)',
                'status' => 'ACTIVE'
            ],
            [
                'description' => 'CAL', 
                'account_title' => 'DUE TO PAG-IBIG - CAL',
                'deduction_type' => 'HDMF_CAL', 
                'deduction_group'=>'HDMF', 
                'deduction_for'=>'both', 
                'sort_position' => 4, 
                'uacs_code_lfps' => '414-4(2-02-01-030)', 
                'uacs_code_cob' => '414-4(2-02-01-030)',
                'status' => 'ACTIVE'
            ],
            [
                'description' => 'PHIC_PREMIUM', 
                'account_title' => 'DUE TO PHILHEALTH', 
                'deduction_type' => 'PHIC_PREMIUM', 
                'deduction_group'=>'PHIC', 
                'deduction_for'=>'both', 
                'sort_position' => 1, 
                'uacs_code_lfps' => '415(2-02-01-040)', 
                'uacs_code_cob' => '415(2-02-01-040)',
                'status' => 'ACTIVE'
            ],
            [
                'description' => 'COOP LOAN', 
                'account_title' => 'OTHER PAYABLES - COOP LOAN', 
                'deduction_type' => 'COOP_LOAN', 
                'deduction_group'=>'COOP', 
                'deduction_for'=>'both', 
                'sort_position' => 1, 
                'uacs_code_lfps' => '439(2-99-99-990)', 
                'uacs_code_cob' => '439(2-99-99-990)',
                'status' => 'ACTIVE'
            ],
            [
                'description' => 'DISALLOWANCE', 
                'account_title' => 'TRUST LIABILITIES-DISALLOWANCE/CHARGES', 
                'deduction_type' => 'DISALLOWANCE', 
                'deduction_group'=>'DISALLOWANCE', 
                'deduction_for'=>'both', 
                'sort_position' => 1, 
                'uacs_code_lfps' => '428(2-04-01-080)', 
                'uacs_code_cob' => '428(2-04-01-080)',
                'status' => 'ACTIVE'
            ],
            [
                'description' => 'WHTAX', 
                'account_title' => 'WHTAX', 
                'deduction_type' => 'WHTAX', 
                'deduction_group'=>'TAX', 
                'deduction_for'=>'both', 
                'sort_position' => 1, 
                'uacs_code_lfps' => '412(2-02-01-010)', 
                'uacs_code_cob' => '412(2-02-01-010)',
                'status' => 'ACTIVE'
            ],
            [
                'description' => 'GSIS PREMIUM', 
                'account_title' => 'DUE TO GSIS - PREMIUM', 
                'deduction_type' => 'GSIS_PREMIUM', 
                'deduction_group'=>'GSIS', 
                'deduction_for'=>'both', 
                'sort_position' => 1, 
                'uacs_code_lfps' => '413(2-02-01-020)', 
                'uacs_code_cob' => '413(2-02-01-020)',
                'status' => 'ACTIVE'
            ],
            [
                'description' => 'SALARY LOAN', 
                'account_title' => 'DUE TO GSIS - SALARY LOAN', 
                'deduction_type' => 'GSIS_SALARY_LOAN', 
                'deduction_group'=>'GSIS', 
                'deduction_for'=>'both', 
                'sort_position' => 2, 
                'uacs_code_lfps' => '413-3(2-02-01-020)', 
                'uacs_code_cob' => '413-3(2-02-01-020)',
                'status' => 'ACTIVE'
            ],
            [
                'description' => 'GSIS MPL', 
                'account_title' => 'DUE TO GSIS - MPL', 
                'deduction_type' => 'GSIS_MPL', 
                'deduction_group'=>'GSIS', 
                'deduction_for'=>'both', 
                'sort_position' => 3, 
                'uacs_code_lfps' => '413(2-02-01-020)', 
                'uacs_code_cob' => '413(2-02-01-020)',
                'status' => 'ACTIVE'
            ],
            [
                'description' => 'CPL', 
                'account_title' => 'DUE TO GSIS - CPL', 
                'deduction_type' => 'GSIS_CPL', 
                'deduction_group'=>'GSIS', 
                'deduction_for'=>'both', 
                'sort_position' => 4, 
                'uacs_code_lfps' => '413(2-02-01-020)', 
                'uacs_code_cob' => '413(2-02-01-020)',
                'status' => 'ACTIVE'
            ],
            [
                'description' => 'PL REG', 
                'account_title' => 'DUE TO GSIS - PL REG', 
                'deduction_type' => 'PL_REG', 
                'deduction_group'=>'GSIS', 
                'deduction_for'=>'both', 
                'sort_position' => 5, 
                'uacs_code_lfps' => '413(2-02-01-020)', 
                'uacs_code_cob' => '413(2-02-01-020)',
                'status' => 'ACTIVE'
            ],
            [
                'description' => 'MPL LITE', 
                'account_title' => 'DUE TO GSIS - MPL LITE', 
                'deduction_type' => 'MPL_LITE', 
                'deduction_group'=>'GSIS', 
                'deduction_for'=>'both', 
                'sort_position' => 5, 
                'uacs_code_lfps' => '413(2-02-01-020)', 
                'uacs_code_cob' => '413(2-02-01-020)',
                'status' => 'ACTIVE'
            ]
        ]);

    }
}
