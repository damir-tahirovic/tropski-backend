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
        Schema::create('extra_trans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('extra_id');
            $table->unsignedBigInteger('lang_id');
            $table->foreign('extra_id')->references('id')->on('extras')->onDelete('cascade');
            $table->foreign('lang_id')->references('id')->on('languages')->onDelete('cascade');
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
        Schema::dropIfExists('extra_trans');
    }
};
