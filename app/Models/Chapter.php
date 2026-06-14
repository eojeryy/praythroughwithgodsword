<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['book_id', 'book_name', 'chapter_number'])]
class Chapter extends Model
{
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function verses(): HasMany
    {
        return $this->hasMany(Verse::class);
    }
}
