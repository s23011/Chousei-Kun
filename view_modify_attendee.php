<?php session_start(); ?>
<!doctype html>
<html lang="ja">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

        <title>出欠情報閲覧</title>
    </head>

    <body>
        <?php
            $event_title = $_SESSION[ 'title' ];
            $isCreator = $_SESSION[ 'isCreator' ];
            $link = $_SESSION[ 'modify_event_link' ];
            $total_atten = $_SESSION[ 'total_atten' ];
            $memo = $_SESSION[ 'memo' ];
            $table = $_SESSION['table'];
            $button
        ?>
        <div class="container">
            <!-- title part -->
            <br>
            <div class="m-4">
                <h1 class="fw-bold text-right"> 調整くん </h1>
            </div>
            <br>

            <!-- event name part -->
            <div class="container-fluid">
                <div class="row border-2 border-bottom border-dark">
                    <div class="col-md-8">
                        <p class="fs-3 fw-bold text-right">Event Title</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <?php 
                            if( $isCreator == True ) {
                                print '<a class="btn btn-outline-primary" href="';
                                print $link;
                                print  '" role="button">イベント編集</a>';
                            }
                        ?>
                    </div>
                </div>
                <div>
                    <p class="text-start pt-1 fs-5"><?php echo $total_atten; ?></p> 
                </div>
            </div>
            
            <!-- event memo part -->
            <div class="container-fluid mt-2">
                <div>
                    <p class="fs-3 fw-bold text-right">イベントの詳細説明</p>
                </div>
                <div>
                    <p class="text-start pt-1 fs-5"><?php echo $memo; ?></p> 
                </div>
            </div>

            <!-- schedule part -->
            <div class="container-fluid mt-2">
                <div>
                    <p class="fs-3 fw-bold text-right">日にち候補</p>
                </div>
                <div>
                    <p class="text-start pt-1 fs-5">※各自の出欠状況を変更するには名前のリンクをクリックしてください。</p> 
                </div>
                <table class="table table-bordered border-dark">
                    <?php echo '$table'; ?>
                </table>
            </div>

            <!-- attendee enroll part -->
            <div class="container-fluid mt-2">
                <div>
                    <p class="fs-3 fw-bold text-right">イベントの詳細説明</p>
                </div>
                <div>
                    <p class="text-start pt-1 fs-5"><?php echo $memo; ?></p> 
                </div>
            </div>

            <div class="mt-3">
                <button class="bg-primary"><?php echo '$button text'; ?></button>
            </div>

        </div>

    </body>
</html>