<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceSettingsInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_settings_info', function (Blueprint $table) {
            $table->id();
            $table->string('setting_key')->unique(); // Each setting should have a unique key
            $table->text('setting_value')->nullable(); // Stores the setting value, can be null
            $table->boolean('active')->default(1); // Status to enable/disable the setting
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
        Schema::dropIfExists('service_settings_info');
    }
}