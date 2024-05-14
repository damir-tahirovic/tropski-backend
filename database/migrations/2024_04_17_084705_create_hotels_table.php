<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('his_id')->nullable();
            $table->string('banner_text')->nullable();
            $table->string('primary_color');
            $table->string('primary_color_light');
            $table->string('primary_color_dark');
            $table->string('secondary_color');
            $table->string('secondary_color_light');
            $table->string('secondary_color_dark');
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
        Schema::dropIfExists('hotels');
    }
};
