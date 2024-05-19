<?php
include_once("../model/access_db.php");

session_start();

$redirect_location = "index.php"; // default page

if(!isset($_POST["event_name"])
    || !isset($_POST["event_dates"])
    || !isset($_POST["event_memo"])){

    http_response_code(405);
    header("Location: ../".$redirect_location);
    exit();
}

$event_name = $_POST['event_name'];
$event_dates = $_POST['event_dates'];
$event_memo = $_POST['event_memo'];

$event_dates = rtrim($event_dates );

//modify event when holding a event id, otherwise create a new event.
if(isset($_SESSION["event_id"])){
    $event_id = $_SESSION['event_id'];

    if(get_event_info_from_event_id($event_id) == CODE_ERROR){
        http_response_code(405);
        header("Location: ../".$redirect_location);
        exit();
    }

    if(modify_event($event_id,$event_name,$event_dates,$event_memo) == CODE_SUCCESS){
        add_msg("Modify event info in success.",CODE_SUCCESS);
        //redirect to view event page
        $redirect_location="view_event.php?eid=".$event_id;
    }else{
        //return modify event page
        $redirect_location="view_modify_event.php?eid=".$event_id;
    }
}else{     
    if(create_event($event_name,$event_dates,$event_memo) == CODE_SUCCESS){
        add_msg("Create event info in success.",CODE_SUCCESS);

        //unset data in session when success
        unset($_SESSION["event_name"]);
        unset($_SESSION["event_dates"]);
        unset($_SESSION["event_memo"]);

        //redirect to view url page
        $redirect_location = "view_url.php?eid=".$global_event_id;
    }else{
        //keep data in session until success
        //if have any problem, return back and setup form fields by read session in check_event.php 
        $_SESSION["event_name"]=$event_name;
        $_SESSION["event_dates"]=$event_dates;
        $_SESSION["event_memo"]=$event_memo;

        //return to default page for create event
        $redirect_location = "index.php";
    }
}

header("Location: ../".$redirect_location);
exit();

?>