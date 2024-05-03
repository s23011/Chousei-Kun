<?php 
include_once("../model/access_db.php");

session_start();

if(!isset($_SESSION["event_id"])){
    exit();
}

if(!isset($_SESSION["attendee_name"])){
    exit();
}

$event_id = $_SESSION['event_id'];
$attendee_name = $_SESSION['attendee_name'];

if(get_attendee_statuses_from_event_id_and_attendee_name($event_id,$attendee_name) == 0){
    $attendee_statuses = [
        'dates'=> $global_attendee_dates,
        'statuses'=>$global_attendee_statuses];
    echo json_encode($attendee_statuses); 
}
?>