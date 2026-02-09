<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LeaderBoardController extends Controller
{
    // كل الوقت
    public function all()
    {
        $leaderboard = DB::table('user_points_total')
            ->join('users', 'users.id', '=', 'user_points_total.user_id')
            ->orderByDesc('points')
            ->limit(10)
            ->get(['users.id', 'users.username', 'user_points_total.points']);

        return response()->json($leaderboard);
    }

    // اليوم
    public function day()
    {
        $today = Carbon::today()->toDateString();

        $leaderboard = DB::table('user_points_daily')
            ->join('users', 'users.id', '=', 'user_points_daily.user_id')
            ->where('date', $today)
            ->orderByDesc('points')
            ->limit(10)
            ->get(['users.id', 'users.username', 'user_points_daily.points']);

        return response()->json($leaderboard);
    }

    // الأسبوع
    public function week()
    {
        $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
        $endOfWeek = Carbon::now()->endOfWeek()->toDateString();

        $leaderboard = DB::table('points')
            ->select('user_id', DB::raw('SUM(amount) as points'))
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->groupBy('user_id')
            ->orderByDesc('points')
            ->limit(10)
            ->get()
            ->map(function($item) {
                $user = DB::table('users')->find($item->user_id);
                return [
                    'id' => $user->id,
                    'username' => $user->username,
                    'points' => $item->points
                ];
            });

        return response()->json($leaderboard);
    }

    // الشهر
    public function month()
    {
        $month = Carbon::now()->format('Y-m');

        $leaderboard = DB::table('user_points_monthly')
            ->join('users', 'users.id', '=', 'user_points_monthly.user_id')
            ->where('month', $month)
            ->orderByDesc('points')
            ->limit(10)
            ->get(['users.id', 'users.username', 'user_points_monthly.points']);

        return response()->json($leaderboard);
    }
}
