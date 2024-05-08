<?php 
session_start();

if(isset($_GET["eid"])){
    $_SESSION["event_id"]=$_GET["eid"];
}else{
    unset($_SESSION["event_id"]);
}
?>

<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        調整くん
        <?php
            if(isset($_SESSION["event_id"])){
                echo "：イベント編集";
            }else{
                echo "：イベント作成";
            }
        ?>
    </title>
    <!--CSS-->
    <link rel="stylesheet" type="text/css" href="mystyle.css">
    <link rel="stylesheet" type="text/css" href="formstyle.css">
    <!-- CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="https://code.jquery.com/jquery-1.12.4.js" integrity="sha256-Qw82+bXyGq6MydymqBxNPYTaUXXq7c8v3CwiYwLLNXU=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js" integrity="sha256-J8ay84czFazJ9wcTuSDLpPmwpMXOm573OUtZHPQqpEU=" crossorigin="anonymous"></script>
</head>

<body class="bg_col">
    <?php
    include "template/navbar.php";

    include "template/message.php";

    include "template/modify_event.html";

    if(isset($_SESSION["event_id"])){
        // setup 「戻る」button to link view_event page when modify mode
    }
    ?>
</body>
</html>

<!-- CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/locales/bootstrap-datepicker.nl.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.standalone.min.css" rel="stylesheet"/>

<!--Script-->
<script type="text/javascript" src="myscript.js"></script>
<script type="text/javascript">
    get_event_info(); // function from myscript.js

    $('#datepicker').datepicker({
            todayHighlight: true,
            format: 'yyyy/mm/dd',
    });
    $('#datepicker').on('changeDate', function() {
        $('#dates').val($('#dates').val()+$('#datepicker').datepicker('getFormattedDate')+'\r\n');
    });
</script>
