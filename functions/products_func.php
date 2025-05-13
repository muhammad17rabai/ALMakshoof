<?
require_once 'functions.php';
class ManageProducts extends DB
{
    function getallproducts()
    {
        $role = new role;
        $check_sub = new info;
        $cc = $check_sub->check_subscribe();

        if ($role->r('role') == 1) {
            $url = '<a href="index.php?page=subscribe"> من هنا </a>';
            if ($cc == 'not_exit') {
                $text = "أنت غير مشترك !! لتتمكن من رؤية جميع المنتجات الرجاء الاشتراك".$url;
                $limit = 'LIMIT 3';
                echo '<div class="alert alert-danger m-5">'.$text.'</div>';
            }elseif($cc == 0){
                $text = " طلبك الاشتراك قيد المراجعة !! الرجاء الانتظار للموافقة على الطلب لتتمكن من رؤية جميع المنتجات , يمكنك متابعة الطلب ".$url;
                echo '<div class="alert alert-success m-5">'.$text.'</div>';
                $limit = 'LIMIT 3';
            }elseif($cc == 1){
                $limit = 'ORDER BY id DESC';
            }elseif($cc == 2){
                $text = " تم تعليق طلب اشتراكك !! لتتمكن من رؤية جميع المنتجات الرجاء معالجة الطلب ".$url;
                $limit = 'LIMIT 3';
                echo '<div class="alert alert-warning m-5">'.$text.'</div>';
            }elseif($cc == 3){
                $text = " تم رفض طلب اشتراكك !! لتتمكن من رؤية جميع المنتجات الرجاء تقديم طلب اشتراك جديد".$url;
                $limit = 'LIMIT 3';
                echo '<div class="alert alert-danger m-5">'.$text.'</div>';
            }elseif($cc == 4){
                $text = " اشتراكك انتهى !! الرجاء تجديد طلب الاشتراك لتتمكن من رؤية جميع المنتجات , يمكنك تجديد الطلب ".$url;
                echo '<div class="alert alert-info m-5">'.$text.'</div>';
                $limit = 'LIMIT 3';
            }
        }else{
            $limit = 'ORDER BY id DESC';
        }
        $sql = $this->connect()->query("SELECT * FROM products WHERE status = 1 && active = 1 $limit");
        if ($sql->num_rows == 0) {
            box_alert('secondary','لا يوجد منتجات');
        }
        while ($fetch = $sql->fetch_assoc()) {
        $sqlf = $this->connect()->query("SELECT * FROM favorate WHERE user_id ='{$_SESSION['id']}' && item_id ='{$fetch['id']}' && type ='product'");
        $rows = $sqlf->num_rows;
        if ($rows > 0) {
            $u =  1;
            $fetchf = $sqlf->fetch_assoc();
        }else{
            $u = 0;
        }
        ?>
            <main>
                <div class="container col-md-4 col-sm-6 col-xs-12 p-20 mt-10 list-product">
                   <div class="text-center" id="result-fav"></div>
                    <div class="row">
                        <div class="col-xs-3 img-product text-center">
                            <img src="images/products_img/<? echo $fetch['main_img'];?>" width="80">
                        </div>
                        <div class="col-xs-9 all-info">
                            <div class="product-info">
                                <h4><? echo $fetch['name'];?></h4>
                                <label class="rating"> <? show_rating($fetch['rating']);?></label> / 
                                <b><label class="mr-10"><i class="fa fa-money text-success">&nbsp;</i> </label>
                                <label><b> <? echo $fetch['price'];?> شيكل </label></b><br>
                                <label><b> ينصح باستخدامه : </b></label>&nbsp;&nbsp;<label> <? echo $fetch['uses'] == 1 ? 'نعم':'لا ';?> </label>
                                <div style="display:none;"><? echo $fetch['source'];?></div>
                            </div>
                        </div>
                    </div><hr>
                    <div class="d-flex justify-content-between install mt-3">
                        <a href="index.php?page=Products_details&&product_id=<? echo $fetch['id'];?>"> عرض تفاصيل أكثر  >></a>
                        <a href="index.php?page=Allrating&&product_id=<? echo $fetch['id'];?>&&type=product">عرض التقييمات &nbsp;<i class="fa fa-angle-right"></i></a>
                        <?
                            if($role->r("role") == 2){
                                echo '<a href="index.php?page=Edit&&product_id='.$fetch['id'].'&&S=edit_product"> &nbsp;<i class="fa fa-edit h5"></i></a>';
                            }
                            if($fetch['user_id'] != $_SESSION['id'] || $role->r("role") == 2){
                                if ($u == 0) {?>
                                    <h4 class="favorate" data-u="0" data-id = "<? echo $fetchf['id'];?>" data-userid = "<? echo $_SESSION['id'];?>" data-itemid = "<? echo $fetch['id'];?>" data-type="product"><i class="fa fa-heart text-gray"></i></h4>
                                <? }elseif($u == 1){?>
                                    <h4 class="favorate" data-u="1" data-id = "<? echo $fetchf['id'];?>" data-userid = "<? echo $_SESSION['id'];?>" data-itemid = "<? echo $fetch['id'];?>" data-type="product"><i class="fa fa-heart text-danger"></i></h4>          
                            <? }
                            }
                        ?>          
                    </div>
                </div>
            </main>
        <?    
        }
    }

