<?php

namespace App\Http\Controllers;

use App\Classes\ShortestPath;
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

        $route = Route::firstOrCreate([
            'from_place_id' => $fromPlace->id,
            'to_place_id' => $toPlace->id,
            'departure_time' => $depTime,
        ]);
        $number_of_history = sizeof($route->users());

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
        //error_log(json_encode($paths));

        $availablePaths = [];
        foreach ($paths as $path) {
            ShortestPath::getSchedules($availablePaths, $path, $depTime, 0);
        }
        $result = array_unique($availablePaths, SORT_REGULAR);
        usort($result, function ($a, $b) {
            return $a['time'] <=> $b['time'];
        });
        $final = [];

        $route->user()->attach($userToken->user());

        for ($i = 0; $i < 5; $i++) {
            if ($i >= sizeof($result)) {
                return;
            }
            for ($j = 0; $j < sizeof($result[$i]['path']); $j++) {
                RouteSchedules::firstOrCreate([
                    'route_id' => $route->id,
                    'schedule_id' => $result[$i]['path'][$j],
                    'step' => $j,
                    'rank' => $i
                ]);
            }
            array_push($final, $result[$i]['path']);
        }
        return response([
            'number_of_history' => $number_of_history,
            "schedules" => $final,
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function show(Route $route)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function edit(Route $route)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Route $route)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function destroy(Route $route)
    {
        //
    }
}
