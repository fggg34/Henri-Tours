<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogTagTranslation extends Model
{
    protected $fillable = [
        'blog_tag_id',
        'locale',
        'name',
        'slug',
    ];

    public function blogTag(): BelongsTo
    {
        return $this->belongsTo(BlogTag::class);
    }
}
