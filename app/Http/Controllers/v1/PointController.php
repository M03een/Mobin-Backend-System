<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Point;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PointController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:zikr,tasbeh',
            'amount' => 'required|integer|min:1'
        ]);

        $userId = Auth::id();
        $amount = $request->amount;
        $type = $request->type;

        DB::transaction(function () use ($userId, $amount, $type) {

            Point::create([
                "user_id" => $userId,
                'type' => $type,
                'amount' => $amount,
                'created_at' => now(),
            ]);

            DB::table('user_points_total')->updateOrInsert(
                ['user_id' => $userId],
                [
                    'points' => DB::raw("COALESCE(points, 0) + $amount"),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
            

            DB::table('user_points_daily')->updateOrInsert(
                ['user_id' => $userId, 'date' => now()->toDateString()],
                ['points' => DB::raw("points + $amount")]
            );

            DB::table('user_points_monthly')->updateOrInsert(
                ['user_id' => $userId, 'month' => now()->format('Y-m')],
                ['points' => DB::raw("points + $amount")]
            );

            $weekKey = now()->year . '-' . now()->weekOfYear;

            $weekly = DB::table('user_points_weekly')
                ->where('user_id', $userId)
                ->where('week', $weekKey);

            if ($weekly->exists()) {
                $weekly->increment('points', $amount);
            } else {
                DB::table('user_points_weekly')->insert([
                    'user_id' => $userId,
                    'week' => $weekKey,
                    'points' => $amount,
                ]);
            }

            $user = User::find($userId);
            $user->increment('points', $amount);

            if ($type == "zikr") {
                $user->last_point_at = now();
                $user->save();
            }
        });

        return response()->json([
            'message' => "$amount points added to user id $userId"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $user = User::findOrFail($id);

        // نقاط المستخدم حسب الفترة
        // $daily = DB::table('user_points_daily')
        //     ->where('user_id', $id)
        //     ->where('date', now()->toDateString())
        //     ->value('points') ?? 0;

        // $monthly = DB::table('user_points_monthly')
        //     ->where('user_id', $id)
        //     ->where('month', now()->format('Y-m'))
        //     ->value('points') ?? 0;

        // $total = DB::table('user_points_total')
        //     ->where('user_id', $id)
        //     ->value('points') ?? 0;

        // return response()->json([
        //     'user_id' => $id,
        //     'username' => $user->username,
        //     'points' => [
        //         'daily' => $daily,
        //         'monthly' => $monthly,
        //         'total' => $total,
        //     ]
        // ]);
    }
}