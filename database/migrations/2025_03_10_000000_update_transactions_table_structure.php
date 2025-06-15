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
        Schema::table('transactions', function (Blueprint $table) {
            // Drop existing columns that are no longer needed
            $table->dropColumn(['description', 'sumber', 'status', 'keterangan', 'nomor_tagihan', 'nama_customer', 'nomor_whatsapp']);
            
            // Add new columns
            $table->string('nama_toko')->after('user_id');
            $table->text('alamat')->after('nama_toko');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Restore dropped columns
            $table->string('description')->nullable();
            $table->string('sumber')->nullable();
            $table->string('status')->nullable();
            $table->string('keterangan')->nullable();
            $table->text('nomor_tagihan')->nullable();
            $table->string('nama_customer')->nullable();
            $table->string('nomor_whatsapp')->nullable();
            
            // Drop new columns
            $table->dropColumn(['nama_toko', 'alamat']);
        });
    }
}; 