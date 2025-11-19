<?php

    /**
     * Determines if a given latitude and longitude are inside a given fence area.
     *
     * @param float $lat The latitude of the point to check.
     * @param float $lon The longitude of the point to check.
     * @param array $fenceArea An array of latitude and longitude pairs defining the fence area.
     * @return bool True if the point is inside the fence area, false otherwise.
     */
function inside($lat, $lon, $fenceArea)
{
    $x = $lat;
    $y = $lon;

    $inside = false;
    for ($i = 0, $j = count($fenceArea) - 1; $i <  count($fenceArea); $j = $i++) {
        $xi = $fenceArea[$i]['lat'];
        $yi = $fenceArea[$i]['lon'];
        $xj = $fenceArea[$j]['lat'];
        $yj = $fenceArea[$j]['lon'];

        $intersect = (($yi > $y) != ($yj > $y))
            && ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi) + $xi);
        if ($intersect) $inside = !$inside;
    }

    return $inside;
}



/**
 * Calculates the distance between two points on the Earth's surface.
 *
 * @param float $lat1 The latitude of the first point.
 * @param float $lon1 The longitude of the first point.
 * @param float $lat2 The latitude of the second point.
 * @param float $lon2 The longitude of the second point.
 * @param string $unit The unit of measurement for the distance (default: "k").
 *                    Options: "k" for kilometers, "N" for nautical miles, other for miles.
 * @return float The distance between the two points in the specified unit of measurement.
 */
function get_distance($lat1, $lon1, $lat2, $lon2, $unit = "k")
{

    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    if ($unit == "k") {
        return ($miles * 1.609344);
    } else if ($unit == "N") {
        return ($miles * 0.8684);
    } else {
        return $miles;
    }
}

    /**
     * Calculates the distance between two points on the Earth's surface.
     *
     * @param float $lat1 The latitude of the first point.
     * @param float $lon1 The longitude of the first point.
     * @param float $lat2 The latitude of the second point.
     * @param float $lon2 The longitude of the second point.
     * @return float The distance between the two points in meters.
     */
function calculateDistance($lat1, $lon1, $lat2, $lon2)
{
    // Convert latitude and longitude from degrees to radians
    $lat1 = deg2rad($lat1);
    $lon1 = deg2rad($lon1);
    $lat2 = deg2rad($lat2);
    $lon2 = deg2rad($lon2);

    // Radius of the Earth in kilometers
    $radius = 6371;

    // Haversine formula
    $dlat = $lat2 - $lat1;
    $dlon = $lon2 - $lon1;

    $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    // Calculate the distance
    $distance = $radius * $c;

    return $distance * 1000; // Convert to meters
}
