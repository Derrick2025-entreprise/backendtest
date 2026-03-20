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
        Schema::table('ec', function (Blueprint $table) {
             $table->string('support_cours_url')->nullable()->after('support_cours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ec', function (Blueprint $table) {
             $table->dropColumn('support_cours_url');
        });
    }
};
