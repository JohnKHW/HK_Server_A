<?php

namespace App\Classes;

use App\Models\Place;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Builder;

class ShortestPath
{
    public static function getPath(&$availablePath, $target, $edges, $remainPaths, $throughPath = [])
    {
        foreach ($remainPaths as $path) {
            if (!in_array($path, $throughPath)) {
                $newThroughPath = $throughPath;
                array_push($newThroughPath, $path);
                if ($path === $target) {
                    array_push($availablePath, $newThroughPath);
                }
                $nextRemainPaths = ShortestPath::remainPaths($throughPath, $edges[$path]);

                if (sizeof($remainPaths) > 0) {
                    ShortestPath::getPath($availablePath, $target, $edges, $nextRemainPaths, $newThroughPath);
                }
            }
        }
    }

    public static function remainPaths($throughPath, $targetsPath)
    {
        $newPath = [];
        foreach ($targetsPath as $path) {
            if (!in_array($path, $throughPath)) {
                array_push($newPath, $path);
            }
        }
        return $newPath;
    }
    // availablePaths = final result
    // places = all point of this path
    // depTime = start time of the place
    // placeId = current place index
    public static function getSchedules(&$availablePaths, $places, $depTime, $placeIdx, $wholeSchdules = [])
    {
        if ($placeIdx < sizeof($places) - 1) {
            $fromPlace = $places[$placeIdx];
            $toPlace = $places[$placeIdx + 1];
            $schedules = Schedule::with('fromPlace', 'toPlace')
                ->where('from_place_id', $fromPlace)
                ->where('to_place_id', $toPlace)
                ->where('departure_time', '>=', $depTime)
                ->orderBy('arrival_time', 'asc')
                ->get();
            if (sizeof($schedules) == 0) {
                return;
            }
            foreach ($schedules as $schedule) {
                $nextDepTime = $schedule->arrival_time;
                $newWholeSchdules = $wholeSchdules;
                $schedule->fromPlace();
                $schedule->toPlace();
                unset($schedule['from_place_id']);
                unset($schedule['to_place_id']);
                $startTime = strtotime($schedule->departure_time);
                $endTime = strtotime($schedule->arrival_time);
                $schedule['travel_time'] = date('i:s', $endTime - $startTime);
                array_push($newWholeSchdules, $schedule);
                ShortestPath::getSchedules($availablePaths, $places, $nextDepTime, $placeIdx + 1, $newWholeSchdules);
            }
        } else if ($placeIdx < sizeof($places)) {
            $lastSchedule = end($wholeSchdules);

            $newPath = [
                'path' => $wholeSchdules,
                'time' => $lastSchedule->arrival_time
            ];
            array_push($availablePaths, $newPath);
        }
    }
}
