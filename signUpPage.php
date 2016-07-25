<?php
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
    <meta http-equiv="Pragma" content="no-cache"/>
    <meta http-equiv="Expires" content="0"/>
    <link rel="icon" href="//s.ytimg.com/yts/img/favicon_32-vfl8NGn4k.png">
    <link rel="stylesheet" type="text/css" href="/stylesheet.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    
</head>

<body>
<h1 id="header"> Welcome </h1>
<div id = "outer">
    <div id="inputform">
        <input type="text" class="field" id="firstname" placeholder="First Name">
        <br><br>
        <input type="text" class="field" id="lastname" placeholder="Last Name">
        <br><br>
        <input type="email" class="field" id="email" placeholder="Email-id" >
        <br><br>
        <input type="password" class="field" id="password" placeholder="Password" >
        <br><br>
        <button id="signUpButton" class="btn" onclick="signUpAction()">Sign up</button>
    </div>
</div>
<script>
    function signUpAction() {
        var firstName = document.getElementById("firstname").value;
        var lastName = document.getElementById("lastname").value;
        var email = document.getElementById("email").value;
        var password = document.getElementById("password").value;

        console.log(firstName);
        console.log(lastName);
        console.log(email);

        var myKeyVals = { firstname : firstName, lastname : lastName, email : email, password : password }

        var saveData = $.ajax({
            type: 'POST',
            url: "http://project1.com/api/v1/postUserDetails",
            data: myKeyVals,
            dataType: "text",
            success: function (result) {
                console.log(result);
                result = JSON.parse(result);
                console.log(result);
                if (result['status'] == "ok") {
                    if (result["error"] == null) {
                        var data = result["data"];
                        var userid = data["id"];
                        location.href = "/home?userid="+userid;
                    } else {
                        alert(result["error"]);
                    }
                }
            }
        });
        saveData.error(function() { alert("Something went wrong"); });
    }


</script>
</body>
</html>

