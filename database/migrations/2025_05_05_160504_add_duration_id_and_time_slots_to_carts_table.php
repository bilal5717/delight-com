<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDurationIdAndTimeSlotsToCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->foreignId('duration_id')
                ->nullable()
                ->constrained('post_durations')
                ->onDelete('set null');

            // Add nullable JSON column for time slots
            $table->json('time_slots')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['duration_id']);
            
            // Then drop the columns
            $table->dropColumn(['duration_id', 'time_slots']);
        });
    }
}
