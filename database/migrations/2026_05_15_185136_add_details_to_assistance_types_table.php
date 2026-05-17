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
        Schema::table('assistance_types', function (Blueprint $table) {
            $table->decimal('jumlah_diterima', 15, 2)->nullable()->after('description');
            $table->integer('maksimal_penerima')->nullable()->after('jumlah_diterima');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assistance_types', function (Blueprint $table) {
            $table->dropColumn(['jumlah_diterima', 'maksimal_penerima']);
        });
    }
};
