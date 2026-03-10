<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Tour;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Tour $tour)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:255',
            'comment' => 'required|string|max:5000',
        ]);

        $existing = Review::where('tour_id', $tour->id)->where('user_id', auth()->id())->first();
        if ($existing) {
            return back()->with('error', 'You have already reviewed this tour.');
        }

        Review::create([
            'tour_id' => $tour->id,
            'user_id' => auth()->id(),
            'rating' => $validated['rating'],
            'title' => $validated['title'],
            'comment' => $validated['comment'],
            'is_approved' => false,
        ]);

        return back()->with('success', 'Thank you! Your review has been submitted and will appear after approval.');
    }
}
