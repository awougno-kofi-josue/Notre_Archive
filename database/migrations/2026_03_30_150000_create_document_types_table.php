<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique();
            $table->timestamps();
        });

        $now = now();
        DB::table('document_types')->insert([
            ['nom' => 'Cours', 'created_at' => $now, 'updated_at' => $now],
            ['nom' => 'TD', 'created_at' => $now, 'updated_at' => $now],
            ['nom' => 'Examen', 'created_at' => $now, 'updated_at' => $now],
            ['nom' => 'Correction', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('document_types');
    }
};
