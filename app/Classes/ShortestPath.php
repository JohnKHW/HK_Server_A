<?php

namespace App\Classes;

use App\Models\Place;
use Illuminate\Database\Eloquent\Builder;

class ShortestPath
{
    public static function findShortest(Place $place, $depTime, $target, $set, &$result, $time = 0, $route = [])
    {
        $color = [];
        $color[$place->id] = 0;
        $queue = [[$place, $depTime]];
        $stepNode = [[$place->id]];

        $bfsStep = 1;
        $tempTotal = 1;
        $tempEnd = 0;
        while ($queue != null) {
            // error_log(json_encode($color));
            $fromPlace = array_pop($queue)[0];
            $pathList = ShortestPath::getSchedules($fromPlace, $depTime);
            $tempTotal = sizeof($pathList);
            // error_log('tempTotal: ' . $tempTotal);

            for ($i = 0; $i < $tempTotal; $i++) {
                $newPlace = [$pathList[$i]->toPlace, $pathList[$i]->arrival_time];
                if (array_key_exists($newPlace[0]->id, $color)) {
                    continue;
                }

                array_push($queue, $newPlace);
                if ($newPlace[0]->id != $target) {
                    $color[$newPlace[0]->id] = $bfsStep;
                }
                $tempStepNode = [];
                error_log($i . ' ' . sizeof($stepNode[$bfsStep - 1]));
                for ($j = 0; $j < sizeof($stepNode[$bfsStep - 1]); $j++) {
                    // error_log(end($stepNode[$bfsStep - 1]));
                    // error_log($fromPlace->id);
                    // error_log('aaa ' . (end($stepNode[$bfsStep - 1]) == $fromPlace->id));
                    $endNode = $stepNode[$bfsStep - 1];
                    if (end($endNode) == $fromPlace->id) {
                        array_push($endNode, $newPlace[0]->id);
                        array_push($tempStepNode, $endNode);
                        //error_log('tempNode ' . json_encode($endNode));
                    }
                }
                // error_log('New');
                $stepNode[$bfsStep] = $tempStepNode;
                $tempEnd = $newPlace[0]->id;
                // error_log(json_encode($stepNode));
            }
            if ($fromPlace->id == $tempEnd) {
                $bfsStep++;
            }
        }
        // error_log(json_encode($color));
    }

    public static function getSchedules(Place $depPlace, $depTime)
    {
        return $depPlace
            ->fromSchedules()
            ->where('departure_time', '>=', $depTime)
            ->get();
    }
}
