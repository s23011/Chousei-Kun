<?php
  session_start();
  include("../model/access_db.php");

  #temparely
  $_SESSION['view_mode'] = False;

  # view part
  // $event_id = $_SESSION['event_id'];
  // $global_event_dates = $_SESSION['event_dates'];
  $_SESSION['form_action'] = 'post_form_modify.php';

  // $thistime_attendee_statues = array();
  // foreach($global_event_dates as $event_date){
  //   if(get_attendee_status_from_event_id_and_event_date($event_id,$event_date) == CODE_SUCCESS){
  //     $thistime_attendee_statues[$event_date] = $global_attendee_statues;
  //   }
  // }
  // $_SESSION['thistime_attendee_statues'] = $thistime_attendee_statues;
  

  # form part
  $att_name = $_GET['attendee_name'];
  $_SESSION['modifying_attendee_name'] = urldecode($att_name);

  $event_id = $_SESSION['event_id'];
  header( "Location: ../view_event.php?eid=".$event_id ); 
  // header( "Location: ../template/view_modify_attendee.php" ); 
  exit();
?>