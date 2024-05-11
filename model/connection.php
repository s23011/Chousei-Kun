<?php
$dsn = 'mysql:dbname=chouseikun;host=localhost;charset=utf8;';
try{
    $GLOBALS["pdo"] = new PDO($dsn,'root','kickickic');
}catch(PDOException $e){
    return set_db_msg('Failed to connect to MySQL:'.$e->getMessage());
}
?>