    function products_details()
    {
        $user_id = validation($_SESSION['id']);
        $role = new role;
        $modals = new Modalss;
        $p_id = validation($_GET['product_id']);
        $sql = $this->connect()->query("SELECT p.* , u.username FROM products AS p JOIN users AS u ON p.user_id = u.id WHERE p.id = '$p_id'");
        $sqlimg = $this->connect()->query("SELECT * FROM products_images WHERE product_id = '$p_id'");
        $rows = $sqlimg->num_rows;
        while ($fetch = $sql->fetch_assoc()) {
            switch ($fetch['status']) {
                case 0:
                    $msg = '<i class="fa fa-exchange"></i>&nbsp;<span class="text-info"><i> قيد المراجعة </i></span>';
                    break;
                case 1:
                    $msg = '<i class="fa fa-check"></i>&nbsp;<span class="text-success"><i> تم النشر </i></span>';
                    break;
                case 2:
                    $msg = '<a href="#show-result-pending'.$fetch['id'].'" class="text-warning" data-toggle="modal" data-id='.$fetch['id'].'><i class="fa fa-exclamation-triangle"></i>&nbsp;
                            <span class="text-warning"><i> معلق </i></span></a>';
                    break;
                case 3:
                    $msg = '<a href="#show-result-reject'.$fetch['id'].'" class="text-danger" data-toggle="modal" data-id='.$fetch['id'].'><i class="fa fa-close"></i>&nbsp;
                            <span class="text-danger"><i>  مرفوض </i></span></a>';
                    break;
                default:
                    break;
            }
        ?>
        <div class="container-fluied">
            <div class="form-add p-10">
                <div class="container-fliud">
                    <div class="wrapper row">
                        <div class="preview col-md-6">
                            <div class="preview-pic tab-content">
                                <div class="tab-pane active" id="pic-1"><img src="./images/products_img/<? echo $fetch['main_img'];?>" class="myImg" data-img="#myModal-img<? echo $fetch['id'];?>" id="big_img"/></div>
                            </div>
                            <ul class="preview-thumbnail nav nav-tabs">
                            <li class="active"><a data-target="#pic-1" data-toggle="tab"><img src="./images/products_img/<? echo $fetch['main_img'];?>" class="small_img" /></a></li>
                            <?
                                if ($rows > 0) {
                                    for ($j=0; $j < $rows; $j++) { 
                                        $fetch_img = $sqlimg->fetch_assoc();
                                        echo '<li><a data-target="#pic-'.$j.'" data-toggle="tab"><img src="./images/products_img/'.$fetch_img['image'].'" class="small_img" /></a></li>';
                                    }
                                }
                            ?>
                            </ul>
                        </div>
                        <div class="details col-md-6">
                            <h3 class="product-title"><? echo $fetch['name'];?></h3>
                            <div class="rating">
                                <? show_rating($fetch['rating']);?>
                            </div>
                            <h6 class=""> السعر التقريبي : <label><i class="fa fa-money text-success">&nbsp;</i> </label>
                                <label><b> <? echo $fetch['price'];?> شيكل </label></b><br></h6><hr>
                            <h6 class=""> الوصف والملاحظات :  </h6>
                            <p class="product-description mt-1"><i> <? echo $fetch['descreption'];?> </i></p><hr>
                            <h6 class="uses"><b>هل ينصح باستخدامه : </b>&nbsp;&nbsp;<i> <? echo $fetch['uses'] == 1 ? 'نعم':'لا ';?> </i></h6>
                            <a href="<? echo $fetch['source'];?>" target="blanek"><p>رابط مصدر المنتج >>></p></a><hr>
                            <? 
                                if($role->r('role') == 2 || $_GET['S'] == 'product_req') {
                                    echo "<p> حالة الطلب : $msg </p>";
                                }
                            ?>
                            <p class="date">تمت الاضافة : &nbsp;<label class="vote"><? get_date($fetch['date_created'])?></label> >> بواسطة :
                            <?
                                if($role->r('role') == 2){
                                    echo '<a href="index.php?page=Member_details&&user_id='.$fetch['user_id'].'&&p=member_info">'.$fetch['username'].'</a>';
                                }else{
                                    echo "<label class='vote'> ".$fetch['username']."</label>";
                                }
                            ?>
                            <hr>
                            <?
                                if (validation($_GET['S']) == 'product_req') {
                                    if($fetch['status'] == 0 || $fetch['status'] == 2){
                                        if($role->r("role") == 1 && $fetch['user_id'] == $user_id){
                                            echo '  
                                                <div class="mt-15 order-btn">
                                                    <a href="index.php?page=Edit&&product_id='. $fetch['id'].'&&S=edit_product" class="btn-success btn-info"> تعديل </a>
                                                    <a href="#delete-order'.$fetch['id'].'" class="btn-danger" id="del_page" data-toggle="modal"> حذف </a>
                                                </div>';
                                        }
                                    }
                                }
                                if($role->r("role") == 2){?>
                                    <div class="mt-15 order-btn text-center">
                                        <a href="index.php?page=Edit&&product_id=<? echo $fetch['id'];?>&&S=edit_product" class="btn-info"><i class="fa fa-edit"></i> تعديل  </a>
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
                                }
                                    $id = $fetch['id'];
                                    $user_id = $fetch['user_id'];
                                    $data_t = 'products';
                                    $data_img = 'products_images';
                                    $data_routs = "orders&&type=$data_t&&st={$_GET['st']}";
                                    $type = 'product';
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
    function addproducts()
    {
        $user_id = $_SESSION['id'];
        $date_created = time();
        $name = validation($_POST['name']);
        $url = validation($_POST['url']);
        $price = validation($_POST['price']);
        $descreption = validation($_POST['descreption']);
        $rating = validation($_POST['rating']);
        $uses = validation($_POST['uses']);

        $main_img_name = $_FILES['main_img']['name'];
        $main_img_tmp = $_FILES['main_img']['tmp_name'];
        $main_img_size = $_FILES['main_img']['size'];

        $ccc = count($_FILES['allimg_product']['name']);

        $val = validate_products($name,$url,$price,$descreption,$rating,$uses);
        $val_mainimg = validate_img($main_img_name , $main_img_size , 1 , 1);

        $all_img_name = $_FILES['allimg_product']['name'];
        $all_img_size = $_FILES['allimg_product']['size'];
        $val_allimg = validate_img($all_img_name,$all_img_size,0,7);

        if(isset($_POST['addproducts'])){
            if(empty($val)){
                if(empty($val_mainimg)){
                    $new_mainimgname = rand(1000, 10000).$main_img_name;
                    $sql = "INSERT INTO products(user_id,date_created,name,source,rating,price,descreption,main_img,uses,status,active) 
                    VALUES('$user_id','$date_created','$name','$url','$rating','$price','$descreption','$new_mainimgname','$uses',0,0)";
                    $query = $this->connect()->query($sql);
                    if($query){
                        move_uploaded_file($main_img_tmp, './images/products_img/'.$new_mainimgname);
                        $mid = $this->connect()->query("SELECT MAX(id) as id FROM products WHERE user_id = $user_id");
                        $lid = $mid->fetch_assoc();
                        $product_id =  $lid['id'];
                        if($product_id > 0 && $ccc > 0){
                            if(empty($val_allimg)) {
                                for ($i=0; $i <$ccc ; $i++) {
                                    $all_img_name = $_FILES['allimg_product']['name'][$i];
                                    $all_img_tmp = $_FILES['allimg_product']['tmp_name'][$i];
                                    $all_img_size = $_FILES['allimg_product']['size'][$i];
                                    $new_allimgname = rand(1000, 10000).$all_img_name;
                                    $sqlimg = "INSERT INTO products_images(product_id,image)VALUES('$product_id','$new_allimgname')";
                                    $queryimg = $this->connect()->query($sqlimg);
                                    move_uploaded_file($all_img_tmp,'./images/products_img/'.$new_allimgname); 
                                } 
                            }else{
                                box_alert('danger',$val_allimg);
                            }
                        }
                        $m = new Notifications;
                        $m->save_notification($product_id,$user_id,'admin','تم ارسال طلب لاضافة منتج جديد','secondary',1);
                        header("location:index.php?page=notic_details&&product_id=$product_id&&S=product_req");
                        echo '<input type="hidden" id="notification" value="success"/>';
                    }else{
                        box_alert('danger','خطأ ! لم يتم اضافة المنتج');
                }
                }else{
                    box_alert('danger',$val_mainimg);
                }
            }else{
                box_alert('danger',$val);
            }
        }
    }
}
