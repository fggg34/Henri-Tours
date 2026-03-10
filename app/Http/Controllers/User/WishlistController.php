<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function store(Tour $tour)
    {
        $tour->wishlistedBy()->syncWithoutDetaching([auth()->id()]);
        return back()->with('success', 'Added to wishlist.');
    }

    public function destroy(Tour $tour)
    {
        $tour->wishlistedBy()->detach(auth()->id());
        return back()->with('success', 'Removed from wishlist.');
    }
}
