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

    public static function getSchedules(&$pathList, $places, $depTime)
    {
        $isAdd = true;
        $time = 0;
        $depTimeCopy = $depTime;
        for ($i = 0; $i < count($places) - 1; $i++) {
            $isPath = Schedule::where('from_place_id', $places[$i])
                ->where('to_place_id', $places[$i + 1])
                ->where('departure_time', '>=', $depTimeCopy)
                ->orderBy('arrival_time', 'asc')
                ->get();
            if (sizeof($isPath) <= 0) {
                $isAdd = false;
                break;
            } else {
                ////////////////////////////////
                for ($j = 0; $j < count($isPath); $j++) {
                    ShortestPath::getSchedules($pathList, $places, $depTime)
                }
                $depTimeCopy = $isPath[0]->arrival_time;
                $oldDepTime = strtotime($isPath[0]->departure_time);
                $arrTime = strtotime($depTimeCopy);
                $time += ($arrTime - $oldDepTime);
            }
        }
        if ($isAdd) {
            $newPath = [
                'path' => $places,
                'time' => $time / 60
            ];
            array_push($pathList, $newPath);
        }
    }
}
