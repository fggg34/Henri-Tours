<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivateGroupTourRequest extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'expected_departure_date',
        'expected_return_date',
        'number_of_participants',
        'departing_from',
        'additional_info',
    ];

    protected function casts(): array
    {
        return [
            'expected_departure_date' => 'date',
            'expected_return_date' => 'date',
            'number_of_participants' => 'integer',
        ];
    }
}
