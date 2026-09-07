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
        Schema::create('competences', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('nom', 100);
            $table->string('categorie', 30);
            $table->string('niveau', 30)->nullable();
        });

        DB::statement("ALTER TABLE competences ADD CONSTRAINT competences_categorie_check CHECK (categorie IN ('technologie', 'outil', 'aptitude')); ");
        DB::statement("ALTER TABLE competences ADD CONSTRAINT competences_niveau_check CHECK (niveau IS NULL OR niveau IN ('débutant', 'intermédiaire', 'avancé', 'expert')); ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competences');
    }
};
