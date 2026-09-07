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
        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('titre', 200);
            $table->text('description');
            $table->string('role', 150);
            $table->text('image_principale')->nullable();
            $table->text('github_url')->nullable();
            $table->text('url_projet')->nullable();
            $table->date('date_projet')->nullable();
            $table->boolean('publie')->default(false);
        });

        DB::statement("ALTER TABLE projects ADD COLUMN fonctionnalites TEXT[] NOT NULL DEFAULT '{}';");
        DB::statement("ALTER TABLE projects ADD COLUMN captures TEXT[] NOT NULL DEFAULT '{}';");

        DB::statement('ALTER TABLE projects ADD CONSTRAINT projects_max_fonctionnalites CHECK (cardinality(fonctionnalites) <= 10);');
        DB::statement('ALTER TABLE projects ADD CONSTRAINT projects_image_si_publie CHECK (publie = FALSE OR image_principale IS NOT NULL);');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
