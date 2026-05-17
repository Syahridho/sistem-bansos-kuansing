<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kriterias', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->string('nama');
            $table->decimal('bobot', 8, 4)->default(0);
            $table->enum('jenis', ['benefit', 'cost']);
            $table->enum('tipe_input', ['rupiah', 'angka', 'pilihan', 'checkbox', 'status'])->default('angka');
            $table->json('opsi')->nullable();
            $table->foreignId('assistance_type_id')->constrained('assistance_types')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['kode', 'assistance_type_id']); // Kode unik per jenis bantuan
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kriterias');
    }
};
