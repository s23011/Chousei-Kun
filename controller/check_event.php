<?php 
include_once("../model/access_db.php");

session_start();

if(!isset($_SESSION["event_id"])){
    exit();
}


$event_id = $_SESSION['event_id'];

if(get_event_info_from_event_id($event_id) == 0){
    $event_info = [
        'event_name'=> $global_event_name,
        'event_memo'=>$global_event_memo,
        'event_dates'=>$global_event_dates];
    echo json_encode($event_info); 
}
?>