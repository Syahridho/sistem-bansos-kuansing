<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alternatifs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_bantuan_id')->constrained('periode_bantuans')->cascadeOnDelete();
            $table->string('nik');
            $table->string('nama');
            $table->text('alamat')->nullable();
            $table->timestamps();

            $table->unique(['periode_bantuan_id', 'nik']); // NIK unique per periode
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alternatifs');
    }
};
