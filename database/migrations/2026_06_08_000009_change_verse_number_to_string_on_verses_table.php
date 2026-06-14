<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('verses', function (Blueprint $table) {
            $table->string('verse_number')->change();
        });
    }

    public function down(): void
    {
        Schema::table('verses', function (Blueprint $table) {
            $table->unsignedInteger('verse_number')->change();
        });
    }
};
