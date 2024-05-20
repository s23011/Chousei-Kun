<?php
  session_start();
  include("../model/access_db.php");

  // #temparely
  // $path = $_SERVER['REQUEST_URI'];
  // if(isset($_COOKIE['event_id'])){
  //   $_SESSION['isCreator'] = True;
  //   $_SESSION['event_id'] = $_COOKIE['event_id'];
  // }else{
  //   $hash_id = substr($path, -20);
  //   $_SESSION['isCreator'] = False;
  //   $_SESSION['event_id'] = $hash_id;
  // }
  

  $_SESSION['view_mode'] = True;
  // $_SESSION['modify_event_link'] = "../view_modify_event.php";
  // $_SESSION['create_attendee_link'] = "../controller/create_attendee.php";
  $_SESSION['attName'] = '';
  $_SESSION['form_action'] = 'post_form.php';

  #permently part
  $event_id = $_SESSION['event_id'];
  if(get_event_info_from_event_id($event_id) == CODE_SUCCESS) {
    $_SESSION['title'] = $global_event_name;
    $_SESSION['memo'] = $global_event_memo;
    $_SESSION['event_dates'] = $global_event_dates;

    $_SESSION['event_info']['event_name'] =  $global_event_name;
    $_SESSION['event_info']['memo'] =  $global_event_memo;
    $_SESSION['event_info']['event_dates'] =  $global_event_dates;
  }

  if(get_attendee_info_from_event_id($event_id) == CODE_SUCCESS) {
    $_SESSION['attendee_num'] = $global_attendee_num;
    $_SESSION['attendee_names'] = $global_attendee_names;
    $_SESSION['attendee_comments'] = $global_attendee_comments;

    $_SESSION['attendee_info_list']['attendee_names'] =  $global_attendee_names;
    $_SESSION['attendee_info_list']['attendee_comments'] =  $global_attendee_comments;
  }
  // if($_SESSION['isCreator']){
  //   $title_below = "あなたが幹事のイベントです。";
  // }else{
  //   $title_below = "「出欠を入力する」ボタンから出欠を入力しましょう。";
  // }
  // $_SESSION['total_atten'] = "解答者数{$global_attendee_num}人、{$title_below}";

  $thistime_attendee_statues = array();
  foreach($global_event_dates as $event_date){
    // if(get_attendee_status_from_event_id_and_event_date($event_id,$event_date) == CODE_SUCCESS){
    //   $thistime_attendee_statues[$event_date] = $global_attendee_statues;

    //   $_SESSION['attendee_status_list'][$event_date] =  $global_attendee_statues;
    // }else{
    //   $thistime_attendee_statues[$event_date] = array();

    //   $_SESSION['attendee_status_list'][$event_date] = array();
    // }

    if(get_attendee_status_all_from_event_id_and_event_date($event_id,$event_date) == CODE_SUCCESS){
      $thistime_attendee_statues[$event_date] = $global_attendee_statues;

      $_SESSION['attendee_status_list'][$event_date] =  $global_attendee_statues;
    }else{
      add_msg("(test)attendee status not exist:".json_encode($event_date));
      $thistime_attendee_statues[$event_date] = array();
      $_SESSION['attendee_status_list'][$event_date] = array();
    }
  }
  $_SESSION['thistime_attendee_statues'] = $thistime_attendee_statues;





  header( "Location: ../view_event.php?eid=".$event_id ); 
  // header( "Location: ../template/view_modify_attendee.php" ); 
  exit();
?>