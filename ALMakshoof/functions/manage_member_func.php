<?php
require_once 'connect.php';
require_once 'functions.php';
class Manage_member extends DB
{
    public function editmember()
    {
        $name = validation($_POST['name']);
        $email = validation($_POST['email']);
        $phone = validation($_POST['phone']);
        $password = md5("password");
        $city = validation($_POST['city']);
        $address= validation($_POST['address']);
        $gender = validation($_POST['gender']);
        $active = validation($_POST['active']);
        $user_id = validation($_POST['user_id']);
        if (isset($_SESSION['id'])) {
            $role = validation($_POST['role']);
        }else{
            $role = 'member';
        }
        
        $sql_email = "SELECT id,email FROM users WHERE email = '$email' && id != '$user_id'";
        $query_email = $this->connect()->query($sql_email);
        $rows_email = $query_email->num_rows;
        
        $sql_phone = "SELECT id,phone FROM users WHERE phone = '$phone' && id != '$user_id'";
        $query_phone = $this->connect()->query($sql_phone);
        $rows_phone = $query_phone->num_rows;

        $val = validate_user($name,$email,$rows_email,$phone,$rows_phone,$password,$city,$address,$gender,'1',$role);
            //echo $val;
            if(empty($val)){
                $sql ="UPDATE users SET username = '$name', email ='$email', phone='$phone', city='$city', address='$address', gender='$gender', active='$active' WHERE id = '$user_id'";
                $sql_broker = $this->connect()->query("UPDATE brokers SET active = '$active' WHERE user_id = '$user_id'");
                $query = $this->connect()->query($sql);
                if($query){
                    echo '<input type="hidden" id="success" value="success">';
                }else{
                    echo '<input type="hidden" id="error" value="error">';
                }
            }else{
                $color = "danger";
                box_alert($color , $val);
            }
    }

