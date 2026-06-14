<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('verses', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('chapter_id')->constrained()->nullOnDelete();
            $table->string('prayer_topic')->nullable()->after('category_id');
        });
    }

    public function down(): void
    {
        Schema::table('verses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
            $table->dropColumn('prayer_topic');
        });
    }
};
