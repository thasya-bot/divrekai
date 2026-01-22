<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('target_pendapatan', function (Blueprint $table) {
            $table->id(); // bigint

            $table->foreignId('unit_id')
                ->constrained('units')
                ->cascadeOnDelete();

            $table->enum('periode', ['harian', 'bulanan', 'tahunan']);

            $table->date('tanggal')->nullable(); // untuk target harian
            $table->unsignedTinyInteger('bulan')->nullable(); // 1–12
            $table->year('tahun'); // wajib

            $table->decimal('target_jumlah', 15, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('target_pendapatan');
    }
};