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
        Schema::create('allowances', function (Blueprint $table) {
                $table->id();
                $table->string('description');
                $table->string('allowance_type');
                $table->string('allowance_group')->nullable();
                $table->string('allowance_for');
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
        Schema::dropIfExists('allowances');
    }
};
