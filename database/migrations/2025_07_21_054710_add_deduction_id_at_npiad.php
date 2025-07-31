<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_payroll_index_all_deds', function (Blueprint $table) {
            $table->string('npiad_deduction_id')->after('npiad_type');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_payroll_index_all_deds', function (Blueprint $table) {
            $table->dropColumn('npiad_deduction_id');

        });
    }
};
