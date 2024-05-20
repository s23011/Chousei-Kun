<?php
    session_start();
    include("../model/access_db.php");

    $event_id = $_SESSION['event_id'];
    $input_attendeeName = $_POST['attendeeName'];
    $event_dates = $_SESSION['event_dates'];
    $input_statuses = array();

    $input_comment = $_POST['comment'];
    $old_attendee_name = $_SESSION['attName'];

    if(modify_attendee_info_from_event_id_and_attendee_name($event_id,$old_attendee_name,$input_attendeeName,$input_comment) == CODE_SUCCESS){
        foreach($event_dates as $date){
            $input_statuses["$date"] = (int)htmlspecialchars($_POST['checkbox-'.$date.'']);
            if(!modify_attendee_status_from_event_id_and_attendee_name($event_id,$old_attendee_name,$input_attendeeName,$date, $input_statuses["$date"]));
        }
    }

    header( "Location: ../controller/view_attendee.php" ); exit;
?>