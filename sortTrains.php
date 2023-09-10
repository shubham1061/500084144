<?php
function getTrains() {
    global $accessToken;

    $headers = array(
        "Authorization: Bearer $accessToken"
    );

    $response = getRequest('http://20.244.56.144/train/trains', $headers);

    if ($response !== false && $response['http_code'] == 200) {
        $allTrains = json_decode($response['body'], true);
        $currentTime = time();
        // 12 hours in seconds
        $next12Hours = $currentTime + 12 * 3600; 

        // Filter trains departing in the next 12 hours (excluding the next 30 minutes)
        $reqTrains = array_filter($allTrains, function ($train) use ($currentTime, $next12Hours) {
            $depTime = strtotime($train['departureTime']['Hours'] . ':' . $train['departureTime']['Minutes']);
            return $depTime >= $currentTime + 30 * 60 && $depTime <= $next12Hours;
        });

        // Sort relevantTrains based on price, seat availability, and departure time with delays
 usort($reqTrains, function ($first, $second) {
    // Compare by price (sleeper and AC combined)
    $price1 = $first['price']['sleeper'] + $first['price']['AC'];
    $price2 = $second['price']['sleeper'] + $second['price']['AC'];
    if ($price1 != $price2) {
        return $price1 - $price2;
    }

    // Compare by seat availability (sleeper and AC combined)
    $seat1 = $first['seatsAvailable']['sleeper'] + $first['seatsAvailable']['AC'];
    $seat2 = $second['seatsAvailable']['sleeper'] + $second['seatsAvailable']['AC'];
    if ($seat1 != $seat2) {
        return $seat2 - $seat1;
    }

    // Compare by departure time with delays
    $depTime1 = strtotime($first['departureTime']['Hours'] . ':' . $first['departureTime']['Minutes']) + $first['delayedBy'] * 60;
    $depTime2 = strtotime($second['departureTime']['Hours'] . ':' . $second['departureTime']['Minutes']) + $second['delayedBy'] * 60;
    return $depTimeA - $depTimeB;
});

        // Prepare the API response
        $Response = array(
            "trains" => array(),
        );
        
        foreach ($reqTrains as $train) {
            $finalTrains = array(
                "trainName" => $train["trainName"],
                "trainNumber" => $train["trainNumber"],
                "departureTime" => sprintf( $train["departureTime"]["Hours"], $train["departureTime"]["Minutes"]),
                "seatsAvailable" => array(
                    "sleeper" => $train["seatsAvailable"]["sleeper"],
                    "AC" => $train["seatsAvailable"]["AC"],
                ),
                "price" => array(
                    "sleeper" => $train["price"]["sleeper"],
                    "AC" => $train["price"]["AC"],
                ),
                "delayedBy" => $train["delayedBy"],
            );
        
            $Response["trains"][] = $finalTrains;
        }
        
        // Send the JSON response
        header("Content-Type: application/json");
        echo json_encode($Response);

        return $finalTrains;
    } else {
        return array("error" => "Failed to fetch train data");
    }
}