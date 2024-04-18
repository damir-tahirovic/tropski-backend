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
        Schema::create('item_type_trans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('item_type_id');
            $table->unsignedBigInteger('lang_id');
            $table->foreign('item_type_id')->references('id')->on('item_types');
            $table->foreign('lang_id')->references('id')->on('languages');
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
        Schema::dropIfExists('item_type_trans');
    }
};
