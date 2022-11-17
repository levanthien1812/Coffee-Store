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
        Schema::create('detail_receipts', function (Blueprint $table) {
            $table->id();
            $table->integer('Quantity');
            $table->enum('Size', ['Small', 'Medium', 'Large']);
            $table->integer('Price');
            $table->unsignedBigInteger('ReceiptID');
            $table->foreign('ReceiptID')->references('id')->on('receipts')->onDelete('cascade');
            $table->unsignedBigInteger('ItemID');
            $table->foreign('ItemID')->references('id')->on('items')->onDelete('cascade');
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
        Schema::dropIfExists('detail_receipts');
    }
};
