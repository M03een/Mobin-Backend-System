<?php

namespace App\Http\Controllers;

use App\Models\Point;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PointController extends Controller
{
    public function learderboard()
    {
        $leaderboard = DB::table('users')->orderBy('points', 'desc')->limit(10)->get(['id','username', 'points']);
        return response()->json($leaderboard);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->type == "zikr") {
            $user = User::find(Auth::id());
            Point::create([
                "user_id" => Auth::id(),
                'type' => $request->type,
                'amount' => $request->amount
            ]);

            $user->increment('points', $request->amount);

            return response()->json([
                'message' => $request->amount . " points add to user id " . Auth::id()
            ], 201);
        }

        if ($request->type == "tasbeh") {
            $user = User::find(Auth::id());
            Point::create([
                "user_id" => Auth::id(),
                'type' => $request->type,
                'amount' => $request->amount
            ]);

            $user->increment('points', $request->amount);

            return response()->json([
                'message' => $request->amount . " points add to user id " . Auth::id()
            ], 201);
        }

        return response()->json([
            "message" => "Unknown type was provided"
        ], 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // TODO Get the user's points
    }
}
