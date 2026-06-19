<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Services\Points\PointFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PointController extends Controller
{
    public function store(Request $request, PointFactory $factory)
    {
        $request->validate([
            'type' => 'required|in:zikr,tasbeh',
            'amount' => 'required|integer|max:100|min:1'
        ]);

        $userId = Auth::id();
        $type = $request->type;
        $amount = $request->amount;

        $signer = $factory->make($type);
        $signer->sign($userId, $amount);

        return response()->json([
            'message' => $amount . " points added to user id $userId"
        ], 201);
    }
}