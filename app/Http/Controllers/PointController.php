<?php

namespace App\Http\Controllers;

use App\Models\Point;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // TODO The rank system to implemented
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
