<?php 
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

    if(!isset($_GET["eid"])){
        unset($_SESSION["event_id"]);
        unset($_SESSION['event_info']);
        unset($_SESSION['attendee_info_list']);
        unset($_SESSION['attendee_status_list']);

        unset($_SESSION['view_attendee_form']);
        unset($_SESSION['attendee_form_action']);
        unset($_SESSION['modifying_attendee_name']);

        header("Location: index.php");
        exit();
    }
    $event_id = $_GET['eid'];
    

    if(isset($_SESSION["event_id"])
        && $_SESSION["event_id"] != $event_id){
        $_SESSION["event_id"] = $event_id;
        header("Location: controller/view_attendee.php");
        exit();
    }
    $_SESSION["event_id"] = $event_id;
    
    if(!isset($_SESSION['event_info'])){
        header("Location: controller/view_attendee.php");
        exit();
    }

    $event_name = $_SESSION['event_info']['event_name'];
    $event_memo = $_SESSION['event_info']['memo'];
    $event_dates = $_SESSION['event_info']['event_dates'];

    if(!empty($_SESSION['attendee_info_list'])){
        $attendee_names = $_SESSION['attendee_info_list']['attendee_names'];
        $attendee_comments = $_SESSION['attendee_info_list']['attendee_comments'];
        $attendee_num = count($attendee_names);
        $attendee_status_list = $_SESSION['attendee_status_list'];
    }else{
        $attendee_num = 0;
    }

    //for creator
    $isCreator = false;
    $isCreator = true; // keep it 'true' for testing
    if(isset($_COOKIE['creator_event_id_list'])){
        $event_id_list = json_decode($_COOKIE['creator_event_id_list'],true);
        if(in_array($event_id,$event_id_list)){
            $isCreator = true;
        }
    }
    //for form
    $view_attendee_form = $_SESSION['view_attendee_form'];
    if(isset($_SESSION['attendee_form_action'])){
        $attendee_form_action = $_SESSION['attendee_form_action'];
    }
    if(isset($_SESSION['modifying_attendee_name'])){
        $modifying_attendee_name = $_SESSION['modifying_attendee_name']; 
        $modifying_attendee_index = array_search($modifying_attendee_name ,$attendee_names); // return int for index,or FALSE when failed
    }else{
        $modifying_attendee_name = '';
        $modifying_attendee_index = false;
    }
?>

