<?php

namespace App\Http\Controllers;

use App\Classes\ShortestPath;
use App\Http\Requests\Route\SelectionRequest;
use App\Models\Place;
use App\Models\Route;
use App\Models\RouteSchedules;
use App\Models\Schedule;
use App\Models\UserToken;
use Illuminate\Http\Request;
use SebastianBergmann\Environment\Console;

class RouteController extends Controller
{
    public function search(Place $fromPlace, Place $toPlace, $depTime, Request $request)
    {
        $userToken = UserToken::where('token', $request->token)->first();

        if ($userToken == null) {
            return response([
                'message' => 'Unauthorized user'
            ], 401);
        }

        $number_of_history = Route::firstOrCreate([
            'from_place_id' => $fromPlace->id,
            'to_place_id' => $toPlace->id,
        ])->users->count();

        error_log(json_encode($number_of_history));

        $schedules = Schedule::all();
        $edges = [];
        foreach ($schedules as $schedule) {
            if (!array_key_exists($schedule->from_place_id, $edges)) {
                $edges[$schedule->from_place_id] = [];
            }
            if (!in_array($schedule->to_place_id, $edges[$schedule->from_place_id])) {
                array_push($edges[$schedule->from_place_id], $schedule->to_place_id);
            }
        }
        $paths = [];
        ShortestPath::getPath($paths, $toPlace->id, $edges, $edges[$fromPlace->id], [$fromPlace->id]);

        $availablePaths = [];
        foreach ($paths as $path) {
            ShortestPath::getSchedules($availablePaths, $path, $depTime, 0);
        }
        $result = array_unique($availablePaths, SORT_REGULAR);
        usort($result, function ($a, $b) {
            return $a['time'] <=> $b['time'];
        });
        $final = [];

        for ($i = 0; $i < 5; $i++) {
            if ($i >= sizeof($result)) {
                return;
            }
            array_push($final, $result[$i]['path']);
        }
        return response([
            'number_of_history' => $number_of_history,
            "schedules" => $final,
        ], 200);
    }

    public function selection(SelectionRequest $request)
    {
        $userToken = UserToken::where('token', $request->input('token'))->first();
        error_log($userToken);

        if ($userToken == null) {
            return response([
                'message' => 'Unauthorized user'
            ], 401);
        }

        $route = Route::firstOrCreate([
            'from_place_id' => $request->from_place_id,
            'to_place_id' => $request->to_place_id,
        ]);

        $route->users()->sync($userToken->user);

        $schedules = [];

        if (is_array($request->schedule_id)) {
            $schedules = $request->schedule_id;
        } else {
            array_push($schedules, $request->schedule_id);
        }

        for ($i = 0; $i < sizeof($schedules); $i++) {
            RouteSchedules::firstOrCreate([
                'route_id' => $route->id,
                'schedule_id' => $schedules[$i],
                'step' => $i,
            ]);
        }

        return response([
            'message' => 'create success'
        ], 200);
    }
}
