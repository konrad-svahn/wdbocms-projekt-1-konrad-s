<?php
include("../../../defPass.php");

$mysqli = new mysqli("mysql.arcada.fi","svahnkon",MYSQLPASS,"svahnkon");
if($mysqli->connect_error) die("MySQL Connect ERROR".$mysqli->connect_error);


header("content-type: application/json");
header("Access-Control-Allow-Methods: POST,PUT,GET,OPTIONS,DELETE");

parse_str($_SERVER["QUERY_STRING"], $reqestVars);

$reqestJson = file_get_contents("php://input");
$reqestBody = json_decode($reqestJson);

$result = ["ip" => $_SERVER["REMOTE_ADDR"], "method" => $_SERVER["REQUEST_METHOD"], "sentReqest" => $reqestBody, "reqestVars" => $reqestVars];

print(json_encode($result));

if($_SERVER["REQUEST_METHOD"] == "POST"){

}elseif($_SERVER["REQUEST_METHOD"] == "GET" &! isset($reqestVars["id"])){

    $getstmt = $mysqli -> prepare("SELECT * FROM an_message(cms&databaser)")
}elseif($_SERVER["REQUEST_METHOD"] == "GET" && isset($reqestVars["id"])){}

?>
