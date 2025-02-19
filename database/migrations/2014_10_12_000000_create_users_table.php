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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(1);
            $table->string('employee_id')->unique();
            // $table->string('name');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('name_extn')->nullable();
            // $table->string('email')->unique();
            // $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            // $table->string('section');
            $table->unsignedBigInteger('agency_unit_id');
            $table->string('position');
            $table->string('employment_status');
            $table->string('sg_jg');
            $table->string('step')->default(1);
            $table->string('daily_rate')->nullable();
            $table->string('monthly_rate')->nullable();
            $table->unsignedBigInteger('fund_id');
            // $table->string('fund');
            $table->string('tin');
            $table->string('phic_no');
            $table->string('gsis');
            $table->string('hdmf');
            $table->string('sss')->nullable();
            $table->boolean('include_to_payroll')->default(1);
            $table->string('role')->default(0);
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('agency_unit_id')->references('id')->on('agency_units');
            $table->foreign('fund_id')->references('id')->on('funds');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
