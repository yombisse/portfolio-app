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
        DB::statement('CREATE EXTENSION IF NOT EXISTS pgcrypto;');

        Schema::create('profiles', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('nom', 150);
            $table->string('titre', 200);
            $table->text('bio');
            $table->text('photo')->nullable();
            $table->string('localisation', 150);
            $table->string('email', 255);
            $table->string('telephone', 30)->nullable();
            $table->jsonb('reseaux_sociaux')->nullable();
            $table->text('cv')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
