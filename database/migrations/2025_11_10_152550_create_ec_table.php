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
        Schema::create('ec', function (Blueprint $table) {
            $table->increments("code_ec")->primary();
            $table->string("label_ec", 256);
            $table->text("description_ec");
            $table->integer("nb_heures_ec");
            $table->integer("nb_credits_ec");
            $table->string("code_ue");
            $table->foreign("code_ue")->references("code_ue")->on("ue")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ec');
    }
};
