<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;

class UserManualController extends Controller
{
    public function pdf()
    {
        return Pdf::loadView('manual.pdf')
            ->setPaper('a4')
            ->stream('PGECC-User-Manual.pdf');
    }
}
