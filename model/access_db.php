<?php
define("CODE_ERROR",1);
define("CODE_SUCCESS",0);

init_db();


function init_db(){
    $dsn = 'mysql:dbname=chouseikun;host=localhost;charset=utf8;';
    try{
        $GLOBALS["pdo"] = new PDO($dsn,'root','kickickic');
    }catch(PDOException $e){
        return set_db_msg('Failed to connect to MySQL:'.$e->getMessage());
    }
}

function get_event_info_from_event_id($event_id){
    if(check_event_id_available($event_id) == CODE_ERROR){ return set_db_msg("Not a available event id:".$event_id);}
    
    global $pdo;

    $sql = "SELECT * FROM event_info WHERE event_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,));

    $row = $q->fetch();

    if(!$row){ return set_db_msg("The event do not exist.");}

    $event_name = decode_spchar($row["event_name"]);
    $event_dates = decode_spchar($row["dates"]);
    $event_memo = decode_spchar($row["memo"]);

    $event_dates = preg_split("/\r\n|\n|\r/", $row["dates"]); //split string by newline for mutiple os

    global $global_event_name,$global_event_dates,$global_event_memo;

    $global_event_name = $event_name;
    $global_event_dates = $event_dates;
    $global_event_memo = $event_memo;

    return CODE_SUCCESS;
}

function create_event($event_name,$event_dates,$event_memo){
    global $pdo;
    $sql = "SELECT count(*) FROM event_info";
    $create_event_id = $pdo->query($sql)->fetchColumn() +1;
    //get row count
    //get time
    //create hash by row count + time,and limit hash length in 20.
    //event id = hash

    if(get_event_info_from_event_id($create_event_id) == CODE_SUCCESS){
        return set_db_msg("Try again to create event.");//If id is exist.Maybe return and try again is better than while loop to create new id. 
    }

    if(check_event_id_available($create_event_id) == CODE_ERROR){// If method for creating event id is updated.You can delelt this code session.
        return set_db_msg("Method for checking event id need to update.");
    }

    $event_name = encode_spchar($event_name);
    $event_dates = encode_spchar($event_dates);
    $event_memo = encode_spchar($event_memo);

    $sql = "INSERT INTO event_info VALUES (?,?,?,?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($create_event_id,$event_name,$event_dates,$event_memo));

    if(!$q){ return set_db_msg("Create event in error.");}
    
    global $global_event_id;
    $global_event_id = $create_event_id;

    return CODE_SUCCESS;
}

function modify_event($event_id,$event_name,$event_dates,$event_memo){
    if(get_event_info_from_event_id($event_id) == CODE_ERROR){return CODE_ERROR;}

    $event_name = encode_spchar($event_name);
    $event_dates = encode_spchar($event_dates);
    $event_memo = encode_spchar($event_memo);

    global $pdo;

    $sql = "UPDATE event_info SET event_name = ?,dates = ?,memo = ? WHERE event_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_name,$event_dates,$event_memo,$event_id));

    if(!$q){ return set_db_msg("Modify event info in error");}

    return CODE_SUCCESS;
}

function delete_event_from_event_id($event_id){
    if(get_event_info_from_event_id($event_id) == CODE_ERROR){return CODE_ERROR;}

    global $pdo;

    //delete event info
    $sql = "DELETE FROM event_info WHERE event_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,));

    //delete attendee info
    $sql = "DELETE FROM attendee_info WHERE event_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id));

    //delete attendee status
    $sql = "DELETE FROM attendee_status WHERE event_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id));

    return CODE_SUCCESS;
}




function get_attendee_info_from_event_id($event_id){
    global $pdo;

    $sql = "SELECT * FROM attendee_info WHERE event_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id));

    $rows = $q->fetchAll();
    global $global_attendee_num;
    $global_attendee_num = 0;
    if(empty($rows)){ return 0;}

    global $global_attendee_names, $global_attendee_comments;

    foreach($rows as $row){
        $global_attendee_num++;
        $global_attendee_names[] = $row['attendee_name'];
        $global_attendee_comments[] = $row['comment'];
    }

    return CODE_SUCCESS;
}

