<?php 
// //session test
// session_start();
// $_SESSION["event_id"]=123;



include_once("../model/access_db.php");

if(isset($_SESSION["event_id"])){
    $event_id = $_SESSION['event_id'];

    if(get_event_info_from_event_id($event_id) == 0){
        $event_info = ['event_name'=> $global_event_name,'event_memo'=>$global_event_memo,];
        echo json_encode($event_info); 
    }
}

// //session test
// session_unset();    // remove all session variables
// session_destroy();  // destroy the session

?>