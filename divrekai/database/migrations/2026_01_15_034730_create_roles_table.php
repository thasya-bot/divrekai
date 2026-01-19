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
       Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique(); // admin_unit | admin_pic | pimpinan
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
