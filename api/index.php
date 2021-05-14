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

//temporära variabler
$chat_id =1;
$anvandarnamn ="APIna";
$losenord ="123"; 
$id = "1";

$updated = parse_str(date("H:i Y-m-d"));

if($_SERVER["REQUEST_METHOD"] == "POST" && $reqestVars["RvL"] == "registrering"){
    //password_hash returnerar false det verkar inte att finas någån klar orsak för varför
    $pasword = password_hash($losenord, PASSWORD_DEFAULT);
    $ulvl="1"; 
if($check = $mysqli->prepare("SELECT * FROM CmsDatabaserAnvandare WHERE username =".$anvandarnamn) == false){
    $poststmt = $mysqli->prepare("INSERT INTO CmsDatabaserAnvandare(id,user_id,username,pasword,user_level,uppdated_at) VALUES(?,?,?,?,?,?)");print(json_encode($response));
    //print($id." ".$anvandarnamn." ".$pasword." ".$ulvl." ".$updated);
    $poststmt->bind_param("isssis",$id,$anvandarnamn,$anvandarnamn,$pasword,$ulvl,$updated);
    $poststmt->execute();
    $response = ["result"=>"you have ben registerd","u"=>$poststmt];
    //print(json_encode($response));
}else{$response = ["result"=>"this user alredy exists"];
    print(json_encode($response));}


}elseif($_SERVER["REQUEST_METHOD"] == "POST" && $reqestVars["RvL"] == "loggin"){
if($check = $mysqli->prepare("SELECT * FROM CmsDatabaserAnvandare WHERE username =".$anvandarnamn) == false){
    $response = ["result"=>"this user does not exist"];
    print(json_encode($response));
}else{
    $poststmt = $mysqli->prepare("SELECT * FROM CmsDatabaserAnvandare WHERE username =".$anvandarnamn);
    $poststmt->execute();
    $result = $poststmt->get_result(); 
    $rows = [];
    while ($row = $result->fetch_assoc()){
        $rows[] = $row;
    }$recived["result"]=$rows;


    if(password_verify($losenord, $recived["pasword"])){
        if(!isset($reqestVars["session_key"])){
        //om sesion key inte finns
            $sha = sha1(random()); 
            $mksesion = $mysqli->prepare("INSERT  INTO CmsDatabaserAnvandare (id,session_key,user_id,customer_name,created_at,) VALUES(?,?,?,?,?)");
            $mksesion->bind_param("issss",$id,$sha,$anvandarnamn,$anvandarnamn,$updated);
            $mksesion->execute();

        }
    if($recived["user_leve"] =="1"){/*om du inte är admin*/ }
    elseif($recived["user_leve"] =="2"){/*om du är admin*/}
   

    }
}


}elseif($_SERVER["REQUEST_METHOD"] == "GET" &! isset($reqestVars["id"])){    
    $getstmt = $mysqli->prepare("SELECT * FROM CmsDatabaserMessage WHERE chat_id =".$chat_id);
    if(!$getstmt){die("ERROR: ".$mysqli->error);}
    $getstmt->execute();
    $reee = $getstmt->get_result(); 
    $rows = [];
    while ($row = $reee->fetch_assoc()){
        $rows[] = $row;
    }$response["reee"]=$rows;
    print(json_encode($response));


}elseif($_SERVER["REQUEST_METHOD"] == "GET" && isset($reqestVars["id"])){
    $response = ["ip" => $_SERVER["REMOTE_ADDR"], "method" => $_SERVER["REQUEST_METHOD"], "sentReqest" => $reqestBody, "reqestVars" => $reqestVars];
    print(json_encode($response));
}

?>
