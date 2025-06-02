<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detailinputs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('input_id');
            $table->integer('no');
            $table->integer('banyaknya');
            $table->string('nama_barang');
            $table->integer('harga');
            $table->integer('jumlah');
            $table->timestamps();
    
            $table->foreign('input_id')->references('id')->on('inputs')->onDelete('cascade');
        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detailinputs');
    }
};
