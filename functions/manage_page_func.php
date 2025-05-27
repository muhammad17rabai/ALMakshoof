<?php
if (file_exists('config.php')) {
    require_once 'config.php';
}else{
    require_once 'connect.php';
}
require_once 'functions.php';
require_once 'notifications_func.php';
class ManagePage extends DB
{
    function addpage()
    {
        $user_id = $_SESSION['id'];
        $pagename = validation($_POST['pagename']);
        $pageurl = validation($_POST['pageurl']);
        $category = validation($_POST['category']);
        $logo = validation($_POST['typepage']);
        $descpage = validation($_POST['descpage']);
        $rating = validation($_POST['rating']);
        $date_created = time();
        $status = 0;
        $active = 1;
        $sql = $this->connect()->query("SELECT * FROM pages WHERE page_url = '$pageurl' && active = 1");
        $rows_url = $sql->num_rows;
        $fetch_id = $sql->fetch_assoc()['id'];
        $imagename = $_FILES['img_name']['name'];
        $imagesize = $_FILES['img_name']['size'];
        $val = validate_page($pagename,$pageurl,$rows_url,$category,$descpage,$logo,$rating);
        $val_img = validate_img($imagename , $imagesize , 3 , 8);
        $cc = count($imagename);
    
        if(isset($_POST['addpage'])){
            if ($rating < 3) {
                $rating = 3;
            }
            if(empty($val)){
                if(empty($val_img)){
                    $sql = "INSERT INTO pages(user_id,page_name,page_url,category,description,logo,rate,date_created,status,active) 
                    VALUES('$user_id','$pagename','$pageurl','$category','$descpage','$logo','$rating','$date_created','$status','$active')";
                    $query = $this->connect()->query($sql);
                    if($query){
                        $mid = $this->connect()->query("SELECT MAX(id) as id FROM pages WHERE user_id = $user_id");
                        $lid = $mid->fetch_assoc();
                        $page_id =  $lid['id'];
                        if($page_id > 0){
                        for ($i=0; $i <$cc ; $i++) { 
                            $imagename = $_FILES['img_name']['name'][$i];                            
                            $tmp = $_FILES['img_name']['tmp_name'][$i];
                            $newimgname = rand(1000, 10000) . $imagename;
                            $sqlimg = "INSERT INTO page_images(page_id,image)VALUES('$page_id','$newimgname')";
                            $queryimg = $this->connect()->query($sqlimg);
                            move_uploaded_file($tmp,'./images/'.$newimgname);
                        }
                        }
                        }
                         if($queryimg){
                            $m = new Notifications;
                            $m->save_notification($page_id,$user_id,'admin','تم ارسال طلب لاضافة صفحة جديدة','secondary',0);
                            header("location:index.php?page=notic_details&&page_id=$page_id&&S=page_req");
                            echo '<input type="hidden" id="notification" value="success"/>';
                         }else{
                            box_alert('danger','حطأ ! لم يتم اضافة الصفحة');
                        }
                    }else{
                        box_alert('danger',$val_img);
                    }
                }elseif($val == 'page_exit'){
                    $a = '<a href="index.php?page=Addrating&&page_id='.$fetch_id.'&&type=page">من هنا</a>';
                    $val = 'هذه الصفحة موجودة ولا يمكن اضافتها مرة أخرى !! يمكنك الذهاب الى تقييمات الصفحة واضافة تقييم '.$a;
                    box_alert('danger',$val);
                }else{
                    box_alert('danger',$val);
                }
        }
    }
    function getallpage()
    {   
        $check_sub = new info;
        $cc = $check_sub->check_subscribe();
        $role = new role;
        $category = validation($_GET['category']);
        $type = validation($_GET['type']);
        $page_active = validation($_GET['page_active']);
        switch ($type) {
            case 'facebook':
                $tp = 'icons/face.png';
                break;
            case 'instagram':
                $tp = 'icons/Instagram.jpg';
                break;
            case 'store':
                $tp = 'icons/store.jpg';
                break;
        }
        if ($role->r('role') == 1) {
            $url = '<a href="index.php?page=newsubscribe"> من هنا </a>';
            if ($cc == 'not_exit') {
                $text = "أنت غير مشترك !! لتتمكن من رؤية جميع الصفحات الرجاء الاشتراك".$url;
                $limit = 'LIMIT 3';
                echo '<div class="alert alert-danger m-5">'.$text.'</div>';
            }elseif($cc == 0){
                $text = " طلبك الاشتراك قيد المراجعة !! الرجاء الانتظار للموافقة على الطلب لتتمكن من رؤية جميع الصفحات , يمكنك متابعة الطلب ".$url;
                echo '<div class="alert alert-success m-5">'.$text.'</div>';
                $limit = 'LIMIT 3';
            }elseif($cc == 1){
                $limit = 'ORDER BY p.date_updated DESC';
            }elseif($cc == 2){
                $text = " تم تعليق طلب اشتراكك !! لتتمكن من رؤية جميع الصفحات الرجاء معالجة الطلب ".$url;
                $limit = 'LIMIT 3';
                echo '<div class="alert alert-warning m-5">'.$text.'</div>';
            }elseif($cc == 3){
                $text = " تم رفض طلب اشتراكك !! لتتمكن من رؤية جميع الصفحات الرجاء تقديم طلب اشتراك جديد".$url;
                $limit = 'LIMIT 3';
                echo '<div class="alert alert-danger m-5">'.$text.'</div>';
            }elseif($cc == 4){
                $text = " اشتراكك انتهى !! الرجاء تجديد طلب الاشتراك لتتمكن من رؤية جميع الصفحات , يمكنك تجديد الطلب ".$url;
                echo '<div class="alert alert-info m-5">'.$text.'</div>';
                $limit = 'LIMIT 3';
            }
        }else{
            $limit = 'ORDER BY p.date_updated DESC';
        }
        if ($page_active == "page_disable" && $role->r('role') == 2) {
            $page_active = 0;
        }else{
            $page_active = 1;
        }
        if ($category == null || $category == 'all') {
            if ($type == null || $type == 'all') {
               $sql = $this->connect()->query("SELECT p.* , u.username FROM pages AS p JOIN users AS u ON p.user_id = u.id WHERE p.status = 1 && p.active = '$page_active' $limit");
            }else{
                $sql = $this->connect()->query("SELECT p.* , u.username FROM pages AS p JOIN users AS u ON p.user_id = u.id WHERE p.logo = '$tp' && p.status = 1 && p.active = '$page_active' $limit");
            }
        }else{
            if ($type == null || $type == 'all') {
                $sql = $this->connect()->query("SELECT p.* , u.username FROM pages AS p JOIN users AS u ON p.user_id = u.id WHERE p.category = '$category' && p.status = 1 && p.active = '$page_active' $limit");
            }else{
                $sql = $this->connect()->query("SELECT p.* , u.username FROM pages AS p JOIN users AS u ON p.user_id = u.id WHERE p.category = '$category' && p.logo = '$tp' && p.status = 1 && p.active = '$page_active' $limit");
            }
        }
        if ($sql->num_rows == 0) {
            box_alert('secondary','لا يوجد صفحات');
        }
        echo '<input type="hidden" id="countpage" value=" ( '.$sql->num_rows.' )"/>';
        while ($fetch = $sql->fetch_assoc()){
        $sqlimg = $this->connect()->query("SELECT * FROM page_images WHERE page_id = '{$fetch['id']}'");
        $page_id = $fetch['id'];
        $user_id = $fetch['user_id'];
        $sql_rating = $this->connect()->query("SELECT * FROM rating WHERE item_id = '$page_id' && type='page'");
        $rows_rating = $sql_rating->num_rows;
        if ($rows_rating >= 5) {
            $sum = 0;
            while ($fetch_rating = $sql_rating->fetch_assoc()) {
                $sum = $sum + $fetch_rating['rate'];
            }
            $avg = $sum / $rows_rating;
            if ($avg < 3) {
                $sql_update = $this->connect()->query("UPDATE pages SET active = 0 WHERE id = '$page_id'");
                if ($sql_update) {
                    $m = new Notifications;
                    $m->save_notification($page_id,'admin',$user_id,'تم الغاء نشر الصفحة لأن معدل تقييم الصفحة أقل من 3 نجوم','secondary',0);
                    $date = time();
                    $notes = 'تم الغاء نشر الصفحة لأن معدل تقييم الصفحة أقل من 3 نجوم';
                    $sql_reject = $this->connect()->query("INSERT INTO order_notes(order_id,date_created,notes,status,type) VALUES('$page_id','$date','$notes',4,'page')");
                }
            }
        }
        switch ($fetch['rating']) {
            case 5:
                $rating = ' <i class="text-success"> ممتازة </i>';
                break;
            case 4:
                $rating =  ' <i class="text-warning"> جيدة جدا </i>';
                break;
            case 3:
                $rating =  ' <i class="text-warning"> جيدة جدا </i>';
                break;
            case 2:
                $rating =  ' <i class="text-warning"> جيدة </i>';
                break;
            case 1:
                $rating =  ' <i class="text-danger"> سيئة </i>';
                break;
            default:
                # code...
                break;
        }
        
        $sqlf = $this->connect()->query("SELECT * FROM favorate WHERE user_id ='{$_SESSION['id']}' && item_id ='{$fetch['id']}' && type ='page'");
        $rows = $sqlf->num_rows;
        if ($rows > 0) {
            $u =  1;
            $fetchf = $sqlf->fetch_assoc();
        }else{
            $u = 0;
        }

        ?>
        <main>
            <div class="container col-lg-4 col-sm-6 col-xs-12">
                <div id="result-fav"></div>
                <div class="row d-flex">
                    <div class="col-md-12 form-add card m-2">
                        <div class="p-3">
                            <div class="d-flex flex-row mb-3"><img src="<? echo $fetch['logo'];?>" class="rounded-circle" width="90"  style="height:50px;">
                                <div class="d-flex flex-column mt-3"><a href="<? echo $fetch['page_url'];?>" target="blanek"><h5><? echo $fetch['page_name'];?></h5></a><span class="text-black-50"><? get_date($fetch['date_created']);?></span>
                                    <span style="font-size: 1.7rem;"><? show_rating($fetch['rate']);?></span>
                                    <span><? echo category($fetch['category']);?></span>
                                </div>
                                <div style="display:none;"><? echo $fetch['page_url'];?></div>
                            </div>
                            <h6><?// echo substr($fetch['description'],0,150).' .......';?></h6><hr>

                            <div class="d-flex justify-content-between install mt-3">
                                <a href="index.php?page=page_details&&page_id=<? echo $fetch['id'];?>"> عرض التفاصيل &nbsp;<i class="fa fa-angle-right"></i></a>
                                <a href="index.php?page=Allrating&&page_id=<? echo $fetch['id'];?>&&type=page">عرض التقييمات &nbsp;<i class="fa fa-angle-right"></i></a>
                                <?
                                    if($role->r("role") == 2){
                                        echo '<a href="index.php?page=Edit&&page_id='.$fetch['id'].'&&S=edit_page"> &nbsp;<i class="fa fa-edit h5"></i></a>';
                                    }
                                    if($fetch['user_id'] != $_SESSION['id'] || $role->r("role") == 2){
                                        if ($u == 0) {?>
                                            <h4 class="favorate" data-u="0" data-id = "<? echo $fetchf['id'];?>" data-userid = "<? echo $_SESSION['id'];?>" data-itemid = "<? echo $fetch['id'];?>" data-type="page"><i class="fa fa-heart text-gray"></i></h4>
                                        <? }elseif($u == 1){?>
                                            <h4 class="favorate" data-u="1" data-id = "<? echo $fetchf['id'];?>" data-userid = "<? echo $_SESSION['id'];?>" data-itemid = "<? echo $fetch['id'];?>" data-type="page"><i class="fa fa-heart text-danger"></i></h4>          
                                    <? }
                                    }
                                ?>          
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    <?    
        }
    }
    function page_details()
    {
        $user_id = validation($_SESSION['id']);
        $role = new role;
        $modals = new Modalss;
        $page_id = validation($_GET['page_id']);
        $sql = $this->connect()->query("SELECT p.* , u.username FROM pages AS p JOIN users AS u ON p.user_id = u.id WHERE p.id = '$page_id'");
        $sqlimg = $this->connect()->query("SELECT * FROM page_images WHERE page_id = '$page_id'");
        $rows = $sqlimg->num_rows;
        while ($fetch = $sql->fetch_assoc()) {
        ?>
        <div class="container-fluied">
            <div class="form-add p-10">
                <div class="container-fliud">
                    <div class="wrapper row">
                        <div class="preview col-md-12">
                            <div class="mb-3 text-center">
                                <img src="<? echo $fetch['logo'];?>" class="rounded-circle mb-5" width="90"  style="height:50px;">
                            </div>
                        </div>
                        <div class="details col-md-12">
                            <h5 class="page-title">اسم الصفحة : <? echo $fetch['page_name'];?></h5>
                            <a href="<? echo $fetch['page_url'];?>" class="mt-5" target="blanek"><h6 class="page-url">الذهاب الى الصفحة >></h6></a><hr>
                            <div class="rating">
                                <h6>التقييم : <? show_rating($fetch['rate']);?></h6>
                            </div><hr>
                            <div class="category">
                                <span>الفئة : <? echo category($fetch['category']);?></span>
                            </div><hr>
                            <span><i class="fa fa-calendar"></i> تمت الاضافة منذ : <i>&nbsp;<? get_date( $fetch['date_created']);?></i><hr>
                            <h6 class="descreption"> الوصف والملاحظات :  </h6>
                            <p class="page-description mt-1"><i> <? echo $fetch['description'];?> </i></p><hr>
                            <?
                                if($role->r('role') == 2){
                                    echo "<p class='date'>تمت الاضافة بواسطة : ";
                                    echo '<a href="index.php?page=Member_details&&user_id='.$fetch['user_id'].'&&p=member_info">'.$fetch['username'].'</a></p><hr>';
                                }
                            ?>                         
                            <h6><b> الصور والدلائل : </b></h6>
                            <div class="row mt-10 img-order-screenshot">
                                <?
                                    while ($fetchimg = $sqlimg->fetch_assoc()) {
                                    echo '
                                    <div class="col-xs-3">
                                        <a href="#zoom-img" data-toggle="modal"><img src="./images/'.$fetchimg['image'].'" class="myImg" data-img="#myModal-img'.$fetch['id'].'" style="width: 80px; height: auto;"></a>
                                    </div>
                                    ';   
                                }?>
                            </div><hr>
                            <?
                                if (validation($_GET['S']) == 'page_req') {
                                    if($fetch['status'] == 0 || $fetch['status'] == 2){
                                        if($role->r("role") == 1 && $fetch['user_id'] == $user_id){
                                            echo '  
                                                <div class="mt-15 order-btn">
                                                    <a href="index.php?page=Edit&&page_id='. $fetch['id'].'&&S=edit_page" class="btn-success btn-info"> تعديل </a>
                                                    <a href="#delete-order'.$fetch['id'].'" class="btn-danger" id="del_page" data-toggle="modal"> حذف </a>
                                                </div>';
                                        }
                                    }
                                }
                                if($role->r("role") == 2){?>
                                    <div class="mt-15 order-btn text-center">
                                        <a href="index.php?page=Edit&&page_id=<? echo $fetch['id'];?>&&S=edit_page" class="btn-info"><i class="fa fa-edit"></i> تعديل  </a>
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
                                    </div>
                                <?  
                                }elseif($fetch['user_id'] == $_SESSION['id']){?>
                                    <div class="mt-15 order-btn">
                                        <a href="index.php?page=Edit&&page_id=<? echo $fetch['id'];?>&&S=edit_page" class="btn-info"><i class="fa fa-edit"></i> تعديل المنشور </a>
                                    </div>
                                <?}
                                    $id = $fetch['id'];
                                    $user_id = $fetch['user_id'];
                                    $data_t = 'pages';
                                    $data_img = 'page_images';
                                    $data_routs = "page_details&&page_id=$id&&type=$data_t&&st={$_GET['st']}";
                                    $type = 'page';
                                    $modals->modals($id,$user_id,$data_t,$data_img,$data_routs,$type);
                                ?>
                                <!--============================== modal zoom img ===========================-->
                                <div id="myModal-img<? echo $fetch['id'];?>" class="modal-img m-0">
                                    <a class="close close-img" data-dissmision="modal" aria-hidden="true"><b>x</b></a>
                                    <img class="modal-content-img img01">
                                </div>
                                <!--============================== End modal zoom img ===========================-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?
        }
    }
}
?>
