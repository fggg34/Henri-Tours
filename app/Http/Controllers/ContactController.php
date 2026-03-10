<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class ContactController extends Controller
{
    public function index()
    {
        return view('pages.contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $to = Setting::get('contact_email', config('mail.from.address'));

        try {
            Mail::raw(
                "Name: {$validated['name']}\nEmail: {$validated['email']}\nSubject: {$validated['subject']}\n\nMessage:\n{$validated['message']}",
                fn ($m) => $m->to($to)->subject('Contact form: ' . $validated['subject'])
            );
        } catch (\Throwable $e) {
            throw ValidationException::withMessages(['email' => ['Unable to send message. Please try again or email us directly.']]);
        }

        return back()->with('success', 'Thank you! Your message has been sent.');
    }
}
