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
        Schema::create('uniform_allowances', function (Blueprint $table) {
            $table->id();
            $table->string('mc')->nullable();
            $table->string('year');
            $table->string('name');
            $table->string('position_title');
            $table->string('sgjg');
            $table->decimal('uniform_allowance', 12, 2);
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('uniform_allowances');
    }
};
