$(document).ready(function () { 
    console.log("js works");
    getIp();  
    registrera();
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

    $.get("https://cgi.arcada.fi/~svahnkon/wdbocms-projekt-1-konrad-s/api/", function(data) {
        console.log(data.ip);
        
        $("#ip").html(data.ip);
    });
};

function loggin() {
    var anvandarnamn = document.gg.anvandarnamn.value;
    var losenord = document.gg.losenord.value;
    $.post("https://cgi.arcada.fi/~svahnkon/wdbocms-projekt-1-konrad-s/api/", {RvL: "loggin", losenord: losenord, anvandarnamn: anvandarnamn} ,function(data) {
        console.log(data);
    
        $("#registrera").html(data);
    });
};

function registrera() {
    var anvandarnamn = document.gg.anvandarnamn.value;
    var losenord = document.gg.losenord.value;
    $.post("https://cgi.arcada.fi/~svahnkon/wdbocms-projekt-1-konrad-s/api/", {RvL: "registrering", losenord: losenord, anvandarnamn: anvandarnamn} ,function(data) {
        console.log(data);
        
        $("#registrera").html(data);
    });
};