function get_attendee_statuses_from_event_id_and_attendee_name($event_id,$attendee_name){
    global $pdo;

    $sql = "SELECT * FROM attendee_status WHERE event_id = ? AND attendee_name = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$attendee_name));

    $rows = $q->fetchAll();

    if(!$rows){ return set_db_msg("Get attendee status in error.");}

    global $global_attendee_dates,$global_attendee_statuses;
    foreach($rows as $row){
        $global_attendee_dates[] = $row["date"];
        $global_attendee_statuses[] = $row["status"];
    }

    return CODE_SUCCESS0;
}

function create_attendee_info($event_id,$attendee_name,$attendee_comment){
    global $pdo;

    $sql = "SELECT * FROM attendee_info WHERE event_id = ? AND attendee_name = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$attendee_name));

    $row = $q->fetch();

    if($row){ return set_db_msg("The attendee info already exist.");}

    $sql = "INSERT INTO attendee_info VALUES (?,?,?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$attendee_name,$attendee_comment));

    if(!$q){ return set_db_msg("Create attendee info in error.");}

    return CODE_SUCCESS;
}

function create_attendee_status($event_id,$attendee_name,$date,$status){
    global $pdo;

    $sql = "SELECT * FROM attendee_status WHERE event_id = ? AND attendee_name = ? AND date = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$attendee_name,$date));

    $row = $q->fetch();
    if($row){ return set_db_msg("The attendee status already exist");}

    $sql = "INSERT INTO attendee_status VALUES (?,?,?,?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$attendee_name,$date,$status));

    if(!$q){ return set_db_msg("Create attendee status in error.");}

    return CODE_SUCCESS;
}

function modify_attendee_info_from_event_id_and_attendee_name($event_id,$attendee_name,$new_name,$new_comment){
    global $pdo;

    $sql = "SELECT * FROM attendee_info WHERE event_id = ? AND attendee_name = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$attendee_name));

    $row = $q->fetch();

    if(!$row){ return set_db_msg("Get attendee info in error.");}

    $sql = "UPDATE attendee_info SET attendee_name = ?,comment = ? WHERE event_id = ? AND attendee_name = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($new_name,$new_comment,$event_id,$attendee_name,));

    if(!$q){ return set_db_msg("Modify attendee info in error.");}

    return CODE_SUCCESS;
}

function modify_attendee_status_from_event_id_and_attendee_name($event_id,$attendee_name,$new_name,$new_date,$new_status){
    global $pdo;

    $sql = "SELECT * FROM attendee_status WHERE event_id = ? AND attendee_name = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$attendee_name));

    $row = $q->fetch();

    if(!$row){ return set_db_msg("Get attendee info in error.");}

    $sql = "UPDATE attendee_status SET attendee_name = ?,date = ?,status = ? WHERE event_id = ? AND attendee_name = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($new_name,$new_date,$new_status,$event_id,$attendee_name,));

    if(!$q){ return set_db_msg("Modify attendee status in error.");}

    return CODE_SUCCESS;
}

function get_attendee_status_from_event_id_and_event_date($event_id,$date){
    global $pdo;

    $sql = "SELECT * FROM attendee_status WHERE event_id = ? AND date = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$date));

    $rows = $q->fetchAll();

    if(!$rows){ return set_db_msg("Get attendee info in error.");}

    global $global_attendee_statues;
    $global_attendee_statues = null;
    foreach($rows as $row){
        $global_attendee_statues[] = $row["status"];
    }

    return CODE_SUCCESS;
}

function set_db_msg($msg,$error_code = CODE_ERROR){
    global $global_db_msg;
    $global_db_msg = $msg;

    return $error_code;
}

function check_txt_limit_length($txt,$limit_length){
    $txt_length = strlen($txt);

    if($txt_length > $limit_length){return CODE_ERROR;}

    return CODE_SUCCESS;
}

function encode_spchar($txt){
    return htmlspecialchars($txt);
}
function decode_spchar($txt){
    return htmlspecialchars_decode($txt);
}

function check_event_id_available($event_id){
    if(!is_int((int)$event_id)){return CODE_ERROR;} //when event id only a integer value

    return check_int_length($event_id,1);
}

function check_int_length($number,$length){
    $num_length = strlen((string)$number);

    if($num_length != $length){return CODE_ERROR;}

    return CODE_SUCCESS;
}


?>