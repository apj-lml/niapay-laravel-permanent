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
        Schema::create('yearend_bonuses', function (Blueprint $table) {
            $table->id();
            $table->string('mc')->nullable();
            $table->string('year');
            $table->string('emp_id');
            $table->string('name');
            $table->string('position_title');
            $table->string('daily_rate');
            $table->string('monthly_rate');
            $table->string('year_end_bonus');
            $table->string('cash_gift')->nullable();
            $table->string('total_year_end_bonus');
            $table->string('casab_loan')->nullable();
            $table->string('net_amount');
            $table->unsignedBigInteger('user_id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('yearend_bonuses');
    }
};
