<?php

namespace App\Classes;

use App\Models\Place;
use Illuminate\Database\Eloquent\Builder;

class ShortestPath
{
    public static function findShortest(Place $place, $depTime, $target, $set, &$result, $time = 0, $route = [])
    {

        $schedules = $place
            ->fromSchedules()
            ->where('departure_time', '>=', $depTime)
            ->get();
        for ($i = 0; $i < sizeof($schedules); $i++) {
            error_log('schedules id: ' . $schedules[$i]->id);
            $newRoute = $route;
            array_push($newRoute, $schedules[$i]->id);
            $departure_time = strtotime($schedules[$i]->departure_time);
            $arrival_time = strtotime($schedules[$i]->arrival_time);
            $newTime = $time + date('i', $arrival_time - $departure_time);
            if ($schedules[$i]->to_place_id === $target) {
                array_push($result, [$newRoute, $newTime]);
                continue;
            } else if (in_array($schedules[$i]->to_place_id, $set)) {
                continue;
            } else {
                array_push($set, $schedules[$i]->to_place_id);
                ShortestPath::findShortest($schedules[$i]->toPlace()->first(), $schedules[$i]->arrival_time, $target, $set, $result, $newTime, $newRoute);
            }
        }
    }
}
