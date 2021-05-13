<?php
include("../../../defPass.php");

$mysqli = new mysqli("mysql.arcada.fi","svahnkon",MYSQLPASS,"svahnkon");
if($mysqli->connect_error) die("MySQL Connect ERROR".$mysqli->connect_error);

//headers
header("content-type: application/json");
header("Access-Control-Allow-Methods: POST,PUT,GET,OPTIONS,DELETE");


parse_str($_SERVER["QUERY_STRING"], $reqestVars);

//RECIVES JSON DATA
$reqestJson = file_get_contents("php://input");
$reqestBody = json_decode($reqestJson);


if($_SERVER["REQUEST_METHOD"] == "POST" && isset($reqestVars["registrering"]){



}elseif($_SERVER["REQUEST_METHOD"] == "POST" && isset($reqestVars["loggin"])){



}elseif($_SERVER["REQUEST_METHOD"] == "GET" &! isset($reqestVars["id"])){
    $chat_id =1;
    $getstmt = $mysqli->prepare("SELECT * FROM CmsDatabaserMessage WHERE chat_id =".$chat_id);
    if(!$getstmt){die("ERROR: ".$mysqli->error);}
    $getstmt->execute();
    $result = $getstmt->get_result(); 
    $rows = [];
    while ($row = $result->fetch_assoc()){
        $rows[] = $row;
    }$response["result"]=$rows;
    print(json_encode($response));


}elseif($_SERVER["REQUEST_METHOD"] == "GET" && isset($reqestVars["id"])){
    $resultIP = ["ip" => $_SERVER["REMOTE_ADDR"], "method" => $_SERVER["REQUEST_METHOD"], "sentReqest" => $reqestBody, "reqestVars" => $reqestVars];
    print(json_encode($resultIP));
}

?>
