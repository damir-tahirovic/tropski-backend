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
        Schema::create('extra_group_extra_pivots', function (Blueprint $table) {
            $table->id();
            $table->double('price');
            $table->integer('quantity');
            $table->string('unit');
            $table->unsignedBigInteger('extra_group_id');
            $table->unsignedBigInteger('extra_id');
            $table->foreign('extra_id')->references('id')->on('extras')->onDelete('cascade');
            $table->foreign('extra_group_id')->references('id')->on('extra_groups')->onDelete('cascade');
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
        Schema::dropIfExists('extra_group_extra_pivots');
    }
};
