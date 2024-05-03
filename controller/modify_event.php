<?php
include_once("../model/access_db.php");

session_start();

if(isset($_SESSION["event_id"])){
    $event_id = $_SESSION['event_id'];

    if(get_event_info_from_event_id($event_id) == 0){
        //modify event
        $_SESSION["msg"]="function not complete yet:midify event";
    }else{
        //error
        $_SESSION["msg"]=$global_db_msg;
    }
}else{
    //create event
    $event_name = $_POST['event_name'];
    $event_dates = $_POST['event_dates'];
    $event_memo = $_POST['event_memo'];
    
    if(create_event($event_name,$event_dates,$event_memo) == 0){
        $_SESSION["event_id"]=$global_event_id;

        //redirect to view url

    }else{
        //error
        $_SESSION["msg"]=$global_db_msg;
    }
}

header("Location: ../index.php");
exit();
?>