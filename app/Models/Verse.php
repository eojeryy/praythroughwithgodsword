<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['chapter_id', 'category_id', 'prayer_topic', 'verse_number', 'verse_text'])]
class Verse extends Model
{
    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
