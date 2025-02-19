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
        Schema::create('payroll_indices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('office_section');
            $table->string('imo');
            $table->string('position_title');
            $table->string('sg_jg');
            $table->string('daily_monthly_rate');
            $table->string('employment_status');
            $table->string('period_covered');
            $table->date('period_covered_from');
            $table->date('period_covered_to');
            $table->string('tin');
            $table->string('phic_no');
            // $table->string('gsis');
            $table->string('hdmf');
            $table->decimal('days_rendered', 9, 3);
            $table->decimal('basic_pay', 9, 2);
            // $table->decimal('pera', 9, 2)->nullable();
            // $table->decimal('medical', 9, 2)->nullable();
            // $table->decimal('meal', 9, 2)->nullable();
            // $table->decimal('children', 9, 2)->nullable();
            // $table->decimal('total_allowances', 9, 2)->nullable();
            // $table->decimal('gross_amount', 9, 2);
            // $table->decimal('wtax', 9, 2)->nullable();
            // $table->decimal('gsis_premium', 9, 2)->nullable();
            // $table->decimal('gsis_consoloan', 9, 2)->nullable();
            // $table->decimal('gsis_salary_loan', 9, 2)->nullable();
            // $table->decimal('gsis_cash_adv', 9, 2)->nullable();
            // $table->decimal('gsis_emergency', 9, 2)->nullable();
            // $table->decimal('gsis_gfal', 9, 2)->nullable();
            // $table->decimal('gsis_cpl', 9, 2)->nullable();
            // $table->decimal('hdmf_premium', 9, 2)->nullable();
            // $table->decimal('hdmf_mp2', 9, 2)->nullable();
            // $table->decimal('hdmf_mploan', 9, 2)->nullable();
            // $table->decimal('hdmf_cal', 9, 2)->nullable();
            // $table->decimal('phic', 9, 2)->nullable();
            // $table->decimal('COOP', 9, 2)->nullable();
            // $table->decimal('cna', 9, 2)->nullable();
            // $table->decimal('total_deductions', 9, 2)->nullable();
            // $table->decimal('incentive_benefits', 9, 2)->nullable();
            $table->decimal('total_deductions', 9, 2)->nullable();
            $table->decimal('net_pay', 9, 2)->nullable();
            $table->string('funding_charges');
            $table->string('dv_payroll_no')->nullable();
            $table->string('remarks')->nullable();
            $table->unsignedBigInteger('user_id');

            $table->foreign('user_id')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payroll_indices');
    }
};
