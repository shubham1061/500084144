<?php
$clientID: "c690151a-a515-48f5-b2be-20ad70ccc733";
$clientSecret: "LRzGpzDszPvweGxR";
$accessToken = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE2OTQzMjkzNjUsImNvbXBhbnlOYW1lIjoiVHJhaW4gc3BlY2lhbCIsImNsaWVudElEIjoiYzY5MDE1MWEtYTUxNS00OGY1LWIyYmUtMjBhZDcwY2NjNzMzIiwib3duZXJOYW1lIjoiIiwib3duZXJFbWFpbCI6IiIsInJvbGxObyI6IjUwMDA4NDE0NCJ9.3MhLa6RyaPUL0bUnJJjQLsJWLN-ZfkBWuWUHDw3qj9k"; 

// Function to register your company with John Doe Railway Server
function register() {
    $data = array(
        "companyName"=> "Train special", 
        "ownerName"=> "shubham",
        "rollNo"=> "500084144",
        "ownerEmail"=> "500084144@stu.upes.ac.in",
        "accessCode"=> "JnNPGs"
    );

    $response = sendPostRequest('http://20.244.56.144/train/register', $data);

    if ($response !== false && $response['http_code'] == 200) {
        return json_decode($response['body'], true);
    } else {
        return array("error" => "Company registration failed");
    }
}
