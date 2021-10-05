<?php

namespace App\Http\Controllers;

use App\Http\Requests\Schedule\DestroyRequest;
use App\Http\Requests\Schedule\StoreRequest;
use App\Models\Schedule;
use App\Models\UserToken;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function store(StoreRequest $request)
    {

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

    public function destroy(Schedule $schedule, DestroyRequest $request)
    {
        $schedule->delete();
        return response([
            'message' => 'delete success'
        ], 200);
    }
}
