<?php
  session_start();
  include("./access_db.php");

  #temparely
  $_SESSION['event_id'] = 0;
  $_SESSION['isCreator'] = True;
  $_SESSION['view_mode'] = False;
  $_SESSION['modify_event_link'] = "#";
  $_SESSION['create_attendee_link'] = "./create_attendee.php";
  $_SESSION['form_action'] = "post_form_modify.php";

  # view part
  $event_id = $_SESSION['event_id'];
  if(!get_event_info_from_event_id($event_id)) {
    $_SESSION['title'] = $global_event_name;
    $_SESSION['memo'] = $global_event_memo;
    $_SESSION['event_dates'] = $global_event_dates;
  }

  if(!get_attendee_info_from_event_id($event_id)) {
    $_SESSION['attendee_num'] = $global_attendee_num;
    $_SESSION['attendee_names'] = $global_attendee_names;
    $_SESSION['attendee_comments'] = $global_attendee_comments;
  }
  if($_SESSION['isCreator']){
    $title_below = 'あなたが幹事のイベントです。';
  }else{
    $title_below = '「出欠を入力する」ボタンから出欠を入力しましょう。';
  }
  $_SESSION['total_atten'] = "解答者数{$global_attendee_num}人、{$title_below}";

  $thistime_attendee_statues = array();
  foreach($global_event_dates as $event_date){
    if(!get_attendee_status_all_from_event_id_and_event_date($event_id,$event_date)){
      $thistime_attendee_statues[$event_date] = $global_attendee_statues;
    }
  }
  $_SESSION['thistime_attendee_statues'] = $thistime_attendee_statues;
  

  # form part
  $att_name = $_GET['att_name'];
  $_SESSION['attName'] = $att_name;

  header( "Location: ./view_modify_attendee.php" ); exit;
?>