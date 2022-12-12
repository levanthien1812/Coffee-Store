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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('Name');
            $table->integer('SPrice');
            $table->integer('MPrice');
            $table->integer('LPrice');
            $table->string('Image');
            $table->unsignedBigInteger('Type');
            $table->foreign('Type')->references('id')->on('categories')->onDelete('cascade');
            $table->timestamps();
            $table->string('Description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
};
