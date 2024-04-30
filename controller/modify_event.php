<?php
// if (!$_SERVER["REQUEST_METHOD"] == "POST") {
//     http_response_code(405); 
// }else{
//     echo "not post?</br>";
// }

include_once("../model/access_db.php");


// //session test
// session_start();
// $_SESSION["event_id"]=123;

if(isset($_SESSION["event_id"])){
    $event_id = $_SESSION['event_id'];

    if(get_event_info_from_event_id($event_id) == 0){
        echo "get event id:".$event_id."</br>";
        echo "get event info:".$global_event_name."/memo:".$global_event_memo."</br>";
    }
}else{
    $event_name = $_POST['event_name'];
    $event_dates = $_POST['event_dates'];
    $event_memo = $_POST['event_memo'];
    
    if(create_event($event_name,$event_dates,$event_memo) == 0){
        session_start();
        $_SESSION["event_id"]=$global_event_id;

        header("Location: ../index.php");
        exit();
    }else{
    }
    
}



// //session test
// session_unset();    // remove all session variables
// session_destroy();  // destroy the session

?>