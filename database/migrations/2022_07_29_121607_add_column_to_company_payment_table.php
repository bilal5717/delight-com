<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToCompanyPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_payment', function (Blueprint $table) {
			$table->char('currency_code', 3);
            // $table->foreign('currency_code')->references('code')->on('currencies');
            $table->boolean('default_payment')->nullable()->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_payment', function (Blueprint $table) {
            $table->dropColumn('default_payment');
            $table->dropColumn('currency_code');
        });
    }
}
