<?php
$dsn = 'mysql:dbname=chouseikun;host=localhost;charset=utf8;';
try{
    $GLOBALS["pdo"] = new PDO($dsn,'root','6251');
}catch(PDOException $e){
    return set_db_msg('Failed to connect to MySQL:'.$e->getMessage());
}
?>