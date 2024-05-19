<?php 
    session_start();
    function show_mark($status){
        $cross = "✕";
        $tangle = "△";
        $circle = "◎";
        switch($status){
            case 0: return $cross;
            case 1: return $tangle;
            default: return $circle;
        }
    }
    $_SESSION['event_id'];
    $event_title = $_SESSION[ 'title' ];
    $isCreator = $_SESSION[ 'isCreator' ];
    $link1 = $_SESSION[ 'modify_event_link' ];
    $total_atten = $_SESSION[ 'total_atten' ];
    $memo = $_SESSION[ 'memo' ];
    $view_mode = $_SESSION['view_mode'];
    $link2 = $_SESSION['create_attendee_link'];
    $attendee_names = $_SESSION['attendee_names'];
    $event_dates = $_SESSION['event_dates'];
    $thistime_attendee_statues = $_SESSION['thistime_attendee_statues'];
    $modify_attendee_name = $_SESSION['attName']; 
    $attendee_comments = $_SESSION['attendee_comments'];
    $form_action = $_SESSION['form_action'];

    $event_id = $_GET['eid'];
    $isCreator = True;
?>

<div class="container">
    <!-- event name part -->
    <div class="container-fluid mt-3">
        <div class="row border-2 border-bottom border-dark">
            <div class="col-md-8">
                <p class="fs-3 fw-bold"><?php echo $event_title; ?></p>
            </div>
            <?php if( $isCreator == True ) : ?>
                <div class="col-md-4 text-end">
                    <a class="btn btn-outline-primary" href="<?php echo "view_modify_event.php?eid=".$event_id; ?>" role="button">
                        イベント編集
                    </a>
                </div>
            <?php endif ?>
        </div>
        <div>
            <p class="text-start pt-1 fs-5"><?php echo $total_atten; ?></p> 
        </div>
    </div>
    
    <!-- event memo part -->
    <div class="container-fluid mt-5">
        <div>
            <p class="fs-3 fw-bold">イベントの詳細説明</p>
        </div>
        <div>
            <p class="text-start pt-1 fs-5"><?php echo $memo; ?></p> 
        </div>
    </div>

    <!-- schedule part -->
    <div class="container-fluid mt-5">
        <div>
            <p class="fs-3 fw-bold">日にち候補</p>
        </div>
        <div>
            <p class="text-start pt-1 fs-5">※各自の出欠状況を変更するには名前のリンクをクリックしてください。</p> 
        </div>
        <table class="table table-bordered border-dark text-center">
            <thead>
                <tr>
                    <th scope="col">日程</th>
                    <th scope="col">◎</th>
                    <th scope="col">△</th>
                    <th scope="col">✕</th>
                    <?php foreach ($attendee_names as $att_name): ?>
                        <th scope="col">
                        <a class="btny" href="../controller/modify_attendee.php?att_name=<?php echo urlencode($att_name); ?>" role="button"><?php echo htmlspecialchars($att_name); ?></a>
                        </th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($event_dates as $date): ?>
                <tr>
                    <td><?php echo htmlspecialchars($date); ?></td>
                    <?php
                        $cross_num = 0;
                        $tangle_num = 0;
                        $circle_num = 0;
                        foreach ($thistime_attendee_statues[$date] as $status) {
                            switch ($status) {
                                case 0: $cross_num++; break;
                                case 1: $tangle_num++; break;
                                case 2: $circle_num++; break;
                                default: break;
                            }
                        }
                    ?>
                    <td><?php echo $circle_num; ?></td>
                    <td><?php echo $tangle_num; ?></td>
                    <td><?php echo $cross_num; ?></td>
                    <?php foreach ($thistime_attendee_statues[$date] as $status): ?>
                        <td><?php echo show_mark($status); ?></td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td>コメント</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <?php foreach ($attendee_comments as $comment): ?>
                        <td><?php echo $comment; ?></td>
                    <?php endforeach; ?>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- attendee enroll part -->
    <?php if (!$view_mode): ?>
        <div class="row justify-content-center mt-5 mb-5">
            <div class="col-md-8">
                <div class="border-2 border-bottom border-dark">
                    <p class="fs-3 fw-bold">出欠を入力する</p>
                </div>
                <form action="<?php echo $form_action; ?>" method="POST">
                    <div class="mt-3">
                        <label for="attendeeName" class="form-label">名前</label>
                        <div id="formatHelp" class="form-text">絵文字は使用できません。</div>
                        <input type="text" class="form-control" id="attendeeName" name="attendeeName" aria-describedby="formatHelp" value="<?php echo $modify_attendee_name; ?>">
                    </div>
                    <div class="mt-4"> 
                        <p>日にち候補</p> 
                        <div class="row g-3">
                        <?php foreach ($event_dates as $date): ?>
                            <div class="col-3"><?php echo $date ?></div>
                            <div class="btn-group col-9" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="checkbox-<?php echo $date ?>" id="btnradio1-<?php echo $date ?>" value="2" autocomplete="off" checked>
                                <label class="btn btn-outline-primary" for="btnradio1-<?php echo $date ?>">◎</label>

                                <input type="radio" class="btn-check" name="checkbox-<?php echo $date ?>" id="btnradio2-<?php echo $date ?>" value="1" autocomplete="off">
                                <label class="btn btn-outline-primary" for="btnradio2-<?php echo $date ?>">△</label>

                                <input type="radio" class="btn-check" name="checkbox-<?php echo $date ?>" id="btnradio3-<?php echo $date ?>" value="0" autocomplete="off">
                                <label class="btn btn-outline-primary" for="btnradio3-<?php echo $date ?>">✕</label>
                            </div>
                        <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="mt-4 mb-4">
                        <label for="comment" class="form-label">コメント</label>
                        <input type="text" class="form-control" id="comment" name="comment">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">入力する</button>
                    </div>
                </form>
            </div>
        </div>
    <?php else: ?>
        <div class="mt-3 text-center">
            <a class="btn btn-primary" href="<?php echo $link2; ?>" role="button">出欠を入力する</a>
        </div>
    <?php endif; ?>
</div>
