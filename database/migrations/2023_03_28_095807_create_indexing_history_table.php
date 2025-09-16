<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndexingHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indexing_history', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->unsignedInteger('reference_id');
            $table->enum('type', ['posts', 'categories', 'pages','cities']);
            $table->unsignedInteger('indexing_count');
            $table->index(['url', 'type', 'reference_id']);
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
        Schema::dropIfExists('indexing_history');
    }
}
