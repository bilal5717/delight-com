<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->json('date_range')->nullable(); // Add a JSON column for date ranges
            $table->json('time_range')->nullable(); // Add a JSON column for time ranges
            $table->integer('buffer_time')->nullable(); // Add an integer column for buffer time
            $table->text('cancellation_reason')->nullable(); // Add a text column for cancellation reason
            $table->integer('slot_details')->nullable(); // Add an integer column for slot details
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('date_range');
            $table->dropColumn('time_range');
            $table->dropColumn('buffer_time');
            $table->dropColumn('cancellation_reason');
            $table->dropColumn('slot_details');
        });
    }
}
