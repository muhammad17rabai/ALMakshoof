<?php
    date_default_timezone_set("Asia/Jerusalem");
    require_once 'connect.php';

    function validation($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    function box_alert($color , $text)
    {
        echo '<div class="col-xs-12 btn-'.$color.' mt-10 mb-15 p-5 text-center" style="border-radius:7px; display:block;">
                <span>'.$text.'</span>
            </div>';
    }
    function get_date($data)
    {
        $timestamp      = (int)$data;
        $current_time   = time();
        $diff           = $current_time - $timestamp;
        //intervals in seconds
        $intervals      = array (
            'year' => 31556926, 'month' => 2629744, 'week' => 604800, 'day' => 86400, 'hour' => 3600, 'minute'=> 60
        );
        //now we just find the difference
        if ($diff < 60)
        {
            echo 'الأن';
        }
        if ($diff >= 60 && $diff < $intervals['hour'])
        {
            $diff = floor($diff/$intervals['minute']);
            echo ' منذ '. $diff .' '.' دقيقة ';
        }
        if ($diff >= $intervals['hour'] && $diff < $intervals['day'])
        {
            $diff = floor($diff/$intervals['hour']);
            echo ' منذ '.$diff .' '.'ساعة';
        }
        if ($diff >= $intervals['day'] && $diff < $intervals['week'])
        {
            $diff = floor($diff/$intervals['day']);
            echo ' منذ '.$diff .' '.'يوم';
        }
        if ($diff >= $intervals['week'] && $diff < $intervals['month'])
        {
            $diff = floor($diff/$intervals['week']);
            echo  ' منذ '.$diff .' '.'اسبوع';
        }
        if ($diff >= $intervals['month'] && $diff < $intervals['year'])
        {
            $diff = floor($diff/$intervals['month']);
            echo ' منذ '.$diff .' '.'شهر';
        }
        if ($diff >= $intervals['year'])
        {
            $diff = floor($diff/$intervals['year']);
            echo ' منذ '.$diff .' '.'سنة';
        }
    }
    function show_rating($n){
        for ($i=0; $i < $n; $i++) { 
            echo '<i class="bx bxs-star star active-star" data-c="'.(1+$i).'"></i>';
        }
        for ($j=0; $j < 5-$n ; $j++) { 
            echo '<i class="bx bx-star star" data-c="'.(1+$j+$i).'"></i>';
        }
    }
      function validate_user($name,$email,$rows_email,$phone,$rows_phone,$password,$city,$address,$gender,$agree,$role)
    {
        if($name == null || $email == null || $phone == null || $password == null || $city == null || $address == null || $gender == null || $agree == null || $role == null){
            $error = "جميع الحقول مطلوبة !! يرجى ملئ جميع الحقول";
        }else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $error = "الايميل خاطئ !! يرجى ادخال ايميل صحيح";
        }elseif($rows_email > 0){
            $error = " هذا الايميل مستخدم !! يرجى ادخال ايميل اخر  ";
        }elseif(!is_numeric($phone)){
            $error = "رقم الهاتف خاطئ !! يرجى ادخال رقم هاتف صحيح";
        }elseif($rows_phone > 0){
            $error = "  رقم الهاتف مستخدم !! يرجى ادخال رقم اخر  ";
        }elseif(strlen($phone) != 10){
            $error = "رقم الهاتف خاطئ !! برجى ادخال رقم هاتف من 10 أرقام";
        }elseif(strlen($password) < 6){
            $error = " كلمة المرور قصيرة !! يجب أن تكون كلمة المرور أكبر من 6 ";
        }else {
            $error = "";
        }
        return $error;
    }
    function validate_login($username,$password,$rows_user){
        if($username == null || $password == null){
            $error = "جميع الحقول مطلوبة !! يرجى ملئ جميع الحقول";
        }elseif($rows_user == 0){
            $error = "معلومات تسجيل الدخول خاطئة";
        }else {
            $error = "";
        }
        return $error;
    }

    function validate_page($pagename,$pageurl,$rows_url,$category,$description,$logo,$rating)
    {
        if($pagename == null || $pageurl == null || $category == null || $description == null || $logo == null || $rating == null){
            $error = "جميع الحقول مطلوبة !! يرجى ملئ جميع الحقول";
        }elseif(!filter_var($pageurl, FILTER_VALIDATE_URL)){
            $error = "  رابط الصفحة خاطئ !! يرجى ادخال رابط صحيح";
        }elseif($rows_url > 0){
            $error = "page_exit";
        }else {
            $error = "";
        }
        return $error;
    }
    function category($data)
    {
        switch ($data) {
            case 'all':
                $category = 'الكل';
                break;
            case 'home_tools':
                $category = 'أدوات منزلية';
                break;
            case 'clothes_shoes':
                $category = 'ملابس وأحذية';
                break;
            case 'devices_electric':
                $category = 'أجهزة كهربائية';
                break;
            case 'computer_mobile':
                $category = 'حواسيب وهواتف';
                break;
            case 'elecetronics':
                $category = 'الكترونيات عامة';
                break;
            case 'prefumes_accessories':
                $category = 'عطور واكسسوارات';
                break;
            case 'health_beauty':
                $category = 'صحة وجمال';
                break;
            case 'bags_prose':
                $category = 'شنط ونثريات';
                break;
            case 'books':
                $category = 'كتب ومطبوعات';
                break;
            case 'cleane':
                $category = 'مواد تنظيف';
                break;
            case 'car_accessories':
                $category = 'كماليات سيارات';
                break;
            case 'medicals':
                $category = 'أدوات ومشدات طبية';
                break;
            case 'others':
                $category = 'غير ذلك';
                break;
        }
        return $category;
    }
    function validate_products($name,$url,$price,$descreption,$rating,$uses)
    {
        if($name == null || $url == null || $price == null || $descreption == null || $rating == null || $uses == null){
            $error = "جميع الحقول مطلوبة !! يرجى ملئ جميع الحقول";
        }elseif(!filter_var($url, FILTER_VALIDATE_URL)){
            $error = " الرابط خاطئ !! يرجى ادخال رابط صحيح ";
        }else{
            $error = "";
        }
        return $error;
    }
    function validate_subscribe($name , $period , $payment_method)
    {
        if($name == null || $period == null || $payment_method == null){
            $error = "جميع الحقول مطلوبة !! يرجى ملئ جميع الحقول";
        }else{
            $error = "";
        }
        return $error;
    }
    function validate_sites($name_ar,$name_en,$url,$descreption,$status,$rows_url){
        if($name_ar == null || $name_en == null || $url == null || $descreption == null || $status == null){
            $error = "جميع الحقول مطلوبة !! يرجى ملئ جميع الحقول";
        }elseif(!filter_var($url, FILTER_VALIDATE_URL)){
            $error = "  رابط المتجر خاطئ !! يرجى ادخال رابط صحيح";
        }elseif($rows_url > 0){
            $error = " هذا المتجر موجود ولا يمكن اضافته مرة أخرى ";
        }else {
            $error = "";
        }
        return $error;
    }
    function validate_broker($code,$phone,$count,$period,$commission,$rows_phone)
    {
        if($code == null || $phone == null || $count == 0 || $period == null || $commission == null){
            $error = "جميع الحقول مطلوبة !! يرجى ملئ جميع الحقول";
        }elseif($rows_phone > 0){
            $error = "  رقم الهاتف مستخدم !! يرجى ادخال رقم اخر ";
        }elseif(!is_numeric($phone)){
            $error = "رقم الهاتف خاطئ !! يرجى ادخال رقم هاتف صحيح";
        }elseif(strlen($phone) != 10){
            $error = "رقم الهاتف خاطئ !! برجى ادخال رقم هاتف من 10 أرقام";
        }else {
            $error = "";
        }
        return $error;
    }
    function validate_img($imagename , $imagesize , $min , $max)
    {   
        $cc = count($imagename);
        if($max == 1){
            $allowExt   = array("jpg", "png", "jpeg");
            $strToArray = explode(".", $imagename);
            $ext        = end($strToArray);
            $ext        = strtolower($ext);
            $size = $imagesize/1024;
            if(!in_array($ext, $allowExt)){
                if($min == 0){
                    $err_ex = 0;
                }else {
                    $err_ex = 1;
                }
            }elseif($size > (8*1024)){
                $err_size = 1;
            }
        }else {
            for ($i=0; $i < $cc; $i++) { 

                $allowExt   = array("jpg", "png", "jpeg");
                $strToArray = explode(".", $imagename[$i]);
                $ext        = end($strToArray);
                $ext        = strtolower($ext);
                $size = $imagesize[$i]/1024;
                if(!in_array($ext, $allowExt)){
                    $err_ex = 1;
                }elseif($size > (8*1024)){
                    $err_size = 1;
                }
            }
        }
        if($cc < $min){
            $error = "الرجاء تحميل $min صور على الأقل";
        }elseif($cc > $max){
            $error = "لا يمكن تحميل أكثر من $max صور";
        }elseif ($err_ex == 1) {
            $error = "خطأ ! الرجاء تحميل صورة صحيحة";
        }elseif ($err_size == 1) {
            $error = "حجم الصورة كبير للغاية ";
        }else {
            $error = "";
        }
        return $error;     
    }
    function validate_avatar($new_nameavatar , $new_sizeavatar)
    {   
        $allowExt   = array("jpg", "png", "jpeg");
        $strToArray = explode(".", $new_nameavatar);
        $ext        = end($strToArray);
        $ext        = strtolower($ext);
        $size = $new_sizeavatar/1024;
        if(!in_array($ext, $allowExt)){
            $err_ex = 1;
        }elseif($size > (8*1024)){
            $err_size = 1;
        }

        if ($err_ex == 1) {
            $error = "خطأ ! الرجاء تحميل صورة صحيحة";
        }elseif ($err_size == 1) {
            $error = "حجم الصورة كبير للغاية ";
        }else {
            $error = "";
        }
        return $error;
    }
    class Modalss extends DB
    {
        function modals($page_id,$user_id,$data_t,$data_img,$data_routs,$type)
        { 
            $role = new role;
            switch (validation($_GET['page'])) {
                case 'page_details':
                    $data_routs = 'Allpage';
                    break;
                case 'Products_details':
                    $data_routs = 'Allproducts';
                    break;
            }
            ?>
            <!----------------------------------------- Modal delete order --------------------------------------->
            <!-- Modal details -->
            <div class="modal text-center" id="delete-order<? echo $page_id;?>" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div id="result"></div>
                    <div class="modal-content text-right">
                        <div class="modal-header">
                            <button type="button" class="clos" data-dismiss="modal" aria-hidden="true"><b>x</b></button>
                            <h5> حذف الطلب </h5>
                        </div>
                        <div class="form-group">
                            <h5 class="p-10"> هل أنت متاكد من حذف هذا الطلب </h5>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger del_order" data-id="<? echo $page_id;?>" data-t="<? echo $data_t;?>" data-img="<? echo $data_img;?>" data-userid="<? echo $user_id ?>" data-routs="<? echo $data_routs;?>"> حذف </button>
                                <button type="button" class="btn btn-info" data-dismiss="modal">اغلاق</button>
                            </div>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal details-->
            <!-------------------------------------- End reject order modal ---------------------------------------------->
            <!----------------------------------------- Modal show result pending --------------------------------------->
            <!-- Modal details -->
            <div class="modal text-center" id="show-result-pending<? echo $page_id;?>" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content text-right">
                        <div class="modal-header">
                            <button type="button" class="clos" data-dismiss="modal" aria-hidden="true"><b>x</b></button>
                            <h5> أسباب تعليق الطلب </h5>
                        </div>
                        <div class="container mt-5 mb-5">
                            <div class="row">
                                <div class="col-md-6 offset-md-3">
                                    <ul class="timeline">
                                        <?
                                            $sql_notes = $this->connect()->query("SELECT * FROM order_notes WHERE order_id = $page_id && status = 0 && type = '$type' ORDER BY id DESC");
                                            while ($fetch_notes = $sql_notes->fetch_assoc()) {?>
                                            <li>
                                                <div class="show-pendding-result<? echo $fetch_notes['id'];?>">
                                                    <h6><a href="#" class="float-right"><? get_date($fetch_notes['date_created']);?></a></h6><br>
                                                    <p><? echo $fetch_notes['notes'];?></p>
                                                    <?
                                                        if($role->r("role") == 2){?>
                                                            <i class="fa fa-edit"></i>&nbsp;<a class="text-info edit-pendding" data-hide="show-pendding-result<? echo $fetch_notes['id'];?>" data-show="form_edit_pendding<? echo $fetch_notes['id'];?>">تعديل</a>
                                                            <a class="text-info delete-pendding" data-show="form_delete_pendding<? echo $fetch_notes['id'];?>"> <i class="fa fa-trash text-danger mr-10"></i>&nbsp; حذف</a>
                                                <? }?>
                                                <div class="form-group form-delete-pendding" id="form_delete_pendding<? echo $fetch_notes['id'];?>"><hr>
                                                    <h6 class="p-10"> هل أنت متاكد من حذف هذا التعليق </h6>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger del_pendding" data-id="<? echo $fetch_notes['id'];?>" data-routs="<? echo $data_routs;?>"> حذف </button>
                                                        <button type="button" class="btn btn-info" data-dismiss="modal">اغلاق</button>
                                                    </div>
                                                </div>
                                                </div>
                                                <div class="form-edit-pendding" id="form_edit_pendding<? echo $fetch_notes['id'];?>">
                                                    <div class="p-5" id="result-pendding<? echo $fetch_notes['id'];?>"></div>
                                                    <textarea id="pendding_edit<? echo $fetch_notes['id'];?>" class="form-control" rows="6"><? echo $fetch_notes['notes'];?></textarea>
                                                    <button type="button" class="btn-sm btn-info save_edit_pendding m-5" data-note="pendding_edit<? echo $fetch_notes['id'];?>" data-id="<? echo $fetch_notes['id'];?>" data-routs="<? echo $data_routs;?>" data-userid="<? echo $user_id;?>" data-t="<? echo $data_t;?>">حفظ</button>
                                                </div>
                                            </li><hr>
                                        <? } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal details-->
            <!-------------------------------------- End show result modal ---------------------------------------------->
            <!----------------------------------------- Modal show result reject --------------------------------------->
            <!-- Modal details -->
            <div class="modal text-center" id="show-result-reject<? echo $page_id;?>" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content text-right">
                        <div class="modal-header">
                            <button type="button" class="clos" data-dismiss="modal" aria-hidden="true"><b>x</b></button>
                            <h5>  سبب رفض الطلب </h5>
                        </div>
                        <div class="container mt-5 mb-5">
                            <div class="row">
                                <div class="col-md-6 offset-md-3">
                                    <ul class="timeline timeline-reject">
                                        <?
                                            $sql = $this->connect()->query("SELECT * FROM order_notes WHERE order_id = $page_id && status = 1 && type = '$type' ORDER BY id DESC");
                                            while ($fetch_notes = $sql->fetch_assoc()) {?>
                                            <li>
                                                <div class="show-reject-result<? echo $fetch_notes['id'];?>">
                                                    <h6><a href="#" class="float-right"><? get_date($fetch_notes['date_created']);?></a></h6><br>
                                                    <p><? echo $fetch_notes['notes'];?></p>
                                                    <?
                                                        if($role->r("role") == 2){?>
                                                            <i class="fa fa-edit"></i>&nbsp;&nbsp;<a class="text-info edit-reject" data-hide="show-reject-result<? echo $fetch_notes['id'];?>" data-show="form_edit_reject<? echo $fetch_notes['id'];?>">تعديل</a>
                                                            <a class="text-info delete-reject" data-show="form_delete_reject<? echo $fetch_notes['id'];?>"> <i class="fa fa-trash text-danger mr-10"></i>&nbsp; حذف</a>
                                                <? }?>
                                                <div class="form-group form-delete-reject" id="form_delete_reject<? echo $fetch_notes['id'];?>"><hr>
                                                    <h6 class="p-10"> هل أنت متاكد من حذف سبب الرفض </h6>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger del_reject" data-id="<? echo $fetch_notes['id'];?>" data-routs="<? echo $data_routs;?>"> حذف </button>
                                                        <button type="button" class="btn btn-info" data-dismiss="modal">اغلاق</button>
                                                    </div>
                                                </div>
                                                </div>
                                                <div class="form-edit-reject" id="form_edit_reject<? echo $fetch_notes['id'];?>">
                                                    <div class="p-5" id="result-reject<? echo $fetch_notes['id'];?>"></div>
                                                    <textarea id="reject_edit<? echo $fetch_notes['id'];?>" class="form-control" rows="9"><? echo $fetch_notes['notes'];?></textarea>
                                                    <button type="button" class="btn-sm btn-info save_edit_reject m-5" data-id="<? echo $fetch_notes['id'];?>" data-routs="<? echo $data_routs;?>" data-userid="<? echo $user_id; ?>" data-t="<? echo $data_t;?>">حفظ</button>
                                                </div>
                                            </li><hr>
                                        <?}?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal details-->
            <!-------------------------------------- End show result reject modal ---------------------------------------------->
            <!----------------------------------------- Modal acceppt order --------------------------------------->
            <!-- Modal details -->
            <div class="modal" id="acceppt-order<? echo $page_id;?>" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content text-right">
                        <div class="modal-header">
                            <button type="button" class="clos" data-dismiss="modal" aria-hidden="true"><b>x</b></button>
                            <h5> قبول الطلب </h5>
                        </div>
                        <div class="p-10">
                            <h6> هل أنت متأكد من قبول الطلب</h6>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success accept_order" data-id="<? echo $page_id;?>" data-t="<? echo $data_t;?>" data-routs="<? echo $data_routs;?>" data-userid="<? echo $user_id; ?>"> قبول </button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">اغلاق</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal details-->

            <!----------------------------------------- Modal pendding order --------------------------------------->
            <!-- Modal details -->
            <div class="modal" id="pendding-order<? echo $page_id;?>" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content text-right">
                        <div class="modal-header">
                            <button type="button" class="clos" data-dismiss="modal" aria-hidden="true"><b>x</b></button>
                            <h5> تعليق الطلب </h5>
                        </div>
                        <div class="p-5" id="result-pendding"></div>
                        <div class="modal-body">    
                            <textarea id="pendding_result<? echo $page_id;?>" class="form-control" placeholder=" سبب التعليق ..." rows="6"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info pendding_order" data-id="<? echo $page_id;?>" data-t="<? echo $data_t;?>" data-routs="<? echo $data_routs;?>" data-userid="<? echo $user_id; ?>"> حغظ </button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">اغلاق</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal details-->

            <!----------------------------------------- Modal reject order --------------------------------------->
            <!-- Modal details -->
            <div class="modal text-center" id="reject-order<? echo $page_id;?>" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content text-right">
                        <div class="modal-header">
                            <button type="button" class="clos" data-dismiss="modal" aria-hidden="true"><b>x</b></button>
                            <h5> رفض الطلب </h5>
                        </div>
                        <div class="p-5" id="result-reject"></div>
                        <div class="modal-body">    
                            <textarea id="reject_result<? echo $page_id;?>" class="form-control" placeholder=" سبب الرفض ..." rows="6"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger reject_order" data-id="<? echo $page_id;?>" data-t="<? echo $data_t;?>" data-routs="<? echo $data_routs;?>" data-userid="<? echo $user_id; ?>"> رفض </button>
                            <button type="button" class="btn btn-info" data-dismiss="modal">اغلاق</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal details-->
            <!-------------------------------------- End reject order modal ---------------------------------------------->
        <!------------------------------------- End Models -------------------------------------------------->
    <? 
    }   
}
    