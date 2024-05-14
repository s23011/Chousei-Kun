<?php
    session_start();
    $server_path = "localhost/chouseikun/controller/view_attendee.php/";
    $event_id = $_GET['event_id'];
    $_SESSION['event_id'] =  $event_id;
    $event_url = $server_path.$event_id;
    $_SESSION['isCreator'] = True;
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

         <!-- Bootstrap CSS -->
         <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

         <title>イベントURL</title>
    </head>

    <body>
        <div class="container">
            <div class="container-fluid m-4">
                <h1 class="fw-bold text-start">調整くん</h1>
            </div>
            <div class="container-fluid mt-3">
                <p class="fs-3 fw-bold border-bottom border-2 border-dark">イベント新規作成</p>
                <p>イベントが作成されました。以下のURLをメール等を使って皆に知らせてあげよう。以降、このURLページにて各自の出欠情報を入力してもらいます。</p>
            </div>
            <div class="container-fluid mt-3">
                <div class="input-group d-grid">
                    <input type="text" aria-label="link" value="<?php echo $event_url; ?>" id="copyText">
                    <button class="btn btn-outline-secondary" type="button" onclick="copyTextFunction()">コピー</button>
                </div>
                <script>
                    function copyTextFunction(){
                        var copyText = document.getElementById("copyText");
                        copyText.select();
                        document.execCommand("copy");
                        alert("URLがコピーされた!");
                    }
                </script>
            </div>
            <div class="container-fluid text-center mt-3 mb-3">
                <a class="btn btn-primary" href="./controller/view_attendee.php/<?php echo $event_id; ?>" role="button">イベントページを表示</a>
            </div>
        </div>
    </body>
</html>