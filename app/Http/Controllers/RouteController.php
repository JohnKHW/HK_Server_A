<?php

namespace App\Http\Controllers;

use App\Classes\ShortestPath;
use App\Models\Place;
use App\Models\Route;
use App\Models\Schedule;
use Illuminate\Http\Request;
use SebastianBergmann\Environment\Console;

class RouteController extends Controller
{
    public function search(Place $fromPlace, Place $toPlace, $depTime)
    {
        $result = [];
        $set = [];
        // $schedules = $fromPlace
        //     ->fromSchedules()
        //     ->where('departure_time', '>=', $depTime)
        //     ->get();
        // for ($i = 0; $i < sizeof($schedules); $i++) {
        //     ShortestPath::findShortest($schedules[$i]->toPlace()->first(), $schedules[$i]->arrival_time, $toPlace->id, $set, $result);
        // }
        $schedules = Schedule::all();
        $edges = [];
        foreach ($schedules as $schedule) {
            //$deltaTime = strtotime($schedule->arrival_time) - strtotime($schedule->departure_time);
            array_push($edges[$schedule->from_place_id], $schedule->to_place_id);
        }
        $paths = [];
        $color = [];
        $queue = [$fromPlace->id];
        while ($queue != null) {
            $tempPath = [];
            $firstSource = array_pop($queue);
            if (in_array($firstSource, $color)) {
                continue;
            }
            array_push($color, $firstSource);
            foreach ($edges as $edge) {
                if ($edge['s'] == $firstSource) {
                    array_push($tempPath, $edge['d']);
                    array_push($queue, $edge['d']);
                }
            }
        }


        error_log(json_encode($paths));
        //ShortestPath::findShortest($fromPlace, $depTime, $toPlace->id, $set, $result);
        $result = array_unique($result, SORT_REGULAR);
        sort($result);
        return response([
            "fromSchedules" => $fromPlace->fromSchedules()->where('departure_time', '>=', $depTime)->get(),
            "result" => $result,
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
