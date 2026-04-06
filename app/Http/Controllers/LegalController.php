<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LegalController extends Controller
{
    public function mentions(): View
    {
        return view('legal.mentions');
    }

    public function privacy(): View
    {
        return view('legal.privacy');
    }
}
