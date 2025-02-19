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
        Schema::create('deductions', function (Blueprint $table) {
                $table->id();
                $table->string('description');
                $table->string('deduction_type');
                $table->string('deduction_group')->nullable();
                $table->string('deduction_for');
                $table->string('uacs_code_lfps');
                $table->string('uacs_code_cob');
                $table->string('status')->default('ACTIVE');
                $table->integer('sort_position');
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
        Schema::dropIfExists('deductions');
    }
};
