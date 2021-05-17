var chatmeddelanden =[];
$(document).ready(function () { 
    console.log("js works");
    getIp();  
    logginStep2();
});

$(function (){
    $.ajax({
        type: "GET",
        URL: "https://cgi.arcada.fi/~svahnkon/wdbocms-projekt-1-konrad-s/api/index.php/",
        success: function(data){
            //console.log(data);
        },
        error: function(){}
    });
});

function getIp() {

    $.ajax({
        type: "GET",
        url:"https://cgi.arcada.fi/~svahnkon/wdbocms-projekt-1-konrad-s/api/", 
        headers:{"Content-Type": "application/json"},
        data: {"ip":"getip"},


        success: function(data) {
            console.log(data.ip);
            $("#ip").html(data.ip);
            console.log("respons1");
        },
        error: function(){console.log("error0");}
    });
        
}

function loggin() {
    var anvandarnamn = document.gg.anvandarnamn.value;
    var losenord = document.gg.losenord.value;
    if(localStorage.getItem("session_key") == null){
        $.ajax({
            type: "GET",
            url:"https://cgi.arcada.fi/~svahnkon/wdbocms-projekt-1-konrad-s/api/", 
            headers:{"Content-Type": "application/json"},
            data: {"RvL": "loggin", "losenord": losenord, "anvandarnamn": anvandarnamn},
    
    
            success: function(data) {
            console.log(data);
            $("#registrera").html(data);
            localStorage.setItem("session_key", data["session_key"]);
            localStorage.setItem("user_level", data["user_level"]); 
            localStorage.setItem("anvandarnamn", anvandarnamn);
            logginStep2();
            },
            error: function(){console.log("error1");}
    });}
    else{
    $.ajax({
        type: "GET",
        url:"https://cgi.arcada.fi/~svahnkon/wdbocms-projekt-1-konrad-s/api/", 
        headers:{"Content-Type": "application/json"},
        data: {"RvL": "loggin", "losenord": losenord, "anvandarnamn": anvandarnamn, "session_key": localStorage.getItem("session_key")},


        success: function(data) {
        console.log(data);
        $("#registrera").html(data);
        localStorage.setItem("session_key", data["session_key"]);
        localStorage.setItem("user_level", data["user_level"]);
        localStorage.setItem("anvandarnamn", anvandarnamn);
        logginStep2();
        },
        error: function(){console.log("error2");}
});}


};





function logginStep2(){
    if(localStorage.getItem("session_key") != null){
    //document.getElementById("regOutput").innerHTML =  localStorage.getItem("session_key");
    document.getElementById("vannish1").innerHTML = "";}
    else{document.getElementById("vannish2").innerHTML = "";}
    
    
    if(localStorage.getItem("user_level") == 1){
        var med = recive(localStorage.getItem("session_key"));
        var print = "";
        console.log(med);
        for(var i = 0; i < med.length; i++){
            print += med[i].author_id+": "+med[i].mesage+"<br>"
        } 
        document.getElementById("medelandena").innerHTML = print;
    }else if(localStorage.getItem("user_level") == 2){
        
        $.ajax({
            type: "GET",
            url: "https://cgi.arcada.fi/~svahnkon/wdbocms-projekt-1-konrad-s/api/", 
            headers:{"Content-Type": "application/json"},
            data: {"getall": "getall"},
    
    
            success:function(data) {
                console.log(data);
                var print = "<form>";
                for(var i = 0; i < data.length; i++){
                    print += '<input type="button" name="'+data[i].session_key+'" value="'+data[i].customer_name+'" onclick="goto(\''+data[i].session_key+'\')"><br>';
                } print += "</form>";
                document.getElementById("grid-container").innerHTML = print;
        
            },
            error: function(){console.log("error3");}
    });}}




    function goto(key){
       localStorage.setItem("session_key", key);
       var med = recive(localStorage.getItem("session_key"));
       var print = "";
       console.log(med);
       for(var i = 0; i < med.length; i++){
           print += med[i].author_id+": "+med[i].mesage+"<br>"
       } 
       document.getElementById("medelandena").innerHTML = print; 
    }




function registrera() {
    var anvandarnamn = document.gg.anvandarnamn.value;
    var losenord = document.gg.losenord.value;
    $.ajax({
        type: "GET",
        url: "https://cgi.arcada.fi/~svahnkon/wdbocms-projekt-1-konrad-s/api/", 
        headers:{"Content-Type": "application/json"},
        data: {"RvL": "registrering", "losenord": losenord, "anvandarnamn": anvandarnamn},


        success:function(data) {
        console.log(data);
        $("#result").html(data);
        document.getElementById("regOutput").innerHTML =  data["result"];
        },
        error: function(){console.log("error3");}
});   
};





function send(){
    var message = document.hh.skrivfalt.value;
    if(message != ""){
    $.ajax({
    type: "GET",
    url:"https://cgi.arcada.fi/~svahnkon/wdbocms-projekt-1-konrad-s/api/", 
    headers:{"Content-Type": "application/json"},
    data: {"session_key": localStorage.getItem("session_key"), "anvandarnamn": localStorage.getItem("anvandarnamn"), "message": message},


    success: function(data) {
    console.log(data);
    $("#registrera").html(data);
   
    },
    error: function(){console.log("error4");
}})}else{console.log("inget skrivet");}
//var chatmeddelanden = recive(localStorage.getItem("session_key"));
//console.log(chatmeddelanden);
logginStep2();
}

function recive(input){
    $.ajax({
        type: "GET",
        url:"https://cgi.arcada.fi/~svahnkon/wdbocms-projekt-1-konrad-s/api/", 
        headers:{"Content-Type": "application/json"},
        data: {"session_key": input},


        success: function(data) {
        var sort = []
        for(var i = 0; i < data.reee.length;i++){
        var mkdate = [];
        mkdate ["chat_id"] = data.reee[i].chat_id;;
        mkdate ["author_id"] = data.reee[i].author_id;
        var date = new Date(data.reee[i].created_at);
        mkdate ["created_at"] = date.getTime();
        mkdate ["mesage"] = data.reee[i].mesage;
        sort[i] = mkdate;
        }

        var sorted = sort.sort((a,b) => b.created_at - a.created_at);
        chatmeddelanden = sorted;
        },
        error: function(){console.log("error5");}  
});

return chatmeddelanden;
}