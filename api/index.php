<?php
include("../../../defPass.php");

$mysqli = new mysqli("mysql.arcada.fi","svahnkon",MYSQLPASS,"svahnkon");
if($mysqli->connect_error) die("MySQL Connect ERROR".$mysqli->connect_error);

//headers
$request_headers = apache_request_headers();
header("content-type: application/json");
header("Access-Control-Allow-Methods: POST,PUT,GET,OPTIONS,DELETE");


parse_str($_SERVER["QUERY_STRING"], $reqestVars);

//RECIVES JSON DATA
$reqestJson = file_get_contents("php://input");
$reqestBody = json_decode($reqestJson);

//temporära variabler
$anvandarnamn = $reqestVars["anvandarnamn"];
$losenord = $reqestVars["losenord"]; 
$id = "1";
$updated = date("Y-m-d H:i:s");

$check = $mysqli->prepare("SELECT userID FROM CmsDatabaserAnvandare WHERE userID ='".$anvandarnamn."'");
    $check->execute();
    $result = $check->get_result();
    $rows = [];
    while ($row = $result->fetch_assoc()){
    $rows[] = $row;
    }
    $RC["result"]=$rows;
    

if($_SERVER["REQUEST_METHOD"] == "POST" && $reqestVars["RvL"] == "registrering"){
    $pasword = password_hash($losenord, PASSWORD_DEFAULT);
    $ulvl="1"; 
    
if(empty($RC["result"])){
    $poststmt = $mysqli->prepare("INSERT INTO CmsDatabaserAnvandare (id,userID,username,pasword,user_level,uppdated_at) VALUES(?,?,?,?,?,?)");
    //print($id." ".$anvandarnamn." ".$pasword." ".$ulvl." ".$updated);
    $poststmt->bind_param("isssis",$id,$anvandarnamn,$anvandarnamn,$pasword,$ulvl,$updated);
    $poststmt->execute();
    $response = ["result"=>"you have ben registerd","u"=>$poststmt];
    //print_r($mysqli->error);
    print(json_encode($response));
}else{$response = ["result"=>"this user alredy exists"];
    //print(json_encode($response));
}


}elseif($_SERVER["REQUEST_METHOD"] == "POST" && $reqestVars["RvL"] == "loggin"){
if($check = $mysqli->prepare("SELECT * FROM CmsDatabaserAnvandare WHERE username =".$anvandarnamn) == false){
    $response = ["result"=>"this user does not exist"];
    print(json_encode($response));
}else{
    $getstmt1 = $mysqli->prepare("SELECT * FROM CmsDatabaserAnvandare WHERE username =".$anvandarnamn);
    $getstmt1->execute();
    $result = $getstmt1->get_result(); 
    $rows = [];
    while ($row = $result->fetch_assoc()){
        $rows[] = $row;
    }$recived1["result"]=$rows;
    

    if(password_verify($losenord, $recived1["pasword"])){
        if(!isset($reqestVars["session_key"])){
        //om sesion key inte finns
            $sha = sha1(random()); 
            $mksesion = $mysqli->prepare("INSERT  INTO CmsDatabaserSession (id,session_key,user_id,customer_name,created_at,) VALUES(?,?,?,?,?)");
            $mksesion->bind_param("issss",$id,$sha,$anvandarnamn,$anvandarnamn,$updated);
            $mksesion->execute();  
            $getstmt2 = $mysqli->prepare("SELECT * FROM CmsDatabaserSession WHERE session_key =".$sha);
        }
        else{$getstmt2 = $mysqli->prepare("SELECT * FROM CmsDatabaserSession WHERE session_key =".$reqestVars["session_key"]);} 
        $getstmt2->execute();
        $result = $getstmt2->get_result(); 
        $rows = [];
        while ($row = $result->fetch_assoc()){
            $rows[] = $row;
        }$recived2["result"]=$rows;

        $response = ["user_level"=>$recived1["user_level"],"session_key"=>$recived2["session_key"]];
        print(json_encode($response));
      
   

    }
}


}elseif($_SERVER["REQUEST_METHOD"] == "GET" && isset($reqestVars["session_key"])){    
    $getstmt = $mysqli->prepare("SELECT * FROM CmsDatabaserMessage WHERE chat_id = ?");
    $getstmt->bind_param("s", $reqestVars["chat_id"]);
    if(!$getstmt){die("ERROR: ".$mysqli->error);}
    $getstmt->execute();
    $reee = $getstmt->get_result(); 
    $rows = [];
    while ($row = $reee->fetch_assoc()){
        $rows[] = $row;
    }$response["reee"]=$rows;
    print(json_encode($response));



    $stmt->bind_param("s", $request_headers["apikey"]);

   






}elseif($_SERVER["REQUEST_METHOD"] == "GET" && isset($reqestVars["id"])){
    $response = ["ip" => $_SERVER["REMOTE_ADDR"], "method" => $_SERVER["REQUEST_METHOD"], "sentReqest" => $reqestBody, "reqestVars" => $reqestVars];
    print(json_encode($response));
}

?>
