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
        Schema::create('new_payroll_index_all_deds', function (Blueprint $table) {
            $table->id();
            $table->string('npiad_type');
            $table->string('npiad_amount');
            $table->string('npiad_group');
            $table->string('npiad_description');
            $table->string('npiad_for');
            $table->string('npiad_sort_position');
            // $table->date('new_payroll_index_period_from');
            // $table->date('new_payroll_index_period_to');
            $table->unsignedBigInteger('new_payroll_index_id');

            $table->foreign('new_payroll_index_id')->references('id')->on('new_payroll_index');
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
        Schema::dropIfExists('new_payroll_index_all_deds');
    }
};
