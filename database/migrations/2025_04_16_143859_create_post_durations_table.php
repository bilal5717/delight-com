<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostDurationsTable extends Migration
{
    /**
     * Run the migrations.
     * $table->foreignId('location_id')->nullable()->constrained()->onDelete('set null');
     * @return void
     */
    public function up()
    {
        Schema::create('post_durations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->foreignId('location_id')->nullable();
            $table->string('duration_title');
            $table->string('duration_unit');
            $table->integer('duration_value');
            $table->integer('max_capacity');
            $table->integer('available_units');
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('post_durations');
    }
}
