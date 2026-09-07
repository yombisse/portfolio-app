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
        Schema::create('experiences', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('titre', 200);
            $table->string('entreprise', 200);
            $table->string('lieu', 150)->nullable();
            $table->string('contact_entreprise', 255)->nullable();
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->text('justificatif')->nullable();
        });

        DB::statement("ALTER TABLE experiences ADD COLUMN bilan TEXT[] NOT NULL DEFAULT '{}';");
        DB::statement('ALTER TABLE experiences ADD CONSTRAINT experiences_dates_valides CHECK (date_fin IS NULL OR date_fin >= date_debut);');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
