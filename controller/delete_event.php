<?php
include_once("../model/access_db.php");

session_start();

if(!isset($_SESSION["event_id"])){
    http_response_code(405);
    header("Location: ../index.php");
    exit();
}
$event_id = $_SESSION["event_id"];

if(get_event_info_from_event_id($event_id) == CODE_ERROR){

    header("Location: ../index.php");
    exit();
}

if(delete_event_from_event_id($event_id) == CODE_SUCCESS){
    add_msg("Delete event in success.(eid=".$event_id.")",CODE_SUCCESS);

    header("Location: ../index.php");
    exit();
}else{

    header("Location: ../view_modify_event.php?eid=".$event_id);
    exit();
}

