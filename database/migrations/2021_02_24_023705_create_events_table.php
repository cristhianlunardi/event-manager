<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->string('title');
            $table->date('startDate');
            $table->foreign('dependency')->references('id')->on('dependencies');
            $table->foreign('event_type')->references('id')->on('event_types');
            $table->json('eventTypeFields');
            $table->json('additionalFields');
            $table->json('agreements');
            $table->json('participants');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
