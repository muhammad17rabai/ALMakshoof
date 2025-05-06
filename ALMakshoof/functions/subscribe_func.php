<?
    require_once 'connect.php';
    require_once 'functions.php';
    class Newsubscribe extends DB
    {
        function form_sub()
        {
            if (validation($_GET['user_id']) != null) {
                $user_id = validation($_GET['user_id']);
                $url = '&&user_id='.$user_id;
            }else{
                $user_id = validation($_SESSION['id']);
            }
            $query = $this->connect()->query("SELECT * FROM subscribe WHERE user_id = '$user_id'");
            $username = $this->connect()->query("SELECT * FROM users WHERE id = '$user_id'")->fetch_assoc()['username'];
            while ($fetch = $query->fetch_assoc()) {
                if ($fetch['status'] < 3) {
                    $q = 0;
                }else {
                    $q = 1;
                }
            }
            if ($query->num_rows == 0) {
                $q = 1;
            }
            if ($q == 1) {?>
                <div class="col-xs-12 m-5 mt-10 p-15 form-add">
                    <div class="alert alert-warning border">
                        <i class="fa fa-exclamation-triangle"></i>
                        <p> لتفعيل الاشتراك يرجى تحويل المبلغ من خلال احدى طرق الدفع الموجودة في القائمة , ثم قم باختيار طريقة الدفع التي قمت بالتحويل من خلالها 
                            وقم برفع صورة لاشعار الدفع الذي يؤكد نجاح عملية تحويل المبلغ.
                        </p>
                    </div>
                    <div class="form-group mt-10">
                        <a href="#new_subscribe" class="btn btn-info btn_subscribe" data-toggle="modal"><i class="fa fa-plus"></i> اشتراك جديد </a>
                    </div>
                    <!----------------------------------------- Modal Subscribe --------------------------------------->
                    <div class="modal text-center" id="new_subscribe" tabindex="-1" role="dialog">
                        <div class="form-add">
                            <div class="modal-content text-right">
                                <div class="border">
                                    <form action="index.php?page=newsubscribe<? echo $url;?>" method="post" enctype="multipart/form-data">
                                        <div class="alert btn-info">
                                            <h6> نموذج الاشتراك </h6>
                                        </div>
                                        <div class="p-15">
                                            <div class="form-group pagename">
                                                <i class="fa fa-user bg"></i>&nbsp;&nbsp;<label for="name"> اسم الشخص الذي قام بتحويل المبلغ : </label>
                                                <input type="text" class="form-control" name="name" id="name" value="<? echo $username;?>">
                                            </div>
                                            <div class="form-group">
                                                <i class="fa fa-credit-card"></i>&nbsp;&nbsp;<label for="period">   طريقة الدفع : </label>
                                                <select name="payment_method" id="payment_method" class="form-control">
                                                    <option value=""> اختار طريقة الدفع </option>
                                                    <!--<option value="bop"> بنك فلسطين </option>-->
                                                    <option value="jawwal"> جوال باي </option>
                                                    <option value="reflect"> ريفلكت </option>
                                                    <option value="iburaq"> ايبان (IBURAQ) </option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <span class="alert-success p-10"> رقم الهاتف لتحويل المبلغ : 0000000000</span>
                                            </div>
                                            <div class="form-group">
                                                <i class="fa fa-calendar"></i>&nbsp;&nbsp;<label for="period">  مدة الاشتراك : </label>
                                                <select name="sub_period" class="form-control" id="sub_period">
                                                    <option value=""> اختار المدة </option>
                                                    <option value="3"> 3 أشهر </option>
                                                    <option value="6"> 6 أشهر </option>
                                                    <option value="1"> سنة </option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <i class="fa fa-money"></i>&nbsp;<span for="price"> رسوم الاشتراك : </span>
                                                <span class="alert-info p-5 border-radius sub_price"> 0 شيكل </span>
                                                <input type="hidden" name="price" id="total">
                                            </div><hr>
                                            <i class="fa fa-image"></i>&nbsp;&nbsp;<label><b> صورة اشعار الدفع :</label>
                                            <div class="col-xs-12 form-group form-add-screenshot p-10" id="form-add-screenshot">
                                                <input type="file" class="form-control imgpage" name="img_pay" id="img_pay">
                                                <span> اختيار الصور <i class="fa fa-upload"></i></span><br> 
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-info" name="addnew_sub"> حفظ </button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">اغلاق</button>
                                        </div>
                                        <div class="alert payment_method">
                                            <div class="form-group">
                                                <!--
                                                    <div class="col-xs-3 bb text-center form-group">
                                                        <img src="./icons/bop.png" width="60">
                                                    </div>
                                                -->
                                                <div class="col-xs-4 bb text-center form-group">
                                                    <img src="./icons/jawwal.png" width="30">
                                                </div>
                                                <div class="col-xs-4 bb text-center form-group">
                                                    <img src="./icons/reflect.png" width="27">
                                                </div>
                                                <div class="col-xs-4 bb text-center form-group">
                                                    <img src="./icons/IBURAQ.png" width="60">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!----------------------------------------- END Modal Subscribe --------------------------------------->
                </div><hr>
             <?}}
        function addnew_sub()
        {
            if (validation($_GET['user_id']) != null) {
                $user_id = validation($_GET['user_id']);
            }else{
                $user_id = validation($_SESSION['id']);
            }
            $name = validation($_POST['name']);
            $period = validation($_POST['sub_period']);
            $payment_method = validation($_POST['payment_method']);
            $total = validation($_POST['price']);
            $start_date = date('Y-m-d G:i:s');
                if($period > 0){
                    if($period == 3){
                        $end_date = date('Y-m-d G:i:s',date(strtotime("+3 month")));
                    }elseif($period == 6){
                        $end_date = date('Y-m-d G:i:s',date(strtotime("+6 month")));
                    }elseif($period == 1){
                        $end_date = date('Y-m-d G:i:s',date(strtotime("+1 year")));
                }
            }
            $image_name = $_FILES['img_pay']['name'];
            $image_tmp = $_FILES['img_pay']['tmp_name'];
            $image_size = $_FILES['img_pay']['size'];

            $val = validate_subscribe($name , $period , $payment_method);
            $val_img = validate_img($image_name , $image_size , 1 , 1);
            $newimgname = rand(1000, 10000) . $image_name;
            $rows = $this->connect()->query("SELECT * FROM subscribe WHERE user_id = '$user_id' && status < 3")->num_rows;
            $role = new role;
            if ($role->r('role') == 2) {
                $status =  1;
                $active = 1;
            }else{
                $status =  0;
                $active = 0;
            }
            if (isset($_POST['addnew_sub'])){
                if(empty($val)){
                    if(empty($val_img)){
                        if($rows < 1){
                            $sql = $this->connect()->query("INSERT INTO subscribe (user_id,name,period,pay_method,total,start,end,images,status,active)
                            VALUES('$user_id','$name','$period','$payment_method','$total','$start_date','$end_date','$newimgname','$status','$active')");
                        if ($sql) {
                            move_uploaded_file($image_tmp,'./images/sub_img/'.$newimgname);
                            box_alert('success','تم تقديم طلب الاشتراك بنجاح');
                            $mid = $this->connect()->query("SELECT MAX(id) as id FROM subscribe WHERE user_id = $user_id");
                            $lid = $mid->fetch_assoc();
                            $sub_id =  $lid['id'];
                            $m = new Notifications;
                            $m->save_notification($sub_id,$user_id,'admin','لديك طلب اشتراك جديد ','secondary',2);
                        }else{
                            box_alert('danger','خطأ ! لم يتم تقديم طلب الاشتراك');
                        }
                    }else{
                        box_alert('danger','خطأ ! لا يمكن تقديم طلب الاشتراك مرة أخرى');
                    }    
                    }else {
                        box_alert('danger',$val_img);
                    }
                }else{
                    box_alert('danger',$val);
                    echo '<input type="hidden" id="err_form" value="err_form">';
                }  
            }
        }

        function getorder_subscribe()
        {   
            $role = new role;
            if (validation($_GET['user_id']) != null) {
                $user_id = validation($_GET['user_id']);
            }else{
                $user_id = validation($_SESSION['id']);
            }
            $page = validation($_GET['page']);
            if(validation($_GET['p']) != null){
                $p = validation($_GET['p']);
            }
            if ($page == 'subscribe' || $p == 'member_sub') {
                $sql = $this->connect()->query("SELECT * FROM subscribe WHERE user_id = '$user_id' ORDER BY id DESC");
            }else if($page == 'newsubscribe') {
                $sql = $this->connect()->query("SELECT * FROM subscribe WHERE user_id = '$user_id' && status < 3  ORDER BY id DESC");
            }else if($page == 'Allsubscribe') {
                $st = validation($_GET['st']);
                if ($st == null || $st == 5) {
                    $sql = $this->connect()->query("SELECT * FROM subscribe  ORDER BY id DESC");
                }else{
                    $sql = $this->connect()->query("SELECT * FROM subscribe WHERE status = '$st'  ORDER BY id DESC");
                } 
            }
            $rows = ($sql)->num_rows;
        if ($rows == 0 && $page != 'newsubscribe') {
            echo "<div class='col-xs-12 mt-15'>";
                box_alert('secondary','لا توجد نتائج');
            echo "</div>";

            if (validation($_GET['p']) == 'member_sub') {
                $user_id = validation($_GET['user_id']);
                echo '<div class="form-group">
                        <a href="index.php?page=newsubscribe&&user_id='.$user_id.'" class="btn btn-info m-5 p-10">اضافة اشتراك جديد</a>
                    </div>';
            }
        }
        while ($fetch = $sql->fetch_assoc()) {
        switch ($fetch['pay_method']) {
            case 'bop':
                $pay =  'بنك فلسطين';
                $img = '<img src="icons/bop.png" width="15">';
                break;
            case 'jawwal':
                $pay = ' جوال باي ';
                $img = '<img src="icons/jawwal.png" width="15">';
                break;
            case 'reflect':
                $pay =' ريفلكت';
                $img = '<img src="icons/reflect.png" width="15">';
                break;
            case 'iburaq':
                $pay ='ايبان(IBURAQ)';
                $img = '<img src="icons/iBURAQ.png" width="15">';
                break;
        }
        switch ($fetch['period']) {
            case '3':
                $p = '3 أشهر';
                break;
            case '6':
                $p = '6 أشهر';
                break;
            case '1':
                $p = 'سنة';
                break;
            }
        switch ($fetch['status']) {
            case 0:
                $msg = '<i class="fa fa-exchange"></i>&nbsp;<span class="text-info"><i>  بانتظار الموافقة </i></span>';
                break;
            case 1:
                $msg = '<i class="fa fa-check"></i>&nbsp;<span class="text-success"><i> تم الاشتراك </i></span>';
                break;
            case 2:
                $msg = '<a href="#show-result-pending'.$fetch['id'].'" class="text-warning" data-toggle="modal" data-id='.$fetch['id'].'><i class="fa fa-exclamation-triangle"></i>&nbsp;
                        <span class="text-warning"><i> معلق </i></span></a>';
                break;
                case 3:
                $msg = '<a href="#show-result-reject'.$fetch['id'].'" class="text-danger" data-toggle="modal" data-id='.$fetch['id'].'><i class="fa fa-close"></i>&nbsp;
                        <span class="text-danger"><i>  مرفوض </i></span></a>';
                break;
                case 4:
                    $msg = '<i class="fa fa-check"></i>&nbsp;<span class="text-success"><i>  مكتمل </i></span>';
                    break;
            }
        ?>
        <main>
            <div class="col-md-4 col-sm-6 col-xs-12 m-5 mt-10 form-add">
                <div class="row">
                    <div class="card-header alert-info">
                        <h6> حالة الطلب :  <? echo $msg;?></h6>
                    </div>
                    <div class="col-xs-12 all-info">
                        <div class="sub-info">
                            <div class="col-xs-12 p-5 mt-5">
                                <?
                                    if ($role->r('role') == 2) {
                                        echo '<h4><a href="index.php?page=Member_details&&user_id='.$fetch['user_id'].'&&p=member_info">'.$fetch['name'].'&nbsp;<i class="fa fa-user"></i></a></h4>';
                                    }else{
                                        echo '<h4><label><i class="fa fa-user"></i></label>&nbsp;<label>'.$fetch['name'].'</label></h4>';
                                    }
                                ?>
                            </div>
                            <div class="col-xs-12 p-5">
                               <label> مدة الاشتراك :  &nbsp;</label><? echo $p;?>
                            </div>
                            <div class="col-xs-12 p-5">
                                <label> رسوم الاشتراك : <i class="fa fa-money text-success"></i>&nbsp;</label><? echo $fetch['total'];?> شيكل
                            </div>
                            <div class="col-xs-12 p-5">
                                <label>  طريقة الدفع : </label>&nbsp;<? echo $img.'&nbsp;'.$pay;?>
                            </div>
                            <div class="col-xs-12 p-5">
                                <label>  اشعار الدفع : </label>&nbsp;<a href="#zoom-img"><img src="./images/sub_img/<? echo $fetch['images'];?>" class="myImg" data-img="#myModal-img<? echo $fetch['id']?>" width="30"></a>
                            </div>
                            <div class="col-xs-6 text-center p-5">
                                <h6> تاريخ الاشتراك :  </h6>
                                <div class="alert-info p-3 border-radius">&nbsp;<? echo date('Y-m-d',strtotime($fetch['start']));?><br>
                                    <? echo date('h:i:s-a',strtotime($fetch['start']));?>
                                </div>
                            </div>
                            <div class="col-xs-6 text-center p-5">
                                <h6> تاريخ الانتهاء :  </h6>
                                <div class="alert-danger p-3 border-radius">&nbsp;<? echo date('Y-m-d',strtotime($fetch['end']));?><br>
                                    <? echo date('h:i:s-a',strtotime($fetch['end']));?>
                                </div>
                            </div>
                            <?
                                if ($fetch['status'] == 1 || $fetch['status'] == 4) {?>
                                    <div class="col-xs-12 p-5">
                                        <input type="hidden" id="end_date" value="<? echo date('Y-m-d h:i:s',strtotime($fetch['end']));?>">
                                        <label>المتبقي للاشتراك : &nbsp;<label class="p-5" id="remain_sub"></label></label>
                                    </div>
                                <?}
                            ?>
                        </div>
                    </div>
                </div><hr>
                <?
                if(($fetch['status'] == 0 || $fetch['status'] == 2) && validation($_GET['page'] != 'Allsubscribe')){
                    echo '  
                        <div class="m-5 p-5 order-btn">
                            <a href="#edit_sub'.$fetch['id'].'" class="btn-info" data-toggle="modal"> تعديل </a>
                            <a href="#delete-order'.$fetch['id'].'" class="btn-danger" id="del_page" data-toggle="modal"> الغاء </a>
                        </div>';
                }if(validation($_GET['page'] == 'Allsubscribe')){?>
                    <div class="mt-15 order-btn text-center">
                        <a href="#edit_sub<? echo $fetch['id'];?>" class="btn-info" data-toggle="modal"><i class="fa fa-edit"></i> تعديل  </a>
                        &nbsp;<a href="#delete-order<? echo $fetch['id'];?>" class="btn-danger" data-toggle="modal"><i class="fa fa-trash"></i> حذف </a>
                    </div>
                    <div class="mt-15 order-btn text-center">
                        <?
                            if($fetch['status'] != 1){
                                echo '<a href="#acceppt-order'.$fetch['id'].'" class="btn-success" data-toggle="modal"><i class="fa fa-check"></i> قبول </a>';
                            }
                        ?>
                        <a href="#pendding-order<? echo $fetch['id'];?>" class="btn-warning" data-toggle="modal"><i class="fa fa-exclamation-triangle"></i> تعليق </a>
                        <a href="#reject-order<? echo $fetch['id'];?>" class="btn-danger" data-toggle="modal"><i class="fa fa-close"></i> رفض </a>
                    </div><hr>
                <?}?>
            </div>
        </main>
        <!----------------------------------------- Modal show edit subscribe --------------------------------------->
            <!-- Modal details -->
            <div class="modal text-center" id="edit_sub<? echo $fetch['id'];?>" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content text-right">
                        <form action="index.php?page=<? echo validation($_GET['page']);?>" method="post" enctype="multipart/form-data">
                            <div class="alert btn-info">
                                <a class="clos" data-dismiss="modal" aria-hidden="true"><b>x</b></a>
                                <h6> تعديل طلب الاشتراك </h6>
                            </div>
                            <div class="p-15">
                                <div class="form-group pagename">
                                    <i class="fa fa-user bg"></i>&nbsp;&nbsp;<label for="name"> اسم الشخص الذي قام بتحويل المبلغ : </label>
                                    <input type="text" class="form-control" name="edit_name" id="name" value="<? echo $fetch['name'];?>">
                                </div>
                                <div class="form-group">
                                    <i class="fa fa-credit-card"></i>&nbsp;&nbsp;<label for="period">   طريقة الدفع : </label>
                                    <select name="edit_payment_method" id="payment_method" class="form-control">
                                        <option value="<? echo $fetch['pay_method'];?>"> <? echo $pay;?></option>
                                        <!--<option value="bop"> بنك فلسطين </option>-->
                                        <option value="jawwal"> جوال باي </option>
                                        <option value="reflect"> ريفلكت </option>
                                        <option value="iburaq"> ايبان (IBURAQ) </option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <span class="alert-success p-10"> رقم الهاتف لتحويل المبلغ : 0000000000</span>
                                </div>
                                <div class="form-group">
                                    <i class="fa fa-calendar"></i>&nbsp;&nbsp;<label for="period">  مدة الاشتراك : </label>
                                    <select name="edit_sub_period" class="form-control" id="sub_period">
                                        <option value="<? echo $fetch['period'];?>"><? echo $p;?></option>
                                        <option value="3"> 3 أشهر </option>
                                        <option value="6"> 6 أشهر </option>
                                        <option value="1"> سنة </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <i class="fa fa-money"></i>&nbsp;<span for="price"> رسوم الاشتراك : </span>
                                    <span class="alert-info p-5 border-radius sub_price"> <? echo $fetch['total'];?> شيكل </span>
                                    <input type="hidden" name="edit_price" id="total" value="<? echo $fetch['total'];?>">
                                </div><hr>
                                <i class="fa fa-image"></i>&nbsp;&nbsp;<label><b> صورة اشعار الدفع :</label>
                                <img src="./images/sub_img/<? echo $fetch['images'];?>" width="30"></a>
                                <div class="row form-group form-add-screenshot p-10" id="form-add-screenshot">
                                    <input type="file" class="form-control imgpage" name="edit_img_pay" id="img_pay">
                                    <span> اختيار الصور <i class="fa fa-upload"></i></span><br>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-info" name="renew_sub"> حفظ </button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">اغلاق</button>
                            </div>
                            <input type="hidden" name="id" value="<? echo $fetch['id'];?>">
                            <input type="hidden" name="img" value="<? echo $fetch['images'];?>">
                        </form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal details-->
            <!-------------------------------------- End show editt subscribe modal ---------------------------------------------->
            <!--============================== modal zoom img ===========================-->
            <div id="myModal-img<? echo $fetch['id'];?>" class="modal-img m-0">
                <a href="#detailspage<? echo $fetch['id'];?>" class="close close-img" data-toggle="modal" aria-hidden="true" data-details="#detailspage<? echo $fetch['id'];?>"><b>x</b></a>
                <img class="modal-content-img img01">
            </div>
            <!--============================== End modal zoom img ===========================-->
        <? 
        $id = $fetch['id'];
        $user_id = $fetch['user_id'];
        $data_t = 'subscribe';
        $data_img = '';
        $type = 'subscribe';
        $data_routs = validation($_GET['page']);
        $modals = new Modalss;
        $modals->modals($id,$user_id,$data_t,$data_img,$data_routs,$type);
    }
    }

    function edit_sub()
    {
        $id = validation($_POST['id']);
        $user_id = validation($_SESSION['id']);
        $name = validation($_POST['edit_name']);
        $period = validation($_POST['edit_sub_period']);
        $payment_method = validation($_POST['edit_payment_method']);
        $total = validation($_POST['edit_price']);
        $start_date = date('Y-m-d G:i:s');
            if($period > 0){
                if($period == 3){
                    $end_date = date('Y-m-d G:i:s',date(strtotime("+3 month")));
                }elseif($period == 6){
                    $end_date = date('Y-m-d G:i:s',date(strtotime("+6 month")));
                }elseif($period == 1){
                    $end_date = date('Y-m-d G:i:s',date(strtotime("+1 year")));
            }
        }
        $image_name = $_FILES['edit_img_pay']['name'];
        $image_tmp = $_FILES['edit_img_pay']['tmp_name'];
        $image_size = $_FILES['edit_img_pay']['size'];

        $val = validate_subscribe($name , $period , $payment_method);
        $val_img = validate_img($image_name , $image_size , 0 , 1);
        if ($image_name == null) {
            $newimgname = validation($_POST['img']);
        }else{
            $newimgname = rand(1000, 10000) . $image_name;
        }
        $rows = $this->connect()->query("SELECT * FROM subscribe WHERE user_id = '$user_id'")->num_rows;
        if (isset($_POST['renew_sub'])){
            if(empty($val)){
                if(empty($val_img)){
                    $sql = $this->connect()->query("UPDATE subscribe SET name='$name', period='$period', pay_method='$payment_method', total='$total', 
                            start='$start_date', end='$end_date', images='$newimgname' WHERE id = '$id'");
                if ($sql) {
                    move_uploaded_file($image_tmp,'./images/sub_img/'.$newimgname);
                    box_alert('success','تم تعديل طلب الاشتراك بنجاح');
                }else{
                    box_alert('danger','خطأ ! لم يتم تعديل طلب الاشتراك');
                }    
                }else {
                    box_alert('danger',$val_img);
                }
            }else{
                box_alert('danger',$val);
                echo '<input type="hidden" id="err_form" value="err_form">';
            }  
        }
    }
}
?>    