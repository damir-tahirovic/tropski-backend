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
        Schema::create('order_places', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->boolean('reported')->default(false);
            $table->unsignedBigInteger('hotel_id');
            $table->unsignedBigInteger('main_cat_id');
            $table->foreign('hotel_id')->references('id')->on('hotels');
            $table->foreign('main_cat_id')->references('id')->on('main_categories');
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
        Schema::dropIfExists('order_places');
    }
};