<div class="container">
    <!-- event name part -->
    <div class="container-fluid mt-3">
        <div class="row border-2 border-bottom border-dark">
            <div class="col-md-8">
                <p class="fs-3 fw-bold"><?php echo $event_name; ?></p>
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
            <p class="text-start pt-1 fs-5">
                <?php 
                  if($isCreator){
                    echo "解答者数{$attendee_num}人、 あなたがイベントの幹事です。";
                  }else{
                    echo "解答者数{$attendee_num}人、 「出欠を入力する」ボタンから出欠を入力しましょう。";
                  }
                ?>
            </p> 
        </div>
    </div>
    
    <!-- event memo part -->
    <div class="container-fluid mt-5">
        <div>
            <p class="fs-3 fw-bold">イベントの詳細説明</p>
        </div>
        <div>
            <p class="text-start pt-1 fs-5"><?php echo $event_memo; ?></p> 
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
                    <?php if($attendee_num > 0): ?>
                        <?php foreach ($attendee_names as $attendee_name): ?>
                            <th scope="col">
                            <a class="btny" href="controller/modify_attendee.php?attendee_name=<?php echo urlencode($attendee_name); ?>" role="button">
                                <?php echo $attendee_name; ?>
                            </a>
                            </th>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                    $cross_num = [];
                    $tangle_num = [];
                    $circle_num = [];
                    foreach ($event_dates as $date){
                        $cross_num[$date] = 0;
                        $tangle_num[$date] = 0;
                        $circle_num[$date] = 0;
                        if($attendee_num > 0){
                            foreach ($attendee_status_list[$date] as $status) {
                                switch ($status) {
                                    case 0: $cross_num[$date]++; break;
                                    case 1: $tangle_num[$date]++; break;
                                    case 2: $circle_num[$date]++; break;
                                    default: break;
                                }
                            }
                        }
                    }
                    unset($dates_of_max_circle);
                    $dates_of_max_circle = array_keys($circle_num,max($circle_num));
                    $tangle_of_max_circle = [];
                    if(count($dates_of_max_circle) != 1){
                        foreach($dates_of_max_circle as $date_of_circle){
                            $tangle_of_max_circle[$date_of_circle] = $tangle_num[$date_of_circle]; 
                        }
                        $highlight_dates = array_keys($tangle_of_max_circle, max($tangle_of_max_circle));
                    }else{
                        $highlight_dates = $dates_of_max_circle;
                    }
                    print_r($highlight_dates);
                ?>
                <?php foreach ($event_dates as $date): ?>
                <tr <?php if(array_search($date, $highlight_dates)!==false){echo 'class="table-active"';} ?>>
                    <td><?php echo $date; ?></td>
                    <td><?php echo $circle_num[$date]; ?></td>
                    <td><?php echo $tangle_num[$date]; ?></td>
                    <td><?php echo $cross_num[$date]; ?></td>
                    <?php 
                        if($attendee_num > 0){
                            foreach ($attendee_names as $attendee_name){
                                if(array_key_exists($attendee_name,$attendee_status_list[$date])){
                                    $mark = show_mark($attendee_status_list[$date][$attendee_name]);
                                    echo "<td>".$mark."</td>";
                                }else{
                                    echo "<td></td>";
                                }
                            }
                        }
                    ?>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td>コメント</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <?php if($attendee_num > 0): ?>
                        <?php foreach ($attendee_comments as $comment): ?>
                            <td><?php echo $comment; ?></td>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- attendee enroll part -->
    <?php if ($view_attendee_form): ?>
        <div class="row justify-content-center mt-5 mb-5">
            <div class="col-md-8">
                <div class="border-2 border-bottom border-dark">
                    <p class="fs-3 fw-bold">出欠を入力する</p>
                </div>
                <form action="<?php echo "controller/".$attendee_form_action; ?>" method="POST">
                    <div class="mt-3">
                        <label for="attendeeName" class="form-label">名前</label>
                        <div id="formatHelp" class="form-text">絵文字は使用できません。</div>
                        <input type="text" class="form-control" id="attendeeName" name="attendeeName" aria-describedby="formatHelp" value="<?php echo $modifying_attendee_name; ?>">
                    </div>
                    <div class="mt-4"> 
                        <p>日にち候補</p> 
                        <div class="row g-3">
                        <?php foreach ($event_dates as $date): ?>
                            <?php 
                                if($modifying_attendee_index !== FALSE
                                    && array_key_exists($modifying_attendee_name,$attendee_status_list[$date])){
                                    $modifying_status = $attendee_status_list[$date][$modifying_attendee_name];
                                }else{
                                    $modifying_status = 2;
                                }
                            ?>
                            <div class="col-3"><?php echo $date ?></div>
                            <div class="btn-group col-9" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="checkbox-<?php echo $date ?>" id="btnradio1-<?php echo $date ?>" value="2" autocomplete="off" <?php if(isset($modifying_status)&&$modifying_status ==2){echo "checked";}?>>
                                <label class="btn btn-outline-primary" for="btnradio1-<?php echo $date ?>">◎</label>

                                <input type="radio" class="btn-check" name="checkbox-<?php echo $date ?>" id="btnradio2-<?php echo $date ?>" value="1" autocomplete="off" <?php if(isset($modifying_status)&&$modifying_status == 1){echo "checked";}?>>
                                <label class="btn btn-outline-primary" for="btnradio2-<?php echo $date ?>">△</label>

                                <input type="radio" class="btn-check" name="checkbox-<?php echo $date ?>" id="btnradio3-<?php echo $date ?>" value="0" autocomplete="off" <?php if(isset($modifying_status)&&$modifying_status == 0){echo "checked";}?>>
                                <label class="btn btn-outline-primary" for="btnradio3-<?php echo $date ?>">✕</label>
                            </div>
                        <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="mt-4 mb-4">
                        <label for="comment" class="form-label">コメント</label>
                        <input type="text" class="form-control" id="comment" name="comment" value="<?php if($modifying_attendee_index !== FALSE){echo $attendee_comments[$modifying_attendee_index];} ?>">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">入力する</button>
                        <a class="btn btn-danger" href="controller/view_attendee.php" role="button">キャンセル</a>
                    </div>
                </form>
            </div>
        </div>
    <?php else: ?>
        <div class="mt-3 text-center">
            <a class="btn btn-primary" href="controller/create_attendee.php" role="button">出欠を入力する</a>
        </div>
    <?php endif; ?>
</div>
