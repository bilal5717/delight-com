<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultAddOnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_default_addons', function (Blueprint $table) {
            $table->id();
            $table->enum('service_type', ['class', 'appointment', 'package','rent']);
            $table->string('title');
            $table->enum('status', ['active', 'inactive']);
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
        Schema::dropIfExists('post_default_addons');
    }
}
