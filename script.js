$(document).ready(function () { 
    console.log("js works");
    getIp();  
    
});

$(function (){
    $.ajax({
        type: "GET",
        URL: "https://cgi.arcada.fi/~svahnkon/wdbocms-projekt-1-konrad-s/api/index.php/",
        success: function(data){
            console.log(data);
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
        error: function(){console.log("error1");}
    });
        
}

function loggin() {
    var anvandarnamn = document.gg.anvandarnamn.value;
    var losenord = document.gg.losenord.value;
    $.ajax({
        type: "GET",
        url:"https://cgi.arcada.fi/~svahnkon/wdbocms-projekt-1-konrad-s/api/", 
        headers:{"Content-Type": "application/json"},
        data: {"RvL": "loggin", "losenord": losenord, "anvandarnamn": anvandarnamn},


        success: function(data) {
        console.log(data);
        console.log("respons2");
        $("#registrera").html(data);
        },
        error: function(){console.log("error2");}
});
};

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
        console.log("respons3");
        $("#registrera").html(data);
        },
        error: function(){console.log("error3");}
});   
};