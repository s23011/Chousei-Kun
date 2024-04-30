<?php
init_db();


function init_db(){
    $dsn = 'mysql:dbname=chouseikun;host=localhost;charset=utf8;';
    try{
        // global $pdo;
        // $pdo= new PDO($dsn,'root','kickickic');
        $GLOBALS["pdo"] = new PDO($dsn,'root','kickickic');
    }catch(PDOException $e){
        echo 'Failed to connect to MySQL:'.e->getMessage();
    }
}

function get_event_info_from_event_id($event_id){






    global $global_event_name,$global_event_dates,$global_event_memo;

    $global_event_name = "test name";
    $global_event_dates = ["day1","day2","day3"];
    $global_event_memo = "test memo";

    return 0;
}

function create_event($event_name,$event_dates,$event_memo){
    global $pdo;
    $sql = "select count(*) from event_info";
    $create_event_id = $pdo->query($sql)->fetchColumn() +1;

    $sql = "insert into event_info values (?,?,?,?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($create_event_id,$event_name,$event_dates,$event_memo));

    if(!$q){
        return 1;
    }    
    
    global $global_event_id;
    $global_event_id = $create_event_id;

    return 0;
}




function get_attendee_info_from_event_id_and_attendee_name($event_id,$attendee_name){






    global $global_attendee_comment;

    $global_attendee_comment = "test comment";

    return 0;
}

function get_attendee_statuses_from_event_id_and_attendee_name($event_id,$attendee_name){






    global $global_attendee_dates;
    global $global_attendee_status;

    $global_attendee_dates = ["day1","day2","day3"];
    $global_attendee_status = [0,1,2];

    return 0;
}

function create_attendee_info($event_id,$attendee_name,$attendee_comment){
    global $pdo;

    $sql = "select * from attendee where event_id = ? and attendee_name = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$attendee_name));

    $row = $q->fetch();

    if($row){
        return 1;
    }

    $sql = "insert into users values (?,?,?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$attendee_name,$attendee_comment));

    if(!$q){
        return 1;
    }

    return 0;
}

function create_attendee_status($event_id,$attendee_name,$date,$status){
    global $pdo;

    $sql = "select * from attendee where event_id = ? and attendee_name = ? and date = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$attendee_name,$date));

    if($row){
        return 1;
    }

    $sql = "insert into users values (?,?,?,?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($event_id,$attendee_name,$date,$status));

    if(!$q){
        return 1;
    }

    return 0;
}


?>