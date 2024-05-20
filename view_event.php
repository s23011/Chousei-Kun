<!doctype html>
<html lang="ja">
    <head>
        <!-- Required meta tags -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">


        <title>出欠情報閲覧</title>
        <link rel="icon" type="image/x-icon" href="/chouseikun/template/chouseikun.png">
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    </head>

    <body class="container-fluid">

        <?php 
        session_start();
        
        include "template/navbar.html";

        include "template/message.php";
        
        include "template/view_modify_attendee.php";
        ?>

    </body>
</html>