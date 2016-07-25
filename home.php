<?php
/**
 * Created by PhpStorm.
 * User: lokendra.surya
 * Date: 7/4/16
 * Time: 5:48 PM
 */

$data = $_GET ;
$userid = $data["userid"];

?>

<!DOCTYPE html>
<html>

<head>
    <link rel="icon" href="//s.ytimg.com/yts/img/favicon_32-vfl8NGn4k.png">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
    <meta http-equiv="Pragma" content="no-cache"/>
    <meta http-equiv="Expires" content="0"/>
    <link rel="stylesheet" type="text/css" href="/homeStyleSheet.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script>
        function loadTable(tableId, data) {
            //$('#' + tableId).empty(); //not really necessary
            var rows = '';
            $.each(data, function(index, item) {
                var row = '<div class="listdiv">';
                var firstName = item['firstname'];
                var lastName = item['lastname'];
                var name = firstName.concat(" "+lastName);
                var objectid = item['id'];
                //$.each(fields, function(index, field) {
                    row += name;
                //});
                var followval = item['following'];
                var buttonText = "";
                if(followval){
                    buttonText = "Following";
                }else {
                    buttonText = "Follow";
                }
                row += '<button class="followbutton" onclick="followAction(this.id)" id='+objectid+'>' + buttonText + '</button>';
                rows += row + '</div>';
            });
            $('#' + tableId).html(rows);
        }
        function followAction(id) {
            var myKeyVals = { userid : userid, otheruserid : id}
            var saveData = $.ajax({
                type: 'POST',
                url: "http://project1.com/api/v1/toggleFollowAction",
                data: myKeyVals,
                dataType: "text",
                success: function (result) {
                    console.log(result);
                    var result1 = JSON.parse(result);
                    console.log(result);
                    if (result1['status'] == "ok") {
                        console.log(userdata);
                        var str = ""+id;
                        console.log(str);
                        var userobj = userdata[str];
                        console.log(userobj);
                        /*if [userobj['id'] == userid]{
                            userobj['following'] = result1['following'];
                        }*/
                        userdata[str] = userobj;
                        loadTable("data-table", userdata);
                    }
                }
            });
            saveData.error(function() { alert("Something went wrong"); });
        }
        $(document).ready(function () {
            console.log("called");
        });
        </script>

</head>

<body>
    <h1> Welcome here </h1>
    <p> Hello user welocme to your home page here are some users list to follow </p>
    <p id="userinfo"> </p>
    <ul id="data-table">
        <li>There are no items...</li>
    </ul>
</body>

<script>
    var userid = "<?php echo $userid; ?>";
    var userdata;
    console.log(userid);
        var myKeyVals = {  next : 0, userid : userid };
        var saveData = $.ajax({
            type: 'GET',
            url: "http://project1.com/api/v1/getUsersList",
            data: myKeyVals,
            dataType: "text",
            success: function (result) {
                console.log(result);
                var result1 = JSON.parse(result);
                console.log(result1);
                if(result1['status'] == "ok") {
                    if (result1["error"] == null) {
                        var data = result1["data"];
                        console.log(data);
                        loadTable("data-table",data);
                        userdata = data;
                    } else {
                        alert(result1["error"]);
                    }
                }
            }
        });
        saveData.error(function() { alert("Something went wrong"); });
</script>

</html>