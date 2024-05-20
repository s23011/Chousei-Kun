<?php
define("CODE_ERROR",1);
define("CODE_SUCCESS",0);

include_once("connection.php");

function get_event_info_from_event_id($event_id){
    if(check_event_id_available($event_id) == CODE_ERROR){ 
        add_msg("Not a available event id:".$event_id);
        return CODE_ERROR;
    }
    
    global $pdo;

    $sql = "SELECT * FROM event_info WHERE event_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,));

    $row = $q->fetch();

    if(!$row){
        add_msg("The event do not exist.(".$event_id.")");
        return CODE_ERROR;
    }

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
function generate_event_id($columnNum){
    $currentTime = gettimeofday(as_float:true);
    $combinedStr = strval($columnNum + $currentTime); // (int + float), and convert the reslut to string
    $hash_id = hash('md5', $combinedStr); // use md5 produce to generate 32bytes hash value
    $event_id = substr($hash_id, 0, 20); // Intercepting the top 20 bytes
    return $event_id;
}

function create_event($event_name,$event_dates,$event_memo){
    global $pdo;

    //create hash as event id by row count + time,and limit the length in 20.
    $sql = "SELECT count(*) FROM event_info";
    $columnNum = $pdo->query($sql)->fetchColumn() +1;
    $create_event_id = generate_event_id($columnNum);

    //If id is exist.Maybe return and try again is better than while loop to create new id. 
    if(get_event_info_from_event_id($create_event_id) == CODE_SUCCESS){
        
        add_msg("Please try again to create event.");
        return CODE_ERROR;
    }

    if(check_event_id_available($create_event_id) == CODE_ERROR){
        add_msg("Method for checking event id need to update.");
        return CODE_ERROR;
    }

    $event_name = encode_spchar($event_name);
    $event_dates = encode_spchar($event_dates);
    $event_memo = encode_spchar($event_memo);

    $sql = "INSERT INTO event_info VALUES (?,?,?,?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($create_event_id,$event_name,$event_dates,$event_memo));

    if(!$q){ 
        add_msg("Create event in error.");
        return CODE_ERROR;
    }
    
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

    if(!$q){ 
        add_msg("Modify event info in error");
        return CODE_ERROR;
    }

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

    if(empty($rows)){
        return CODE_ERROR;
    }

    global $global_attendee_names, $global_attendee_comments;

    foreach($rows as $row){
        $attendee_name = decode_spchar($row['attendee_name']);
        $attendee_comment = decode_spchar($row['comment']);

        $global_attendee_names[] = $attendee_name;
        $global_attendee_comments[] = $attendee_comment;
    }

    return CODE_SUCCESS;
}

function get_attendee_statuses_from_event_id_and_attendee_name($event_id,$attendee_name){
    global $pdo;

    $attendee_name = encode_spchar($attendee_name);

    $sql = "SELECT * FROM attendee_status WHERE event_id = ? AND attendee_name = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$attendee_name));

    $rows = $q->fetchAll();

    if(!$rows){ 
        add_msg("The attendee do not exist.");
        return CODE_ERROR;
    }

    global $global_attendee_dates,$global_attendee_statuses;
    foreach($rows as $row){
        $date = decode_spchar($row["date"]);

        $global_attendee_dates[] = $date;
        $global_attendee_statuses[] = $row["status"];
    }

    return CODE_SUCCESS;
}

function create_attendee_info($event_id,$attendee_name,$attendee_comment){
    global $pdo;

    $attendee_name = encode_spchar($attendee_name);
    $attendee_comment = encode_spchar($attendee_comment);

    $sql = "SELECT * FROM attendee_info WHERE event_id = ? AND attendee_name = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$attendee_name));

    $row = $q->fetch();

    if($row){ 
        add_msg("The attendee name already exist.");
        return CODE_ERROR;
    }

    $sql = "INSERT INTO attendee_info VALUES (?,?,?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$attendee_name,$attendee_comment));

    if(!$q){ 
        add_msg("Create attendee info in error.");
        return CODE_ERROR;
    }

    return CODE_SUCCESS;
}

function create_attendee_status($event_id,$attendee_name,$date,$status){
    global $pdo;

    $attendee_name = encode_spchar($attendee_name);

    $sql = "SELECT * FROM attendee_status WHERE event_id = ? AND attendee_name = ? AND date = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$attendee_name,$date));

    $row = $q->fetch();
    if($row){
        add_msg("The attendee status already exist");
        return CODE_ERROR;
    }

    $sql = "INSERT INTO attendee_status VALUES (?,?,?,?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$attendee_name,$date,$status));

    if(!$q){
        add_msg("Create attendee status in error.");
        return CODE_ERROR;
    }

    return CODE_SUCCESS;
}

function modify_attendee_info_from_event_id_and_attendee_name($event_id,$attendee_name,$new_name,$new_comment){
    global $pdo;

    $attendee_name = encode_spchar($attendee_name);
    $new_name = encode_spchar($new_name);
    $new_comment = encode_spchar($new_comment);

    $sql = "SELECT * FROM attendee_info WHERE event_id = ? AND attendee_name = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$attendee_name));

    $row = $q->fetch();

    if(!$row){ 
        add_msg("The attendee name do not exist.");
        return CODE_ERROR;
    }

    $sql = "UPDATE attendee_info SET attendee_name = ?,comment = ? WHERE event_id = ? AND attendee_name = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($new_name,$new_comment,$event_id,$attendee_name,));

    if(!$q){ 
        add_msg("Modify attendee info in error.");
        return CODE_ERROR;
    }

    return CODE_SUCCESS;
}

function modify_attendee_status_from_event_id_and_attendee_name($event_id,$attendee_name,$new_name,$new_date,$new_status){
    global $pdo;

    $attendee_name = encode_spchar($attendee_name);
    $new_name = encode_spchar($new_name);
    $new_date = encode_spchar($new_date);

    $sql = "SELECT * FROM attendee_status WHERE event_id = ? AND attendee_name = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$attendee_name));

    $row = $q->fetch();

    if(!$row){
        add_msg("The attendee status do not exist.");
        return CODE_ERROR;
    }

    $sql = "UPDATE attendee_status SET attendee_name = ?,date = ?,status = ? WHERE event_id = ? AND attendee_name = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($new_name,$new_date,$new_status,$event_id,$attendee_name,));

    if(!$q){
        add_msg("Modify attendee status in error.");
        return CODE_ERROR;
    }

    return CODE_SUCCESS;
}

function get_attendee_status_from_event_id_and_event_date($event_id,$date){
    global $pdo;

    $sql = "SELECT * FROM attendee_status WHERE event_id = ? AND date = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$date));

    $rows = $q->fetchAll();

    if(!$rows){
        add_msg("Get attendee info do not exist.");
        return CODE_ERROR;
    }

    global $global_attendee_statues;
    $global_attendee_statues = null;
    foreach($rows as $row){
        $global_attendee_statues[] = $row["status"];
    }

    return CODE_SUCCESS;
}

//new
function get_attendee_status_all_from_event_id_and_event_date($event_id,$date){
    global $pdo;

    $sql = "SELECT * FROM attendee_status WHERE event_id = ? AND date = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$date));

    $rows = $q->fetchAll();

    if(!$rows){
        add_msg("Get attendee info do not exist.". json_encode($date));
        return CODE_ERROR;
    }

    global $global_attendee_statues;
    $global_attendee_statues = null;
    foreach($rows as $row){
        $attendee_name = decode_spchar($row["attendee_name"]);
        $global_attendee_statues[$attendee_name] = $row["status"];
    }

    return CODE_SUCCESS;
}

//count of text length is unexpected number now
function check_txt_limit_length($txt,$limit_length){
    $txt_length = strlen($txt);

    if($txt_length > $limit_length+20){
        add_msg("over text:".$txt."(".$txt_length);
        return CODE_ERROR;
    }

    return CODE_SUCCESS;
}

function encode_spchar($txt){
    return htmlspecialchars($txt);
}
function decode_spchar($txt){
    return htmlspecialchars_decode($txt);
}

function check_event_id_available($event_id){
    // if(!is_int((int)$event_id)){return CODE_ERROR;} //when event id only a integer value
    return CODE_SUCCESS;
    // return check_txt_limit_length($event_id,20);
    // return check_int_length($event_id,1);
}

function check_int_length($number,$length){
    $num_length = strlen((string)$number);

    if($num_length != $length){return CODE_ERROR;}

    return CODE_SUCCESS;
}


?>