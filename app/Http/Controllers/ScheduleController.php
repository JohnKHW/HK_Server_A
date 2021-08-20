<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\UserToken;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function store(Request $request, $token)
    {
        $user = UserToken::where('token', $token)->user();
        if (!$user || $user->role != 'ADMIN') {
            return response([
                'message' => 'Unauthorized user'
            ], 401);
        }

        $validated = $request->validate([
            'from_place_id' => 'required',
            'to_place_id' => 'required',
            'departure_time' => 'required',
            'arrival_time' => 'required',
            'distance' => 'required',
            'speed' => 'required',
        ]);

        if (Schedule::create($validated)) {
            return response([
                'message' => 'create success'
            ], 200);
        } else {
            return response([
                'message' => 'Data cannot be processed'
            ], 422);
        }
    }

    public function destroy(Schedule $schedule, $token)
    {
        $user = UserToken::where('token', $token)->user();
        if (!$user || $user->role != 'ADMIN') {
            return response([
                'message' => 'Unauthorized user'
            ], 401);
        }

        if($schedule->delete()) {
            return response([
                'message' => 'delete success'
            ], 200);
        } else {
            return response([
                'message' => 'Data cannot be deleted'
            ], 400);
        }
    }
}
