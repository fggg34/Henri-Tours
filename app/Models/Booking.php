<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $fillable = [
        'confirmation_token',
        'user_id',
        'tour_id',
        'tour_date_id',
        'booking_date',
        'status',
        'total_amount',
        'currency',
        'guest_count',
        'guest_name',
        'guest_email',
        'first_name',
        'last_name',
        'guest_phone',
        'pickup_location',
        'special_requests',
        'billing_country',
        'billing_region',
        'billing_city',
        'billing_address',
        'payment_status',
        'payment_method',
        'stripe_session_id',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'booking_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function tourDate(): BelongsTo
    {
        return $this->belongsTo(TourDate::class, 'tour_date_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Booking $booking): void {
            if (empty($booking->confirmation_token)) {
                $booking->confirmation_token = Str::random(64);
            }
        });
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Get a short, non-sequential reference for display (e.g. on confirmation page).
     */
    public function getReferenceAttribute(): string
    {
        if ($this->confirmation_token) {
            return strtoupper(substr($this->confirmation_token, 0, 8));
        }
        return '#' . $this->id;
    }

    /**
     * Confirmation URL using token (secure; no sequential ID in URL).
     */
    public function getConfirmationUrlAttribute(): string
    {
        if ($this->confirmation_token) {
            return route('bookings.confirmation', ['token' => $this->confirmation_token]);
        }
        return route('tours.index');
    }
}
