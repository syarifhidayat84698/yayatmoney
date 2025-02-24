<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToDebtsTable extends Migration
{
    public function up()
    {
        Schema::table('debts', function (Blueprint $table) {
            $table->string('status')->default('Belum Terbayar'); // Menambahkan kolom status
        });
    }

    public function down()
    {
        Schema::table('debts', function (Blueprint $table) {
            $table->dropColumn('status'); // Menghapus kolom status jika rollback
        });
    }
}