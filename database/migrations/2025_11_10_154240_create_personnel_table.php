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
        Schema::create('personnel', function (Blueprint $table) {
            $table->string("code_personnel", 20)->primary();
            $table->string("nom_personnel", 100);
            $table->string("prenom_personnel", 100)->nullable();
            $table->enum("sexe_personnel", ['M', 'F']);
            $table->string("phone_personnel");
            $table->string("login_personnel");
            $table->string("password_personnel");
            $table->enum("type_personnel", ['ENSEIGNANT', 'RESPONSABLE_ACADEMIQUE', 'RESPONSABLE_DISCIPLINE']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnel');
    }
};
