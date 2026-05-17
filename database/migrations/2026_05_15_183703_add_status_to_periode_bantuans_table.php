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
        Schema::table('periode_bantuans', function (Blueprint $table) {
            $table->enum('status', ['buka', 'tutup'])->default('buka')->after('tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('periode_bantuans', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
