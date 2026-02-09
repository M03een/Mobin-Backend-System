<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function Illuminate\Support\now;

class LeaderBoardController extends Controller
{
    public function learderboard(Request $request)
    {
        $leaderboard = DB::table('users')->orderBy('points', 'desc')->limit(10)->get(['id','username', 'points']);
        return response()->json($leaderboard);
    }

    public function month() {
        $leaderboard = DB::table('users')->whereYear('last_point_at', now()->year)
        ->whereMonth('last_point_at', now()->month)
        ->orderBy('points', 'desc')
        ->limit(10)
        ->get(['id', 'username', 'points']);

        return response()->json($leaderboard);
    }

    public function week() {
        $leaderboard = DB::table('users')->whereBetween('last_point_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])
        ->orderBy('points', 'desc')
        ->limit(10)
        ->get(['id', 'username', 'points']);

        return response()->json($leaderboard);
    }

    public function day() {
        $leaderboard = DB::table('users')->whereDate('last_point_at', today())
        ->orderBy('points', 'desc')
        ->limit(10)
        ->get(['id', 'username', 'points']);

        return response()->json($leaderboard);
    }
}
