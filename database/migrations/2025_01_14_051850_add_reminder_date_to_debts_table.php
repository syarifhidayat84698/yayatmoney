<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('debts', function (Blueprint $table) {
        $table->date('reminder_date')->nullable()->after('due_date'); // Menambahkan kolom reminder_date
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('debts', function (Blueprint $table) {
        $table->dropColumn('reminder_date'); // Menghapus kolom reminder_date
    });
}
};
