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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
</head>

<body>
<h1 id="header"> Welcome </h1>
<div id = "outer">
    <div id="inputform">
        <input type="email" class="field" id="email" placeholder="Email-id" >
        <br><br>
        <input type="password" class="field" id="password" placeholder="Password" >
        <br><br>
        <button id="loginButton" class="btn" onclick="loginAction()">Login</button>
        <br><br>
        <a class="linkcls" href="http://project1.com/signUpPage"><button id="signUpButton" class="btn">New User Sign up here</button>
        </a>
        <br><br>
        <button id="forgetButton" class="btn" onclick="forgotAction()">Forgot Password</button>
    </div>
</div>
<script>
    function loginAction() {
        var email = document.getElementById("email").value;
        var password = document.getElementById("password").value;
        if(email == null){
            alert("Please enter your email");
        }else if (password == null){
            alert("Please enter password");
        }else {
            var myKeyVals = { email : email, password : password };
            var saveData = $.ajax({
                type: 'POST',
                url: "http://project1.com/api/v1/loginAction",
                data: myKeyVals,
                dataType: "text",
                success: function (result) {
                    var result1 = JSON.parse(result);
                    console.log(result1);
                    if(result1['status'] == "ok") {
                        if (result1["error"] == null) {
                            var data = result1["data"];
                            var userid = data["id"];
                            location.href = "/home?userid="+userid;
                        } else {
                            alert(result1["error"]);
                        }
                    }
                }
            });
            saveData.error(function() { alert("Something went wrong"); });
        }
    }

    function forgotAction() {

    }


</script>
</body>
</html>
