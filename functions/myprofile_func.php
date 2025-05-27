<?
if (file_exists('config.php')) {
    require_once 'config.php';
}else{
    require_once 'connect.php';
}
require_once 'functions.php';
class MyProfile extends DB
{
    function getmyinfo()
    {
        $role = new role;
        if (validation($_GET['user_id']) != null) {
            $user_id = validation($_GET['user_id']);
        }else{
            $user_id = validation($_SESSION['id']);
        }
        $sql = $this->connect()->query("SELECT * FROM users WHERE id = $user_id");
        $rows_d = $this->connect()->query("SELECT * FROM devices WHERE user_id = $user_id && active = 1")->num_rows;
        $fetch = $sql->fetch_assoc();?>
        <div class="col-xs-12 form-info">
            <div class="side-right text-center">
                <div class="avatars pt-20">
                    <img src="<? echo $fetch['avatar'];?>" class="img-circle avatar">
                </div>
                <div class="titLE-edit-avatar mt-10">
                    <a href="" class="btn-choice-avatar"><h6> تغيير الصورة الشخصية <i class="fa fa-upload"></i></h6></a>
                    <input type="file" name="new_avatar" class="form-control box_add_avatar">
                    <button type="submit" name="btn_save_newavatar" class="btn btn-info btn-save-newavatar"> حفظ الصورة </button>
                </div>
            </div><hr>
            <div class="col-md-8 col-xs-12 p-20">
                <div class="form-group main-name">
                    <h5>الاسم الكامل : <span><? echo $fetch['username'];?></span></h5>
                </div><hr>
                <div class="form-group main-email">
                    <h5> الايميل : <span> <? echo $fetch['email'];?> </span></h5>
                </div><hr>
                <div class="form-group main-phone">
                    <h5> رقم الهاتف : <span> <? echo $fetch['phone'];?> </span></h5>
                </div><hr>
                <div class="form-group main-address">
                    <h5> المحافظة : <span>  <? echo $fetch['city'];?> </span></h5>
                </div><hr>
                <div class="form-group main-address">
                    <h5> المنطقة : <span>  <? echo $fetch['address'];?> </span></h5>
                </div><hr>
                <div class="form-group main-gender">
                    <h5> الجنس : 
                        <span>  <?
                            switch ($fetch['gender']) {
                                case 'male':
                                    echo 'ذكر';
                                    break;
                                case 'female':
                                    echo 'أنثى';
                                    break;
                                }
                        ?> </span>
                    </h5>
                </div><hr>
                <div class="form-group main-gender">
                    <h6> تاريخ التسجيل : <i class="text-info">  <? get_date($fetch['created_at']);?> </i></h6>
                </div><hr>
                <?
            if ($role->r('role') == 2) {?>
                <div class="form-group main-role">
                    <h6> الصلاحية : 
                        <span>  <?
                            switch ($fetch['role']) {
                                case 'member':
                                    echo 'مستخدم عادي';
                                    break;
                                case 'admin':
                                    echo 'مسؤول نظام';
                                    break;
                                }
                        ?> </span>
                    </h6>
                </div><hr>
                <?}?>
                <div class="form-group count-device">
                    <h5> عدد الأجهزة المتصلة : <? echo $rows_d;?></h5>
                </div><hr>
                <div class="form-group count-status">
                <?
                    switch ($fetch['active']) {
                        case '1':
                            $status =  'نشط <i class="fa fa-check text-success"></i>';
                            break;
                        case '0':
                            $status =  'معطل <i class="fa fa-close text-danger"></i>';
                            break;
                        }
                ?>
                <h6> حالة الحساب : <? echo $status;?></h6>
                </div><hr>
                <div class="form-group">
                    <button class="btn btn-info btn-edit-maininfo" type="button"> تعديل المعلومات </button>
                </div>
            </div>
        </div>
        <?   
    }
    function edit_myinfo()
    {
        $role = new role;
        if (validation($_GET['user_id']) != null) {
            $user_id = validation($_GET['user_id']);
        }else{
            $user_id = validation($_SESSION['id']);
        }
        $sql = $this->connect()->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();?>
        <div class="col-xs-12 p-20 edit-maininfo form-add">
            <div class="form-group edit-name">
                <h5>الاسم الكامل : <input type="text" name="newname" class="form-control" placeholder="الاسم الكامل" value=" <? echo $sql['username'];?> "/></h5>
            </div>
            <div class="form-group main-email">
                <h5> الايميل : <input type="email" name="newemail" class="form-control" placeholder="الايميل" value="<? echo $sql['email'];?>"/></h5>
            </div>
            <div class="form-group main-phone">
                <h5> رقم الهاتف : <input type="text" name="newphone" class="form-control" placeholder="رقم الهاتف" value="<? echo $sql['phone'];?>"/></h5>
            </div>
            <div class="form-group city-info">
                <label for="city">  المحافظة : </label>
                <select name="newcity" class="form-control" required>
                    <option>  <? echo $sql['city'];?> </option>
                    <option value="مناطق 48">مناطق 48</option>
                    <option value="القدس">القدس</option>
                    <option value="رام الله">رام الله</option>
                    <option value="نابلس">نابلس</option>
                    <option value="جنين">جنين</option>
                    <option value="طولكرم">طولكرم</option>
                    <option value="سلفيت">سلفيت</option>
                    <option value="قلقيلية">قلقيلية</option>
                    <option value="طوباس">طوباس</option>
                    <option value="أريحا">أريحا</option>
                    <option value="بيت لحم">بيت لحم</option>
                    <option value="الخليل">الخليل</option>
                </select>
            </div>
            <div class="form-group city-info">
                <label for="address">  المنطقة : </label>
                <input type="text" class="form-control" name="newaddress" placeholder="المنطقة" value="<? echo $sql['address'];?>" required>
            </div>
            <div class="form-group main-gender">
                <h5> الجنس : 
                    <select name="newgender" class="form-control">
                    <option value="<? echo $sql['gender'];?>"><?
                        switch ($sql['gender']) {
                            case 'male':
                                echo 'ذكر';
                                break;
                            case 'female':
                                echo 'أنثى';
                                break;
                            }
                    ?></option>
                    <option value="male">ذكر</option>
                    <option value="female">أنثى</option>
                </select></h5>
            </div>
            <?
            if ($role->r('role') == 2) {?>
                <div class="form-group gender-info">
                    <label for="gender">  الصلاحية : </label>
                    <select name="role" class="form-control" required>
                        <option value="<? echo $sql['role'];?>"><?
                            switch ($sql['role']) {
                                case 'member':
                                    echo 'مستخدم عادي';
                                    break;
                                case 'admin':
                                    echo 'مسؤول نظام';
                                    break;
                                }
                        ?></option>
                        <option value="member"> مستخدم عادي </option>
                        <option value="admin"> مسؤول نظام </option>
                    </select>
                </div>
            <?}?>
            <div class="form-group main-status">
                <?
                    switch ($sql['active']) {
                        case '1':
                            $status = 'نشط';
                            break;
                        case '0':
                            $status = 'معطل';
                            break;
                        }
                ?>
                <h5> حالة الحساب : 
                    <select name="active" class="form-control">
                        <option value="<? echo $sql['active'];?>"><? echo $status;?></option>
                        <option value="1">تنشيط</option>
                        <option value="0">تعطيل</option>
                    </select>
                </h5>
            </div>
            <div class="form-group btn-save-newinfo">
                <button type="submit" class="btn btn-info btn-edit-newpass" name="btn_savenewinfo">حفظ</button>
            </div>
        </div>
    <?
        if (validation($_GET['user_id']) != null) {
            $user_id = validation($_GET['user_id']);
        }else{
            $user_id = validation($_SESSION['id']);
        }
        $name = validation($_POST['newname']);
        $email = validation($_POST['newemail']);
        $phone = validation($_POST['newphone']);
        $city = validation($_POST['newcity']);
        $address= validation($_POST['newaddress']);
        $gender = validation($_POST['newgender']);
        $active = validation($_POST['active']);
        if ($role->r('role') == 2) {
            $role = validation($_POST['role']);
        }else{
            $role = 'member';
        }
        $new_nameavatar = $_FILES['new_avatar']['name'];
        $new_tmpavatar = $_FILES['new_avatar']['tmp_name'];
        $new_sizeavatar = $_FILES['new_avatar']['size'];
        
        $sql_email = "SELECT email FROM users WHERE email = '$email' && id != '$user_id'";
        $query_email = $this->connect()->query($sql_email);
        $rows_email = $query_email->num_rows;
        
        $sql_phone = "SELECT phone FROM users WHERE phone = '$phone' && id != '$user_id'";
        $query_phone = $this->connect()->query($sql_phone);
        $rows_phone = $query_phone->num_rows;
        
        $val = validate_user($name,$email,$rows_email,$phone,$rows_phone,'.......',$city,$address,$gender,'..',$role);
        $val_avtar = validate_avatar($new_nameavatar, $new_sizeavatar);
        if (isset($_POST['btn_savenewinfo'])) {
            if (empty($val)) {
                $query = $this->connect()->query("UPDATE users SET username = '$name', email ='$email', phone='$phone', city='$city', address='$address', gender='$gender',active='$active' WHERE id = '$user_id'");
            if($query){
                $color = "success";
                $text = "تم تعديل البيانات بنجاح";
                box_alert($color , $text);
                if (validation($_GET['p']) == 'member_info') {
                    header("location:index.php?page=Member_details&&user_id=".$user_id."&&p=".validation($_GET['p']));
                }else{
                    header("location:index.php?page=Myprofile");
                }
            }
        }else{
            $color = "danger";
            box_alert($color , $val);
        }
        }
        if (isset($_POST['btn_save_newavatar'])) {
            if($new_nameavatar != null){
                if (empty($val_avtar)) {
                    $new_avatarname = './images/avatar/'.rand(1000, 10000) . $new_nameavatar;
                    $queryimg = $this->connect()->query("UPDATE users SET avatar = '$new_avatarname' WHERE id = '$user_id'");
                    if ($queryimg) {
                        move_uploaded_file($new_tmpavatar,$new_avatarname);
                        $color = "success";
                        $text = "تم تعديل البيانات بنجاح";
                        box_alert($color , $text);
                        if (validation($_GET['p']) == 'member_info') {
                            header("location:index.php?page=Member_details&&user_id=".$user_id."&&p=".validation($_GET['p']));
                        }else{
                            header("location:index.php?page=Myprofile");
                        }
                    }else{
                        $color = "danger";
                        box_alert($color , $val);
                    }
                }else{
                    box_alert('danger' , $val_avtar);
                }
            }
        }  
    }
    function edit_pass()
    {
        $user_id = validation($_POST['user_id']);
        $sql = $this->connect()->query("SELECT password FROM users WHERE id = '$user_id'")->fetch_assoc();
        $oldpass = validation($_POST['oldpass']);
        $newpass = validation($_POST['newpass']);
        $re_newpass = validation($_POST['re_newpass']);
        if ($oldpass == null || $newpass == null || $re_newpass == null) {
            box_alert('danger','جميع الحقول مطلوبة !! يرجى ملئ جميع الحقول');
        }elseif(md5($oldpass) != $sql['password']){
            box_alert('danger','كلمة السر القديمة خاطئة');
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
}
$editpass = new MyProfile;
switch ($_GET['f']) {
    case 'editpass':
        $editpass->edit_pass();
        break;
}   
?>