<?php
header("content-type: application/json");

parse_str($_SERVER["QUERY_STRING"], $reqestVars);

$reqestJson = file_get_contents("php://input");
$reqestBody = json_decode($reqestJson);

$result = ["ip" => $_SERVER["REMOTE_ADDR"], "method" => $_SERVER["REQUEST_METHOD"], "sentReqest" => $reqestBody, "reqestVars" => $reqestVars];

print(json_encode($result));

if($_SERVER["REQUEST_METHOD"] == "POST"){

}elseif($_SERVER["REQUEST_METHOD"] == "GET" &! isset($reqestVars["id"])){

}elseif($_SERVER["REQUEST_METHOD"] == "GET" && isset($reqestVars["id"])){}

?>