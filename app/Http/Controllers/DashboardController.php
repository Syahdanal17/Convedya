<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Services\DashboardStatsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request, DashboardStatsService $stats)
    {
        $user = $request->user();

        return view('dashboard', array_merge(
            [
                'user'      => $user,
                'languages' => Language::where('is_active', 1)->orderBy('name')->get(),
            ],
            $stats->for($user)
        ));
    }
}
