<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'instagram_post_url',
        'show_on_home',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'show_on_home' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image)) {
            return null;
        }
        return '/storage/' . ltrim($this->image, '/');
    }

    public function scopeVisibleOnHome($query)
    {
        return $query->where('show_on_home', true);
    }
}
