<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('formations', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('nom', 200);
            $table->string('institut', 200);
            $table->string('domaine', 150)->nullable();
            $table->string('diplome', 150)->nullable();
            $table->text('description')->nullable();
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->text('justificatif')->nullable();
        });

        DB::statement('ALTER TABLE formations ADD CONSTRAINT formations_dates_valides CHECK (date_fin IS NULL OR date_fin >= date_debut);');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formations');
    }
};
