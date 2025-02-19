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
        Schema::create('agency_units', function (Blueprint $table) {
            $table->id();
            $table->string('unit_code')->unique();
            $table->string('unit_description');
            $table->unsignedBigInteger('agency_section_id')->nullable();

            $table->timestamps();
            
            $table->foreign('agency_section_id')->references('id')->on('agency_sections');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agency_units');
    }
};
