<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShowOnInvoiceToCompanyPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_payment', function (Blueprint $table) {
             $table->boolean('show_on_invoice')->default(false)->after('default_payment');
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
            $table->dropColumn('show_on_invoice');
        });
    }
}
