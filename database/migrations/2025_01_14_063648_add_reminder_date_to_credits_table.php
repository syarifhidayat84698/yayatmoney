<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReminderDateToCreditsTable extends Migration
{
    public function up()
    {
        Schema::table('credits', function (Blueprint $table) {
            $table->date('reminder_date')->nullable()->after('due_date'); // Menambahkan kolom reminder_date
        });
    }

    public function down()
    {
        Schema::table('credits', function (Blueprint $table) {
            $table->dropColumn('reminder_date'); // Menghapus kolom reminder_date jika migrasi dibatalkan
        });
    }
}
