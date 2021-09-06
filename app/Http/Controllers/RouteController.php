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
        //error_log(json_encode($edges));
        $paths = [];
        ShortestPath::getPath($paths, $toPlace->id, $edges, $edges[$fromPlace->id], [$fromPlace->id]);
        error_log(json_encode($paths));

        $availablePaths = [];
        foreach ($paths as $path) {
            $isAdd = true;
            $time = 0;
            $depTimeCopy = $depTime;
            for ($i = 0; $i < count($path) - 1; $i++) {
                $isPath = Schedule::where('from_place_id', $path[$i])
                    ->where('to_place_id', $path[$i + 1])
                    ->where('departure_time', '>=', $depTimeCopy)
                    ->orderBy('arrival_time', 'asc')
                    ->get();
                if (sizeof($isPath) <= 0) {
                    $isAdd = false;
                    break;
                } else {
                    ////////////////////////////////
                    $depTimeCopy = $isPath[0]->arrival_time;
                    $oldDepTime = strtotime($isPath[0]->departure_time);
                    $arrTime = strtotime($depTimeCopy);
                    $time += ($arrTime - $oldDepTime);
                }
            }
            if ($isAdd) {
                $newPath = [
                    'path' => $path,
                    'time' => $time / 60
                ];
                array_push($availablePaths, $newPath);
            }
        }
        error_log(json_encode($availablePaths));
        $result = array_unique($availablePaths, SORT_REGULAR);
        usort($result, function ($a, $b) {
            return $a['time']  <=> $b['time'];
        });
        return response([
            //"fromSchedules" => $fromPlace->fromSchedules()->where('departure_time', '>=', $depTime)->get(),
            "path" => $paths,
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
