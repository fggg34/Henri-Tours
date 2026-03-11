<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportBooking extends Model
{
    protected $fillable = [
        'name',
        'email',
        'telephone',
        'travel_date',
        'travel_end_date',
        'pickup_location',
        'dropoff_location',
        'preferred_vehicle',
        'group_size',
        'message',
    ];

    protected function casts(): array
    {
        return [
            'travel_date' => 'date',
            'travel_end_date' => 'date',
            'group_size' => 'integer',
        ];
    }
}
