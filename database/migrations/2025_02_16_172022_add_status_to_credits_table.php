<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToCreditsTable extends Migration
{
    public function up()
    {
        Schema::table('credits', function (Blueprint $table) {
            $table->string('status')->default('Belum Terbayar')->after('receipt'); // Menambahkan kolom status
        });
    }

    public function down()
    {
        Schema::table('credits', function (Blueprint $table) {
            $table->dropColumn('status'); // Menghapus kolom status jika rollback
        });
    }
}