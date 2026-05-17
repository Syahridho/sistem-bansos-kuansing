<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('periode_bantuans', function (Blueprint $table) {
            $table->date('tanggal_mulai')->nullable()->after('assistance_type_id');
            $table->date('tanggal_akhir')->nullable()->after('tanggal_mulai');
        });

        DB::statement('UPDATE periode_bantuans SET tanggal_mulai = tanggal, tanggal_akhir = tanggal');

        Schema::table('periode_bantuans', function (Blueprint $table) {
            $table->dropColumn('tanggal');
        });
    }

    public function down(): void
    {
        Schema::table('periode_bantuans', function (Blueprint $table) {
            $table->date('tanggal')->nullable()->after('assistance_type_id');
        });

        DB::statement('UPDATE periode_bantuans SET tanggal = tanggal_mulai');

        Schema::table('periode_bantuans', function (Blueprint $table) {
            $table->dropColumn(['tanggal_mulai', 'tanggal_akhir']);
        });
    }
};
