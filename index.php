<?php 
if(isset($_SESSION["event_id"])){
    unset($_SESSION["event_id"]);
}

include_once("view_modify_event.php");
?>

