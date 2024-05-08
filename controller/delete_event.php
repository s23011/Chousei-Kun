<?php
include_once("../model/access_db.php");

session_start();

if(!isset($_SESSION["event_id"])){
    http_response_code(405);
    header("Location: ../index.php");
    exit();
}
$event_id = $_SESSION["event_id"];

// //test by get method
// if(!isset($_GET["eid"])){
//     http_response_code(405);
//     exit();
// }
// $event_id = $_GET['eid'];

if(get_event_info_from_event_id($event_id) == CODE_ERROR){
    $_SESSION["msg"]=$global_db_msg;

    header("Location: ../index.php");
    exit();
}

if(delete_event_from_event_id($event_id) == CODE_SUCCESS){
    $_SESSION["msg"]="delete event success.eid=".$event_id;

    header("Location: ../index.php");
    exit();
}else{
    $_SESSION["msg"]=$global_db_msg;

    header("Location: ../view_modify_event.php?eid=".$event_id);
    exit();
}

