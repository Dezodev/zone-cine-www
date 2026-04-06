<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LegalController extends Controller
{
    public function mentions(): View
    {
        $this->setSeo(
            'Mentions légales',
            'Mentions légales du site Zone Ciné.',
        );

        return view('legal.mentions');
    }

    public function privacy(): View
    {
        $this->setSeo(
            'Politique de confidentialité',
            'Politique de confidentialité du site Zone Ciné.',
        );

        return view('legal.privacy');
    }
}
