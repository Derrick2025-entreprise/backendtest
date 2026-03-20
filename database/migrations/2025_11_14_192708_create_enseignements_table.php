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
        Schema::create('enseignements', function (Blueprint $table) {
            $table->id();
            $table->string("code_personnel");
            $table->unsignedInteger("code_ec");
            $table->foreign("code_personnel")->references("code_personnel")->on("personnel")->onDelete("cascade");
            $table->foreign("code_ec")->references("code_ec")->on("ec")->onDelete("cascade");
            $table->unique(['code_personnel', 'code_ec']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enseignements');
    }
};
