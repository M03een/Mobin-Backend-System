<?php

namespace App\Services\Points;

use Illuminate\Support\Facades\DB;
use App\Models\Point;
use App\Models\User;

class ZikrSigner implements PointSignerInterface {
    public function sign(string $userId, int $amount): void {
        DB::transaction(function () use ($userId, $amount) {

            Point::create([
                "user_id" => $userId,
                'type' => 'zikr',
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
                ['user_id', 'month'],
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
    }
}