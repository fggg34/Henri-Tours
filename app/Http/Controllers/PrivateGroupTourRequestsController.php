<?php

namespace App\Http\Controllers;

class PrivateGroupTourRequestsController extends Controller
{
    public function __invoke()
    {
        return view('pages.private-group-tour-requests');
    }
}
