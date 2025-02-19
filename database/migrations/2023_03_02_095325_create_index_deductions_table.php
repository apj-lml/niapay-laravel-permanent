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
        Schema::create('index_deductions', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->string('deduction_type');
            $table->string('deduction_group')->nullable();
            $table->string('amount');
        // $table->string('payroll_period_from');
        // $table->string('payroll_period_to');
            $table->integer('sort_position');
            $table->unsignedBigInteger('payroll_index_id');
            $table->timestamps();

            $table->foreign('payroll_index_id')->references('id')->on('payroll_indices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('index_deductions');
    }
};
