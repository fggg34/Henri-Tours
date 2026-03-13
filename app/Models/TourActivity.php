<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Resolve the icon path from various storage formats (plain string, array, JSON-encoded array).
     */
    private function getResolvedIconPath(): ?string
    {
        $icon = $this->getRawIconValue();
        if (empty($icon) || ! is_string($icon)) {
            return null;
        }
        return str_replace('\\', '', $icon);
    }

    /**
     * Get the raw icon value, normalizing arrays and JSON-encoded arrays to a single path.
     */
    private function getRawIconValue(): mixed
    {
        $icon = $this->attributes['icon'] ?? null;
        if (empty($icon)) {
            return null;
        }
        if (is_array($icon)) {
            return $icon[0] ?? null;
        }
        if (is_string($icon)) {
            $trimmed = trim($icon);
            if (str_starts_with($trimmed, '[')) {
                $decoded = json_decode($icon, true);
                if (is_array($decoded) && ! empty($decoded)) {
                    $first = $decoded[0] ?? null;
                    return is_string($first) ? $first : null;
                }
            }
            return $icon;
        }
        return null;
    }

    public function getIconUrlAttribute(): ?string
    {
        $path = $this->getResolvedIconPath();
        if (empty($path)) {
            return null;
        }
        $filename = basename($path);
        $storagePath = str_contains($path, 'tour_activities') ? $path : 'tour_activities/' . $filename;
        if (Storage::disk('public')->exists($storagePath)) {
            return Storage::disk('public')->url($storagePath);
        }
        return route('storage.tour-activities.svg', ['filename' => $filename]);
    }

    /**
     * Get the SVG content for inline embedding (always renders correctly).
     */
    public function getIconSvgContent(): ?string
    {
        $icon = $this->getResolvedIconPath();
        if (empty($icon)) {
            return null;
        }
        $paths = [
            $icon,
            ltrim($icon, '/'),
            'tour_activities/' . basename($icon),
        ];
        $disk = Storage::disk('public');
        foreach ($paths as $path) {
            if ($disk->exists($path)) {
                $content = $disk->get($path);
                if (is_string($content) && str_contains($content, '<svg')) {
                    return $content;
                }
            }
        }
        $fsPaths = [
            storage_path('app/public/' . ltrim($icon, '/')),
            storage_path('app/public/tour_activities/' . basename($icon)),
        ];
        foreach ($fsPaths as $path) {
            if (is_file($path)) {
                $content = file_get_contents($path);
                if ($content !== false && str_contains($content, '<svg')) {
                    return $content;
                }
            }
        }
        return null;
    }
}
