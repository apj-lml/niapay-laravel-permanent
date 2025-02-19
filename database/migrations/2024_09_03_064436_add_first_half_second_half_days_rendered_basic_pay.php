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
            $table->decimal('second_half', 9, 3)->after('period_covered');
            $table->decimal('first_half', 9, 3)->after('period_covered');
            $table->decimal('second_half_basic_pay', 9, 2)->after('basic_pay');
            $table->decimal('first_half_basic_pay', 9, 2)->after('basic_pay');
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
            $table->dropColumn('second_half', 9, 3);
            $table->dropColumn('first_half', 9, 3);
            $table->dropColumn('second_half_basic_pay', 9, 2);
            $table->dropColumn('first_half_basic_pay', 9, 2);
        });
    }
};
