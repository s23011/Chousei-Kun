<?php
include_once("../model/access_db.php");

session_start();

$redirect_location = "index.php"; // default page

if(isset($_SESSION["event_id"])){
    $event_id = $_SESSION['event_id'];

    if(get_event_info_from_event_id($event_id) == 0){
        $_SESSION["msg"]="function not complete yet:midify event";
    
        //redirect to view_event.php?eid=$event_id.php
        $redirect_location="view_modify_event.php?eid=".$event_id;
    }else{
        $_SESSION["msg"]=$global_db_msg;

        //redirect to view_modify_event.php
        $redirect_location="view_modify_event.php?eid=".$event_id;
    }
}else if(isset($_POST["event_name"])&isset($_POST["event_dates"])&isset($_POST["event_memo"])){
    $event_name = $_POST['event_name'];
    $event_dates = $_POST['event_dates'];
    $event_memo = $_POST['event_memo'];
     
    if(create_event($event_name,$event_dates,$event_memo) == 0){
        //if create event success ,and having data in session
        unset($_SESSION["event_name"]);
        unset($_SESSION["event_dates"]);
        unset($_SESSION["event_memo"]);

        //redirect to view_url.php?eid=$global_event_id.php
        $redirect_location = "view_modify_event.php?eid=".$global_event_id;
    }else{
        $_SESSION["msg"]=$global_db_msg;

        //keep data in session when create event failed
        //and return back to setup form fields by read session in check_event.php 
        $_SESSION["event_name"]=$event_name;
        $_SESSION["event_dates"]=$event_dates;
        $_SESSION["event_memo"]=$event_memo;

        //redirect to index.php
        $redirect_location = "index.php";
    }
}

//default
//redirect to index
header("Location: ../".$redirect_location);
exit();

?>