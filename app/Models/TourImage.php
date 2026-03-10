<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class TourImage extends Model
{
    protected $fillable = ['tour_id', 'path', 'alt', 'sort_order'];

    protected function casts(): array
    {
        return ['sort_order' => 'integer'];
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function getUrlAttribute(): ?string
    {
        if (empty($this->path)) {
            return null;
        }
        // External URLs (e.g. from CSV import) - return as-is
        if (str_starts_with($this->path, 'http://') || str_starts_with($this->path, 'https://')) {
            return $this->path;
        }
        // Local storage path - use relative URL so images load on whatever host/port the app is served from
        return '/storage/' . ltrim($this->path, '/');
    }
}
