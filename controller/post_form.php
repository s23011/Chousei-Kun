<?php
    session_start();
    include("./access_db.php");

    $event_id = $_SESSION['event_id'];
    $input_attendeeName = htmlspecialchars($_POST['attendeeName']);
    $event_dates = $_SESSION['event_dates'];
    $input_statuses = array();

    $input_comment = htmlspecialchars($_POST['comment']);

    if(!create_attendee_info($event_id, $input_attendeeName, $input_comment)){
        foreach($event_dates as $date){
            $input_statuses["$date"] = (int)htmlspecialchars($_POST['checkbox-'.$date.'']);
            if(!create_attendee_status($event_id, $input_attendeeName, $date, $input_statuses["$date"]));
        }
    }

    header( "Location: ./view_attendee.php" ); exit;
?>