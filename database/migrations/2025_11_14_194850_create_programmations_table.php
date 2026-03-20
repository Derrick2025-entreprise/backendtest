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
        Schema::create('programmations', function (Blueprint $table) {
            $table->id();
            $table->integer("num_salle");
            $table->unsignedInteger("code_ec");
            $table->string("code_personnel");
            $table->integer("nbre_heures");
            $table->date("date");
            $table->time("heure_debut");
            $table->time("heure_fin");
            $table->enum("status", ["PLANIFIÉ", "TERMINÉ", "ANNULÉ", "REPORTÉ"]);
            // $table->primary(['num_salle', 'code_ec', 'code_personnel']);
            $table->foreign("num_salle")->references("num_salle")->on("salles")->onDelete("cascade");
            $table->foreign("code_ec")->references("code_ec")->on("ec")->onDelete("cascade");
            $table->foreign("code_personnel")->references("code_personnel")->on("personnel")->onDelete("cascade");
            $table->unique(["num_salle", "code_ec", "code_personnel"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programmations');
    }
};
