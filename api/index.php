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

// variabler
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
    //print(json_encode($rows[0]["userID"]));
    








//Registrera användare
if($_SERVER["REQUEST_METHOD"] == "GET" && $reqestVars["RvL"] == "registrering"){
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
    print(json_encode($response));}


//Logga in
}elseif($_SERVER["REQUEST_METHOD"] == "GET" && $reqestVars["RvL"] == "loggin"){
if(empty($RC["result"])){
    $response = ["result"=>"this user does not exist"];
    print(json_encode($response));
}else{
    $getstmt1 = $mysqli->prepare("SELECT * FROM CmsDatabaserAnvandare WHERE userID=?");
    $getstmt1->bind_param("s",$anvandarnamn);
    $getstmt1->execute();
    $result = $getstmt1->get_result(); 
    $rows = [];
    while ($row = $result->fetch_assoc()){
        $rows[] = $row;
        $recived1=$rows[0]["user_level"]; 
        }

        
        if(password_verify($losenord, $rows[0]["pasword"])){
            if(!isset($reqestVars["session_key"])){
            //om sesion key inte finns
                $sha = sha1(rand()); 
                $mksesion = $mysqli->prepare("INSERT INTO CmsDatabaserSession (id,session_key,userID,customer_name,created_at) VALUES(?,?,?,?,?)");
                $mksesion->bind_param("isiss",$id,$sha,$id,$anvandarnamn,$updated);
                $mksesion->execute();
                //en ny session key skapas och skikas som response
                $getstmt2 = $mysqli->prepare("SELECT * FROM CmsDatabaserSession WHERE session_key ='".$sha."'");print_r($mysqli->error);
                //anars skikas den session key som gavs i post datan om den fins i databasen 
            }else{$getstmt2 = $mysqli->prepare("SELECT * FROM CmsDatabaserSession WHERE session_key ='".$reqestVars["session_key"]."'");} 
            // exekverar getstmt
            $getstmt2->execute();
            $result = $getstmt2->get_result(); 
            $rows = [];
            while ($row = $result->fetch_assoc()){
                $rows[] = $row;
            }$recived2=$rows[0]["session_key"];}
                $response = ["user_level"=>$recived1,"session_key"=>$recived2];
                print(json_encode($response));}
                


     
                



//method test och ip                
}elseif($_SERVER["REQUEST_METHOD"] == "GET" && isset($reqestVars["ip"])){
    $response = ["ip" => $_SERVER["REMOTE_ADDR"], "method" => $_SERVER["REQUEST_METHOD"], "sentReqest" => $reqestBody, "reqestVars" => $reqestVars];
    print(json_encode($response));      


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


    $getstmt->bind_param("s", $request_headers["apikey"]);

   



} 
?>
