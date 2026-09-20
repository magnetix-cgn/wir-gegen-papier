<?php

namespace App\Http\Controllers;

use App\Models\Supporter;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('welcome', [
            'supporterCount' => Supporter::where('status', Supporter::STATUS_CONFIRMED)->count(),
        ]);
    }
}
