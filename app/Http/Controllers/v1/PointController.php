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
            'amount' => 'required|integer|max:100|min:1'
        ]);

        $userId = Auth::id();
        $type = $request->type;
        $amount = $request->amount;

        if ($request->type == "zikr") {
            DB::transaction(function () use ($userId, $type) {

                Point::create([
                    "user_id" => $userId,
                    'type' => $type,
                    'amount' => $amount,
                    'created_at' => now(),
                ]);
    
                DB::table('user_points_total')->upsert(
                    [
                        [
                            'user_id' => $userId,
                            'created_at' => now(),
                            'updated_at' => now(),
                            'points' => $amount,
                        ]
                    ],
                    ['user_id'],
                    [
                        'points' => DB::raw('points + ' . (int) $amount),
                        'updated_at' => now(),
                    ]
                );   
                
                
    
                DB::table('user_points_daily')->upsert(
                    [
                        [
                            'user_id' => $userId,
                            'date' => now()->toDateString(),
                            'points' => $amount,
                        ]
                    ],
                    ['user_id', 'date'],
                    [
                        'points' => DB::raw('points + ' . (int) $amount),
                        'date' => now()->toDateString(),
                    ]
                );                                     
                
    
                DB::table('user_points_monthly')->upsert(
                    [
                        [
                            'user_id' => $userId,
                            'month' => now()->format('Y-m'),
                            'points' => $amount
                        ]
                    ],
                    ['user_id'],
                    [
                        'month' => now()->format('Y-m'),
                        'points' => DB::raw('points + ' . (int) $amount)
                    ]
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
                $user->last_point_at = now();
                $user->save();
                $user->updateStreak();
            });

            return response()->json([
                'message' => $amount . " points added to user id $userId"
            ], 201);
        } elseif ($request->type == "tasbeh") {
            DB::transaction(function () use ($userId, $type) {

                Point::create([
                    "user_id" => $userId,
                    'type' => $type,
                    'amount' => $amount,
                    'created_at' => now(),
                ]);
    
                DB::table('user_points_total')->upsert(
                    [
                        [
                            'user_id' => $userId,
                            'created_at' => now(),
                            'updated_at' => now(),
                            'points' => $amount,
                        ]
                    ],
                    ['user_id'],
                    [
                        'points' => DB::raw('points + ' . (int) $amount),
                        'updated_at' => now(),
                    ]
                );   
                
                
    
                DB::table('user_points_daily')->upsert(
                    [
                        [
                            'user_id' => $userId,
                            'date' => now()->toDateString(),
                            'points' => $amount,
                        ]
                    ],
                    ['user_id', 'date'],
                    [
                        'points' => DB::raw('points + ' . (int) $amount),
                        'date' => now()->toDateString(),
                    ]
                );                                     
                
    
                DB::table('user_points_monthly')->upsert(
                    [
                        [
                            'user_id' => $userId,
                            'month' => now()->format('Y-m'),
                            'points' => $amount
                        ]
                    ],
                    ['user_id'],
                    [
                        'month' => now()->format('Y-m'),
                        'points' => DB::raw('points + ' . (int) $amount)
                    ]
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
                $user->last_point_at = now();
                $user->save();
                $user->updateStreak();
            });

            return response()->json([
                'message' => $amount . " points added to user id $userId"
            ], 201);
        } else {
            return response()->json([
                "message" => "wrong type"
            ], 400);
        }
    }
}