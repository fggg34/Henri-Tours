<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TourActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'icon',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function tours(): BelongsToMany
    {
        return $this->belongsToMany(Tour::class, 'activity_tour');
    }

    public function getIconUrlAttribute(): ?string
    {
        if (empty($this->icon)) {
            return null;
        }
        $filename = basename($this->icon);
        return route('storage.tour-activities.svg', ['filename' => $filename]);
    }
}
