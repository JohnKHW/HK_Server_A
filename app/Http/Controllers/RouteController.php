<?php

namespace App\Http\Controllers;

use App\Classes\ShortestPath;
use App\Models\Place;
use App\Models\Route;
use Illuminate\Http\Request;

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
        ShortestPath::findShortest($fromPlace, $depTime, $toPlace->id, $set, $result);
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
