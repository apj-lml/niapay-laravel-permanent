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
        Schema::table('deduction_user', function (Blueprint $table) {
            $table->decimal('loan_granted', 12, 2)->nullable()->after('amount');
            $table->date('start_term')->nullable()->after('loan_granted');
            $table->date('end_term')->nullable()->after('start_term');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deduction_user', function (Blueprint $table) {
            $table->dropColumn([
                'loan_granted',
                'monthly_amortization',
                'start_term',
                'end_term'
            ]);
        });
    }
};
