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
        header("Location: ../index.php");
        exit();
    }
    $event_id = $_GET['eid'];

    if(!isset($_SESSION['event_info'])){
        $_SESSION['event_id'] = $event_id;
        header("Location: controller/view_attendee.php");
        exit();
    }

    if(isset($_COOKIE['event_id'])){
        $_SESSION['isCreator'] = True;
    }else{
        $_SESSION['isCreator'] = False;
    }

    $event_name = $_SESSION['event_info']['event_name'];
    $event_memo = $_SESSION['event_info']['memo'];
    $event_dates = $_SESSION['event_info']['event_dates'];

    if(!empty($_SESSION['attendee_info_list'])){
        $attendee_names = $_SESSION['attendee_info_list']['attendee_names'];
        $attendee_comments = $_SESSION['attendee_info_list']['attendee_comments'];
        $attendee_num = count($attendee_names);
        $thistime_attendee_statues = $_SESSION['thistime_attendee_statues'];
    }else{
        $attendee_num = 0;
    }

    // print_r($_SESSION['event_info']);
    // unset($_SESSION['event_info']); //testing
    // print_r($_SESSION['attendee_status_list']);
    // unset($_SESSION['attendee_status_list']); //testing


    $isCreator = $_SESSION[ 'isCreator' ];
    $view_mode = $_SESSION['view_mode'];

    //for form
    $form_action = $_SESSION['form_action'];
    if(empty($_SESSION['modifying_attendee_name'])){
        $modifying_attendee_name = NULL;
        $modifying_attendee_index = false;
    }else{
        $modifying_attendee_name = $_SESSION['modifying_attendee_name']; 
        $modifying_attendee_index = array_search($modifying_attendee_name ,$attendee_names); // return int for index,or FALSE when failed

    }

    
    $isCreator = True; // need to be delete
    
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
                    <?php if(isset($attendee_names)): ?>
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
                <?php foreach ($event_dates as $date): ?>
                <tr>
                    <td><?php echo $date; ?></td>
                    <?php
                        $cross_num = 0;
                        $tangle_num = 0;
                        $circle_num = 0;
                        if(isset($thistime_attendee_statues)){
                            foreach ($thistime_attendee_statues[$date] as $status) {
                                switch ($status) {
                                    case 0: $cross_num++; break;
                                    case 1: $tangle_num++; break;
                                    case 2: $circle_num++; break;
                                    default: break;
                                }
                            }
                        }
                    ?>
                    <td><?php echo $circle_num; ?></td>
                    <td><?php echo $tangle_num; ?></td>
                    <td><?php echo $cross_num; ?></td>
                    <?php 
                        if(isset($attendee_names)){
                            foreach ($attendee_names as $attendee_name){
                                if(array_key_exists($attendee_name,$thistime_attendee_statues[$date])){
                                    $mark = show_mark($thistime_attendee_statues[$date][$attendee_name]);
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
                    <?php if(isset($attendee_comments)): ?>
                        <?php foreach ($attendee_comments as $comment): ?>
                            <td><?php echo $comment; ?></td>
                        <?php endforeach; ?>
                    <?php endif; ?>
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
                <form action="<?php echo "controller/".$form_action; ?>" method="POST">
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
                                if($modifying_attendee_index != FALSE
                                    && array_key_exists($modifying_attendee_name,$thistime_attendee_statues[$date])){
                                    $modifying_status = $thistime_attendee_statues[$date][$modifying_attendee_name];
                                }
                            ?>
                            <div class="col-3"><?php echo $date ?></div>
                            <div class="btn-group col-9" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="checkbox-<?php echo $date ?>" id="btnradio1-<?php echo $date ?>" value="2" autocomplete="off" <?php if(isset($modifying_status)&&$modifying_status ==2){echo "checked";}else if(!isset($modifying_status)){echo "checked";}?>>
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
                        <input type="text" class="form-control" id="comment" name="comment" value="<?php if($modifying_attendee_index != FALSE){echo $attendee_comments[$modifying_attendee_index];} ?>">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">入力する</button>
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
