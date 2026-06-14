<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chapters', function (Blueprint $table) {
            $table->foreignId('book_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });

        $bookNames = DB::table('chapters')
            ->whereNotNull('book_name')
            ->distinct()
            ->pluck('book_name');

        foreach ($bookNames as $bookName) {
            $bookId = DB::table('books')->insertGetId([
                'name' => $bookName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('chapters')
                ->where('book_name', $bookName)
                ->update([
                    'book_id' => $bookId,
                    'updated_at' => now(),
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('chapters', function (Blueprint $table) {
            $table->dropConstrainedForeignId('book_id');
        });
    }
};
