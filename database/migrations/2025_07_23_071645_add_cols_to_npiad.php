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
            $table->string('npiad_application_no')->nullable()->after('npiad_amount');
            $table->string('npiad_loan_granted')->nullable()->after('npiad_application_no');
            $table->string('npiad_start_term')->nullable()->after('npiad_loan_granted');
            $table->string('npiad_end_term')->nullable()->after('npiad_start_term');
            
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
            $table->dropColumn('npiad_application_no');
            $table->dropColumn('npiad_loan_granted');
            $table->dropColumn('npiad_start_term');
            $table->dropColumn('npiad_end_term');
            
        });
    }
};
