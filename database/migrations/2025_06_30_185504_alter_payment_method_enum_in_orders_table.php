<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPaymentMethodEnumInOrdersTable extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_method 
            ENUM('credit_card', 'stripe', 'paypal', 'bank_transfer', 'cash_on_delivery', 'offlinepayment')");
    }

    public function down()
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_method 
            ENUM('credit_card', 'stripe', 'paypal', 'bank_transfer', 'cash_on_delivery')");
    }
}
