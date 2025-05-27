<?php
    require_once 'connect.php';
    require_once 'functions.php';
    require_once 'notifications_func.php';
class Rating extends DB
{
    function addrating()
    {
        $user_id = $_SESSION['id'];
        $type = validation($_GET['type']);
        if ($type == 'page') {
            $item_id = validation($_GET['page_id']);
            $url = 'page_id='.$item_id.'&&type=page';
            $receiver = $this->connect()->query("SELECT user_id FROM pages WHERE id = '$item_id'")->fetch_assoc()['user_id'];
            $body = " تم اضافة تقييم جديد على الصفحة الخاصة بك ";
            $t = 0;
        }elseif($type == 'product'){
            $item_id = validation($_GET['product_id']);
            $url = 'product_id='.$item_id.'&&type=product';
            $receiver = $this->connect()->query("SELECT user_id FROM products WHERE id = '$item_id'")->fetch_assoc()['user_id'];
            $body = " تم اضافة تقييم جديد على المنتج الخاصة بك ";
            $t = 1;
        }elseif($type == 'broker'){
            $item_id = validation($_GET['broker_id']);
            $url = 'broker_id='.$item_id.'&&type=broker';
            $receiver = $this->connect()->query("SELECT user_id FROM brokers WHERE id = '$item_id'")->fetch_assoc()['user_id'];
            $body = "تم اضافة تقييم جديد على حساب الوسيط الخاص بك ";
            $t = 2;
        }
        $date = time();
        $rate = validation($_POST['rating']);
        $notes = validation($_POST['comment_rate']);
        if (isset($_POST['save_rate'])) {
            if ($rate != null && $notes != null) {
                $sql = $this->connect()->query("INSERT INTO rating (user_id,item_id,date_created,rate,notes,type)
                    VALUES('$user_id','$item_id','$date','$rate','$notes','$type')");
                if ($sql) {
                    $m = new Notifications;
                    $m->save_notification($item_id,$user_id,$receiver,$body,'secondary',$t);
                    header("location:index.php?page=Allrating&&$url");
                }else{
                    box_alert('danger','حطأ ! لم يتم اضافة التقييم');
                }
            }else{
                    box_alert('danger','يرجى ملئ جميع الحقول');
            }
        }
    }
    
    function getallrating()
    {
        $role = new role;
        $user_id = $_SESSION['id'];
        $type = validation($_GET['type']);
        if ($type == 'page') {
            $item_id = validation($_GET['page_id']);
        }elseif($type == 'product') {
            $item_id = validation($_GET['product_id']);
        }elseif($type == 'broker') {
            $item_id = validation($_GET['broker_id']);
        }
        $sql = $this->connect()->query("SELECT r.* , u.username,avatar FROM rating AS r JOIN users AS u ON r.user_id = u.id WHERE r.item_id = $item_id && r.type = '$type' ORDER BY r.id DESC");
        $rows = $sql->num_rows;
        echo '<h3 class="h-title">'.$rows.' تقييم</h3>';
        if($rows == 0){
            box_alert('secondary','لا يوجد تقييم');
        }
        while ($fetch = $sql->fetch_assoc()) {?>
        <div class="review-single pt-30">
            <div class="row media">
                <div class="col-xs-12">
                    <div class="review-wrapper clearfix">
                        <ul class="list-inline">
                            <li>
                               <img src="<? echo $fetch['avatar'];?>" class="rounded-circle" width="35" style="height:35px;"> <span class="review-holder-name"><b><? echo $fetch['username'];?></b></span>&nbsp;<i class="review-date mr-1"><? get_date($fetch['date_created']);?></i>
                            </li><br>
                            <li>
                                <div class="rating">
                                    <span class="rating-os">
                                        <?
                                            show_rating($fetch['rate']);
                                        ?>
                                    </span>
                                </div>
                            </li>
                        </ul>
                        <p class="copy"><? echo $fetch['notes'];?></p><hr>
                        <?
                        if($fetch['user_id'] == $user_id || $role ->r("role") == 2){?>
                            <div class="mt-15 order-btn">
                                <a href="#edit-rate<? echo $fetch['id'];?>" data-toggle="modal"><i class="fa fa-edit"></i> تعديل </a>
                                <a href="#delete-rate<? echo $fetch['id'];?>" data-toggle="modal"><i class="fa fa-trash text-danger"></i> حذف </a>
                            </div>
                        <? } ?>
                    </div>
                </div>
            </div>
        </div>
        <!------------------------------------- edit rating  ------------------------------>
        <!----------------------------------------- Modal edit rating --------------------------------------->
        <!-- Modal details -->
        <div class="modal text-center" id="edit-rate<? echo $fetch['id'];?>" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content text-right">
                    <div class="modal-header">
                        <button type="button" class="clos" data-dismiss="modal" aria-hidden="true"><b>x</b></button>
                        <h5> تعديل البيانات </h5>
                    </div>
                    <div class="p-5" id="result-edit"></div>
                    <div class="p-10">
                        <div class="form-group">
                            <label for="rating">  التقييم : </label>
                            <div class="addrating">
                                <input type="hidden" name="rating" id="rating" value="<? echo $fetch['rate'];?>">
                                    <?
                                        show_rating($fetch['rate']);
                                    ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" id="comment_rate" placeholder="اكتب تعليق ...." rows="6"><? echo $fetch['notes'];?></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info save_edit_rate" data-id="<? echo $fetch['id'];?>"> حفظ </button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">اغلاق</button>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal details-->
        <!----------------------------------------- Modal delete rating --------------------------------------->
        <!-- Modal details -->
        <div class="modal text-center" id="delete-rate<? echo $fetch['id'];?>" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content text-right">
                    <div class="modal-header">
                        <button type="button" class="clos" data-dismiss="modal" aria-hidden="true"><b>x</b></button>
                        <h5> حذف التقييم </h5>
                    </div>
                    <div class="p-10">
                        <h6> هل انت متأكد من حذف هذا التقييم </h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger delete_rate" data-id="<? echo $fetch['id'];?>"> حذف </button>
                        <button type="button" class="btn btn-info" data-dismiss="modal">اغلاق</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal details-->
        <!-------------------------------------- End delete-rating modal ---------------------------------------------->
    <? }
    }
    function edit_rating()
    {
        $id = validation($_POST['id']);
        $rate = validation($_POST['rate']);
        $notes = validation($_POST['notes']);
        if ($id != null && $rate != null && $notes != null) {
            $query = $this->connect()->query("UPDATE rating SET rate='$rate', notes='$notes' WHERE id = '$id'");
            if($query){
                echo '<input type="hidden" id="success" value="success">';
            }else{
                box_alert('danger','حطأ ! لم يتم اضافة التقييم');
            }
        }else{
                box_alert('danger','يرجى ملئ جميع الحقول');
        }
    }
    function del_rating()
    {
        $id = validation($_POST['id']);
        if ($id != null) {
            $query = $this->connect()->query("DELETE FROM rating WHERE id = '$id'");
            if($query){
                echo '<input type="hidden" id="success" value="success">';
            }else{
                box_alert('danger','حطأ ! لم يتم حذف التقييم');
            }
        }
    }
}
$ratingg = new Rating;
$f = validation($_GET['f']);
switch ($f) {
    case 'editrating':
        $ratingg->edit_rating();
        break;
    case 'delrating':
        $ratingg->del_rating();
        break;
    default:
        # code...
        break;
}