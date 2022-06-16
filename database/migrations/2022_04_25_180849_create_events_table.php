<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('description');
            $table->text('content');

            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();

            $table->text('location');

            $table->float('lat', 10, 6)->nullable();
            $table->float('lng', 10, 6)->nullable();
            $table->point('position')->nullable();


            $table->string('photo')->nullable();
            $table->string('link')->nullable();
            $table->unsignedBigInteger('organizer_id');
            $table->unsignedBigInteger('category_id');

            $table->unsignedBigInteger('popular_place_id')->nullable(); // if event located in popular place then fullfilled

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
        Schema::dropIfExists('events');
    }
};
