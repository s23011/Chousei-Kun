<?php
init_db();


function init_db(){
    $dsn = 'mysql:dbname=chouseikun;host=localhost;charset=utf8;';
    try{
        $GLOBALS["pdo"] = new PDO($dsn,'root','kickickic');
    }catch(PDOException $e){
        return set_db_msg('Failed to connect to MySQL:'.e->getMessage());
    }
}

function get_event_info_from_event_id($event_id){
    global $pdo;

    $sql = "SELECT * FROM event_info WHERE event_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,));

    $row = $q->fetch();

    if(!$row){ return set_db_msg("get event info error");}

    global $global_event_name,$global_event_dates,$global_event_memo;

    $global_event_name = $row["event_name"];
    $global_event_dates = preg_split("/\r\n|\n|\r/", $row["dates"]); //split string by newline for mutiple os
    $global_event_memo = $row["memo"];

    return 0;
}

function create_event($event_name,$event_dates,$event_memo){
    global $pdo;
    $sql = "SELECT count(*) FROM event_info";
    $create_event_id = $pdo->query($sql)->fetchColumn() +1;

    $sql = "INSERT INTO event_info VALUES (?,?,?,?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($create_event_id,$event_name,$event_dates,$event_memo));

    if(!$q){ return set_db_msg("create event error");}
    
    global $global_event_id;
    $global_event_id = $create_event_id;

    return 0;
}

function modify_event($event_id,$event_name,$event_dates,$event_memo){
    global $pdo;

    $sql = "SELECT * FROM event_info WHERE event_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,));

    $row = $q->fetch();

    if(!$row){ return set_db_msg("get event info error");}

    $sql = "UPDATE event_info SET event_name = ?,event_memo = ?,event_dates = ? WHERE event_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$event_name,$event_memo,$event_dates));

    if(!$q){ return set_db_msg("modify event info error");}

    return 0;
}

function delete_event_from_event_id($event_id){
    global $pdo;

    $sql = "SELECT * FROM event_info WHERE event_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,));

    $row = $q->fetch();

    if(!$row){ return set_db_msg("get event info error");}

    $sql = "DELETE FROM event_info WHERE event_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,));

    if(!$q){ return set_db_msg("delete event info error");}

    //delete attendee info and status
    $sql = "DELETE FROM attendee_info WHERE event_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id));

    $sql = "DELETE FROM attendee_status WHERE event_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$attendee_name));

    return 0;
}




function get_attendee_info_from_event_id_and_attendee_name($event_id,$attendee_name){
    global $pdo;

    $sql = "SELECT * FROM attendee_info WHERE event_id = ? AND attendee_name = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$attendee_name));

    $row = $q->fetch();

    if(!$row){ return set_db_msg("get attendee info error");}

    global $global_attendee_comment;

    $global_attendee_comment = $row["comment"];

    return 0;
}

function get_attendee_statuses_from_event_id_and_attendee_name($event_id,$attendee_name){
    global $pdo;

    $sql = "SELECT * FROM attendee_status WHERE event_id = ? AND attendee_name = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$attendee_name));

    $rows = $q->fetchAll();

    if(!$rows){ return set_db_msg("get attendee status error");}

    global $global_attendee_dates,$global_attendee_statuses;
    foreach($rows as $row){
        $global_attendee_dates[] = $row["date"];
        $global_attendee_statuses[] = $row["status"];
    }

    return 0;
}

function create_attendee_info($event_id,$attendee_name,$attendee_comment){
    global $pdo;

    $sql = "SELECT * FROM attendee_info WHERE event_id = ? AND attendee_name = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$attendee_name));

    $row = $q->fetch();

    if($row){ return set_db_msg("attendee info already exist");}

    $sql = "INSERT INTO users VALUES (?,?,?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$attendee_name,$attendee_comment));

    if(!$q){ return set_db_msg("create attendee info error");}

    return 0;
}

function create_attendee_status($event_id,$attendee_name,$date,$status){
    global $pdo;

    $sql = "SELECT * FROM attendee_status WHERE event_id = ? AND attendee_name = ? AND date = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$attendee_name,$date));

    if($row){ return set_db_msg("attendee status already exist");}

    $sql = "INSERT INTO users VALUES (?,?,?,?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$attendee_name,$date,$status));

    if(!$q){ return set_db_msg("create attendee status error");}

    return 0;
}

function modify_attendee_info_from_event_id_and_attendee_name($event_id,$attendee_name,$new_name,$new_comment){
    global $pdo;

    $sql = "SELECT * FROM attendee_info WHERE event_id = ? AND attendee_name = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$attendee_name));

    $row = $q->fetch();

    if(!$row){ return set_db_msg("get attendee info error");}

    $sql = "UPDATE attendee_info SET attendee_name = ?,comment = ? WHERE event_id = ? AND attendee_name = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($new_name,$new_comment,$event_id,$attendee_name,));

    if(!$q){ return set_db_msg("modify attendee info error");}

    return 0;
}

function modify_attendee_status_from_event_id_and_attendee_name($event_id,$attendee_name,$new_name,$new_date,$new_status){
    global $pdo;

    $sql = "SELECT * FROM attendee_status WHERE event_id = ? AND attendee_name = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$attendee_name));

    $row = $q->fetch();

    if(!$row){ return set_db_msg("get attendee info error");}

    $sql = "UPDATE attendee_status SET attendee_name = ?,date = ?,status = ? WHERE event_id = ? AND attendee_name = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($new_name,$new_date,$new_status,$event_id,$attendee_name,));

    if(!$q){ return set_db_msg("modify attendee status error");}

    return 0;
}

function delete_attendee_from_event_id_and_attendee_name($event_id,$attendee_name){
    global $pdo;

    //delete attendee info
    $sql = "SELECT * FROM attendee_info WHERE event_id = ? AND attendee_name = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$attendee_name));

    $row = $q->fetch();

    if(!$row){ return set_db_msg("get event info error");}

    $sql = "DELETE FROM attendee_info WHERE event_id = ? AND attendee_name = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$attendee_name));

    //delete attendee status
    $sql = "SELECT * FROM attendee_status WHERE event_id = ? AND attendee_name = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$attendee_name));

    $row = $q->fetch();

    if(!$row){ return set_db_msg("get event info error");}

    $sql = "DELETE FROM attendee_status WHERE event_id = ? AND attendee_name = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$attendee_name));

    return 0;
}

function get_attendee_status_all_from_event_id_and_event_date($event_id,$date){
    global $pdo;

    $sql = "SELECT * FROM attendee_status WHERE event_id = ? AND date = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$date));

    $rows = $q->fetchAll();

    if(!$rows){ return set_db_msg("get attendee status error");}

    global $global_attendee_dates,$global_attendee_statuses;
    foreach($rows as $row){
        $global_attendee_names[] = $row["attendee_name"];
        $global_attendee_dates[] = $row["date"];
        $global_attendee_statuses[] = $row["status"];
    }

    return 0;
}

function set_db_msg($msg,$error_code = 1){
    global $global_db_msg;
    $global_db_msg = $msg;

    return $error_code;
}


?>