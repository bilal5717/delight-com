<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            
            $table->enum('status', ['pending', 'processing', 'completed', 'confirmed', 'cancelled'])
                  ->default('pending');
                  
            $table->enum('payment_method', ['credit_card','stripe', 'paypal', 'bank_transfer', 'cash_on_delivery']);
            $table->string('payment_id')->nullable();
            
            // Changed to nullable() before constrained()
            $table->foreignId('shipping_address_id')
                  ->nullable()
                  ->onDelete('set null');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
