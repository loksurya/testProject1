<?php

/**
 * Created by PhpStorm.
 * User: lokendra.surya
 * Date: 7/15/16
 * Time: 7:01 PM
 */
class ApiFunctions
{

    public $redisConnection;

    public function processAPI($endPoint, $data)
    {
        $conn = new Redis();
        $conn->connect('127.0.0.1', 6379);
        $conn->select(1);
        $this->redisConnection = $conn;
        if (method_exists($this, $endPoint)) {
            $this->$endPoint($data);
        } else {
            echo " endPoint $endPoint does not exists";
        }

    }

    function getUserDetail(){
        $data = $_GET;
        echo json_encode($this->getUserInfo($data["email"]));
    }

    function getUserInfo($email){
        $redis = $this->redisConnection;
        $output = array();
        $count = $redis->hGet("emailids",$email);
        if ($count != 0){
            $userkey = "user:$count-details";
            $resultdata = $redis->hGetAll($userkey);
            $output = $resultdata;
        }
        return $output;
    }

    function postUserDetails(){
        $redis = $this->redisConnection;
        $data = $_POST;
        $useremail = ($data["email"]);
        $password = $data["password"];
        $hasspass = hash('sha512',$password);
        $data["password"] = $hasspass;
        $output = array();
        $output["status"] = "ok";
        $result = $redis->hGet("emailids",$useremail);
        if ($result == 0){
            $count = $redis->incr("usercount");
            $timestamp = time();
            $countstr = "$count";
            $redis->zAdd("users",$timestamp,$countstr);
            $redis->hSet("emailids", $useremail, $countstr);
            $userkey = "user:$count-details";
            $data["id"] = $countstr;
            $status = $redis->hMset($userkey, $data);
            if ($status != 0){
                $resultdata = $redis->hGetAll($userkey);
                $output["data"] = $resultdata;
            }else{
                $output["error"] = "Some error";
            }
        }else{
            $output["error"] = "Sorry email is already registered";
        }
        echo json_encode($output);
    }

    function loginAction(){
        $data = $_POST;
        $useremail = $data["email"];
        $password = $data["password"];
        $hasspass = hash('sha512',$password);
        $output = array();
        $output["status"] = "ok";
        $resultdata = $this->getUserInfo($useremail);
        if (count($resultdata) > 0){
            $resultpassword = $resultdata["password"];
            if ($hasspass == $resultpassword){
                $output["data"] = $resultdata;
            }else{
                $output["error"] = "Sorry password did not match";
            }
        }else{
            $output["error"] = "Sorry email is not registered";
        }
        echo json_encode($output);
    }

    function getUsersList(){
        $redis = $this->redisConnection;
        $input = $_GET;
        $next = $input["next"];
        $userid = $input["userid"];
        $minScore = ($next-1) * 10  ;
        $maxScore = $next * 10 - 1 ;
        $output = array();
        $output["status"] = "ok";
        $result = $redis->zRange("users", $minScore, $maxScore);
        if ($result == 0){
            $output["error"] = "Some error";
        }else{
            $respone = array();
            for ($i = 0 ;$i < count($result); $i++){
                $count = $result[$i];
                $userkey = "user:$count-details";
                $userdata = $redis->hGetAll($userkey);
                if ($userid != $userdata["id"]) {
                    $otheruserid = $userdata["id"];
                    $userkey = "user:$userid:following";
                    $result1 = $redis->zScore($userkey,$otheruserid);
                    if ($result1 > 0){
                        $userdata["following"] = true ;
                    }else{
                        $userdata["following"] = false;
                    }
                    $respone [$i] = $userdata;
                }
            }
            $output["data"] = $respone;
        }
        echo json_encode($output);
    }

    function toggleFollowAction(){
        $redis = $this->redisConnection;
        $output = array();
        $output["status"] = "ok";
        $data = $_POST;
        $userid = $data["userid"];
        $otherid = $data["otheruserid"];
        $userkey = "user:$userid:following";
        $otheruserkey = "user:$otherid:followers";
        $timestamp = time();
        $result1 = $redis->zScore($userkey,$otherid);
        if ($result1 == 0){
            $result3 = $redis->zAdd($userkey, $timestamp, $otherid);
            $result2 = $redis->zAdd($otheruserkey, $timestamp, $userid);
            if ($result3 == 0 || $result2 == 0){
                $output["error"] = "Some error";
            }else
                $output["following"] = true;
        }else{
            $result3 = $redis->zRem($userkey,$otherid);
            $result2 = $redis->zRem($otheruserkey,$userid);
            if ($result3 == 0 || $result2 == 0){
                $output["error"] = "Some error";
            }else
                $output["following"] = false;
        }
        echo json_encode($output);
    }

    function showMessage(){
        $data = $_POST;
        return json_encode($data);
    }

}