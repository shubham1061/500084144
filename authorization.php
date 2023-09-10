<?php
include_once("register.php");
function getToken() {
    global $clientID, $clientSecret, $accessToken;
    $data = array(
        "companyName" => "Train Central",
        "ownerName" => "Ram",
        "clientID" => $clientID,
        "ownerEmail" => "ram@abc.edu",
        "rollNo" => "1",
        "clientSecret" => $clientSecret
    );

    $response = sendPostRequest('http://20.244.56.144/train/auth', $data);

    if ($response !== false && $response['http_code'] == 200) {
        $accessToken = json_decode($response['body'], true)['access_token'];
        return json_decode($response['body'], true);
    } else {
        return array("error" => "Token retrieval failed");
    }
}

