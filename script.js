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

    $.get("https://cgi.arcada.fi/~svahnkon/wdbocms-projekt-1-konrad-s/api/", function(data) {
        console.log(data);
        
        $("#ip").html(data.ip);
    });
};

function loggin() {};
function registrera() {
    
};