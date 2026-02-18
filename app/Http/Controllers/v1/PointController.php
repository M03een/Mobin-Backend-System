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
        ]);

        $userId = Auth::id();
        $type = $request->type;

        if ($request->type == "zikr") {
            DB::transaction(function () use ($userId, $type) {

                Point::create([
                    "user_id" => $userId,
                    'type' => $type,
                    'amount' => 25,
                    'created_at' => now(),
                ]);
    
                DB::table('user_points_total')->upsert(
                    [
                        [
                            'user_id' => $userId,
                            'created_at' => now(),
                            'updated_at' => now(),
                            'points' => 25,
                        ]
                    ],
                    ['user_id'],
                    [
                        'points' => DB::raw('points + 25'),
                        'updated_at' => now(),
                    ]
                );   
                
                
    
                DB::table('user_points_daily')->upsert(
                    [
                        [
                            'user_id' => $userId,
                            'date' => now()->toDateString(),
                            'points' => 25,
                        ]
                    ],
                    ['user_id', 'date'],
                    [
                        'points' => DB::raw('points + 25'),
                        'date' => now()->toDateString(),
                    ]
                );                                     
                
    
                DB::table('user_points_monthly')->upsert(
                    [
                        [
                            'user_id' => $userId,
                            'month' => now()->format('Y-m'),
                            'points' => 25
                        ]
                    ],
                    ['user_id'],
                    [
                        'month' => now()->format('Y-m'),
                        'points' => DB::raw('points + 25')
                    ]
                    );
                
    
                $weekKey = now()->year . '-' . now()->weekOfYear;
    
                $weekly = DB::table('user_points_weekly')
                    ->where('user_id', $userId)
                    ->where('week', $weekKey);
    
                if ($weekly->exists()) {
                    $weekly->increment('points', 25);
                } else {
                    DB::table('user_points_weekly')->insert([
                        'user_id' => $userId,
                        'week' => $weekKey,
                        'points' => 25,
                    ]);
                }
    
                $user = User::find($userId);
                $user->increment('points', 25);
                $user->last_point_at = now();
                $user->save();
                $user->updateStreak();
            });

            return response()->json([
                'message' => "25 points added to user id $userId"
            ], 201);
        } elseif ($request->type == "tasbeh") {
            DB::transaction(function () use ($userId, $type) {

                Point::create([
                    "user_id" => $userId,
                    'type' => $type,
                    'amount' => 25,
                    'created_at' => now(),
                ]);
    
                DB::table('user_points_total')->upsert(
                    [
                        [
                            'user_id' => $userId,
                            'created_at' => now(),
                            'updated_at' => now(),
                            'points' => 25,
                        ]
                    ],
                    ['user_id'],
                    [
                        'points' => DB::raw('points + 25'),
                        'updated_at' => now(),
                    ]
                );   
                
                
    
                DB::table('user_points_daily')->upsert(
                    [
                        [
                            'user_id' => $userId,
                            'date' => now()->toDateString(),
                            'points' => 5,
                        ]
                    ],
                    ['user_id', 'date'],
                    [
                        'points' => DB::raw('points + 5'),
                        'date' => now()->toDateString(),
                    ]
                );                                     
                
    
                DB::table('user_points_monthly')->upsert(
                    [
                        [
                            'user_id' => $userId,
                            'month' => now()->format('Y-m'),
                            'points' => 5
                        ]
                    ],
                    ['user_id'],
                    [
                        'month' => now()->format('Y-m'),
                        'points' => DB::raw('points + 5')
                    ]
                    );
                
    
                $weekKey = now()->year . '-' . now()->weekOfYear;
    
                $weekly = DB::table('user_points_weekly')
                    ->where('user_id', $userId)
                    ->where('week', $weekKey);
    
                if ($weekly->exists()) {
                    $weekly->increment('points', 5);
                } else {
                    DB::table('user_points_weekly')->insert([
                        'user_id' => $userId,
                        'week' => $weekKey,
                        'points' => 5,
                    ]);
                }
    
                $user = User::find($userId);
                $user->increment('points', 5);
                $user->last_point_at = now();
                $user->save();
                $user->updateStreak();
            });

            return response()->json([
                'message' => "5 points added to user id $userId"
            ], 201);
        } else {
            return response()->json([
                "message" => "wrong type"
            ], 400);
        }
    }
}