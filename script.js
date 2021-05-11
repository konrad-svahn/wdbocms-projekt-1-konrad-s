$(document).ready(function () { 
    getIp();
    console.log("js works");
});

function getIp() {

    $.get("https://cgi.arcada.fi/~svahnkon/wdbocms-projekt-1-konrad-s/api/", function(data) {
        console.log(data.ip);
        
        $("#ip").html(data.ip);
    });
}

$(function(){
    $.ajax({
        type: "GET",
        URL: "https://cgi.arcada.fi/~svahnkon/wdbocms-projekt-1-konrad-s/api/index.php/",
        success: function(data){
            console.log(data);
        }.
        error: function()
    })
})
