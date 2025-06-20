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
        Schema::table('new_payroll_index', function (Blueprint $table) {
            $table->string('filename')->nullable()->after('funding_charges');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_payroll_index', function (Blueprint $table) {
            $table->dropColumn('filename');
        });
    }
};