    public function deletemember()
    {
        $user_id = validation($_POST['user_id']);
        if(!empty($user_id)){
            $sql ="DELETE FROM users WHERE id = '$user_id'";
            if($sql){
                $query_b = $this->connect()->query("SELECT * FROM brokers WHERE user_id = '$user_id'");
                $broker_id = $query_b->fetch_assoc()['id'];
                if($query_b->num_rows > 0) {
                  $sql_s = $this->connect()->query("DELETE FROM broker_sites WHERE broker_id = '$broker_id'");
                  $sql_b =$this->connect()->query("DELETE FROM brokers WHERE user_id = '$user_id'");
                }
                $query = $this->connect()->query($sql);
                echo '<input type="hidden" id="success" value="success">';
            }
        }
    }
    function member_pass()
    {
        $user_id = validation($_POST['user_id']);
        $newpass = validation($_POST['newpass']);
        $re_newpass = validation($_POST['re_newpass']);
        if ($newpass == null || $re_newpass == null) {
            box_alert('danger','جميع الحقول مطلوبة !! يرجى ملئ جميع الحقول');
        }elseif(strlen($newpass) < 6){
            box_alert('danger',' كلمة المرور قصيرة !! يجب أن تكون كلمة المرور أكبر من 6 ');
        }elseif($newpass != $re_newpass){
            box_alert('danger','كلمة السر غير متطابقة');
        }else{
            $hashpass = md5($newpass);
            $query = $this->connect()->query("UPDATE users SET password = '$hashpass' WHERE id = '$user_id'");
            if($query){
                box_alert('success','تم تعديل كلمة السر بنجاح');
                echo '<input type="hidden" id="success" value="success">';
            }else{
                box_alert('danger','حطأ ! لم يتم تعديل كلمة السر');
            }
        }
    }
    function member_posts()
    {
        $user_id = validation($_GET['user_id']);
        $type = validation($_GET['type']);
        switch ($type) {
            case 'pages':
                $tt = "الصفحات";
                break;
            case 'products':
                $tt = "المنتجات";
                break;
            default:
                $tt = "الصفحات";
                break;
        }
    ?>
        <div class="col-xs-12 d-flex justify-content-between install">
            <label class="form-control bg-white text-st col-xs-8"> نوع المنشورات  : </label>
            <select class="form-control bg-white" id="type_member_post" data-user_id="<? echo $user_id;?>">
                <option value=""><? echo $tt;?></option>
                <option value="pages">  الصفحات </option>
                <option value="products">  المنتجات </option>
            </select>
        </div>
    <?
        if ($type == 'pages' || $type == null) {
            $sql = $this->connect()->query("SELECT * FROM pages WHERE user_id = '$user_id' order by id desc");
            if($sql->num_rows == 0){
                box_alert('secondary',' لم يتم اضافة اي صفحات ');
            }else{
                while ($fetch = $sql->fetch_assoc()){?>
                    <div class="col-md-4 col-sm-6 col-xs-12 list-page m-5">
                        <div class="p-3">
                            <div class="d-flex flex-row mb-3"><img src="<? echo $fetch['logo'];?>" class="rounded-circle" width="90"  style="height:50px;">
                                <div class="d-flex flex-column mt-3"><a href="<? echo $fetch['page_url'];?>" target="blanek"><h5><? echo $fetch['page_name'];?></h5></a>
                                    <span class="text-secondary"><? get_date($fetch['date_created']);?></span>
                                    <span style="font-size: 1.7rem;"><? show_rating($fetch['rate']);?></span>
                                </div>
                            </div>
                            <h6><? echo substr($fetch['description'],0,150).' .......';?></h6><hr>
                            <div class="d-flex justify-content-between install mt-3">
                                <a href="index.php?page=page_details&&page_id=<? echo $fetch['id'];?>" data-toggle="modal"> عرض تفاصيل أكثر >></a>
                            </div>
                        </div>
                    </div>
                <?}
            }
        }elseif($type == 'products'){
            $sql = $this->connect()->query("SELECT * FROM products WHERE user_id = '$user_id' order by id desc");
            if($sql->num_rows == 0){
                box_alert('secondary',' لم يتم اضافة اي منجات ');
            }else{
                while ($fetch = $sql->fetch_assoc()){?>
                    <main>
                        <div class="col-md-4 col-sm-6 col-xs-12 p-20 mt-5 list-product m-5">
                            <div class="row">
                                <div class="col-xs-3 img-product text-center">
                                    <img src="images/products_img/<? echo $fetch['main_img'];?>" width="80">
                                </div>
                                <div class="col-xs-9 all-info">
                                    <div class="product-info">
                                        <h4><? echo $fetch['name'];?></h4>
                                        <span class="text-secondary"><? get_date($fetch['date_created']);?></span><br>
                                        <label class="rating"> <? show_rating($fetch['rating']);?></label> / 
                                        <b><label class="mr-10"><i class="fa fa-money text-success">&nbsp;</i> </label>
                                        <label><b> <? echo $fetch['price'];?> شيكل </label></b><br>
                                    </div>
                                </div>
                            </div><hr>
                            <div class="d-flex justify-content-between install mt-3">
                                <a href="index.php?page=Products_details&&product_id=<?echo $fetch['id'];?>" class="show-details-member" data-toggle="modal">  عرض تفاصيل أكثر >></a>
                            </div>
                        </div>
                    </main>
                <?
                }
            }
        }   
    }
    function member_devices()
    {
        $user_id = validation($_GET['user_id']);
        $sql = $this->connect()->query("SELECT * FROM users INNER JOIN devices ON devices.user_id = users.id WHERE user_id = $user_id ORDER BY devices.id DESC");
        if ($sql->num_rows == 0) {
            box_alert('secondary','لا يوجد أجهزة متصلة بهذا الحساب');
        }
        while ($fetch=$sql->fetch_assoc()) {
    ?>
      <main>
            <div class="col-md-4 col-sm-6 col-xs-12 m-5 mt-10 form-add">
                <div class="row">
                    <div class="card-header alert-info">
                        <h6>  معلومات الجهاز : 
                            <label for="active">
                                <?
                                if ($fetch['online_d'] == 0) {?>
                                    <span class="online" style="font-size:12px;color:rgb(150, 150, 150);"><i> نشط <? get_date($fetch['date_updated']); ?></i></span>
                                <?}elseif($fetch['online_d'] == 1 && $fetch['active'] == 1){
                                    echo  ' <i class="fa fa-circle text-success"> نشط الان</i>';
                                }
                                ?>
                            </label>
                        </h6>
                    </div>
                    <div class="col-xs-12 all-info">
                        <div class="sub-info">
                            <div class="col-xs-12 p-5 mt-5">
                                <h6><label> الاسم : </label>&nbsp;<label><? echo $fetch['username']; ?></label></h6>
                            </div>
                            <div class="col-xs-12 p-5">
                               <label> بصمة الجهاز :  &nbsp;</label><? echo $fetch['finger'];?>
                            </div>
                            <div class="col-xs-12 p-5">
                                <label> عنوان ip : &nbsp;</label><? echo $fetch['external_ip'];?> 
                            </div>
                            <div class="col-xs-6 p-5">
                                <label>  نوع الجهاز : </label>&nbsp;<? echo $fetch['type'];?>
                            </div>
                            <div class="col-xs-6 p-5">
                                <label>  نظام التشغيل : </label>&nbsp;<? echo $fetch['os'];?>
                            </div>
                            <div class="col-xs-6 p-5">
                                <label>الدولة : </label>&nbsp;<? echo $fetch['country'];?>
                            </div>
                            <div class="col-xs-6 p-5">
                                <label>المدينة : </label>&nbsp;<? echo $fetch['city'];?>
                            </div>
                            <div class="col-xs-6 p-5">
                                <label>المنطقة : </label>&nbsp;<? echo $fetch['region'];?>
                            </div>
                            <div class="col-xs-6 p-5">
                                <label>الوقت : </label>&nbsp;<? echo $fetch['city_time'];?>
                            </div>
                            <div class="col-xs-6 p-5">
                                <label>خط الطول : </label>&nbsp;<? echo $fetch['hieght'];?>
                            </div>
                            <div class="col-xs-6 p-5">
                                <label>خط العرض : </label>&nbsp;<? echo $fetch['width'];?>
                            </div>
                            <div class="col-xs-12 p-5">
                                <? $fetch['active'] == 1 ? $st = '<span class="text-success"> مفعل <i class="fa fa-check-circle text-success"></i></span>':$st = '<span class="text-danger"> معطل <i class="fa fa-close text-danger"></i></span>';?>
                                <label> حالة الجهاز : </label>&nbsp;<? echo $st;?>
                            </div>
                            <div class="col-xs-12 d-flex mb-5">
                                <a href="#edit-device<? echo $fetch['id'];?>" class="text-info" data-toggle="modal"><i class="fa fa-edit"></i> تعديل الحالة</a>
                                <a href="#delete-device<? echo $fetch['id'];?>" class="text-danger mr-5 pr-20" data-toggle="modal"><i class="fa fa-trash"></i> حذف الجهاز</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!----------------------------------------- Modal edit device --------------------------------------->
        <!-- Modal details -->
        <div class="modal text-center" id="edit-device<? echo $fetch['id'];?>" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div id="result"></div>
                <div class="modal-content text-right">
                    <div class="modal-header">
                        <button type="button" class="clos" data-dismiss="modal" aria-hidden="true"><b>x</b></button>
                        <h5> تعديل الحالة </h5>
                    </div>
                    <div class="form-group p-10">
                        <select class="form-control" id="device_status">
                            <option value="<? echo $fetch['active'];?>"><? $fetch['active'] == 1? print('مفعل'):print('معطل');?></option>
                            <option value="1">تفعيل</option>
                            <option value="0">تعطيل</option>
                        </select>
                        <div class="mt-10">
                            <button type="button" class="btn btn-info save_edit_device" data-id="<? echo $fetch['id'];?>"> حفظ </button>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal details-->

        <!----------------------------------------- Modal delete device --------------------------------------->
        <!-- Modal details -->
        <div class="modal text-center" id="delete-device<? echo $fetch['id'];?>" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div id="result"></div>
                <div class="modal-content text-right">
                    <div class="modal-header">
                        <button type="button" class="clos" data-dismiss="modal" aria-hidden="true"><b>x</b></button>
                        <h5> حذف الجهاز </h5>
                    </div>
                    <div class="form-group">
                        <h5 class="p-10"> هل أنت متاكد من حذف هذا الجهاز </h5>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger del_device" data-id="<? echo $fetch['id'];?>"> حذف </button>
                            <button type="button" class="btn btn-info" data-dismiss="modal">اغلاق</button>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal details-->
    <?
    }
    }
}
$f = validation($_POST['f']);
$manage_member = new Manage_member;

if($f == 'newedit'){
    $manage_member->editmember();
}else if($f == 'delete'){
    $manage_member->deletemember();
}
if(validation($_GET['f']) == 'edit_memberpass'){
    $manage_member->member_pass();
}
