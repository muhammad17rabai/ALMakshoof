<?php
if(!isset($_SESSION['id'])){
    header("location:index.php?page=login");
}
require_once './functions/products_func.php';
class NoticDetails extends DB
{
    function page_req()
    {
        $page_id = $_GET['page_id'];
        $role = new role;
        $sql = "SELECT * FROM pages WHERE id = '{$_GET['page_id']}'";
        $query = $this->connect()->query($sql);
        $queryimg = $this->connect()->query("SELECT * FROM page_images WHERE page_id = '$page_id'");
        $fetch = $query->fetch_assoc();
        if($fetch['user_id'] != $_SESSION['id']){
            if ($role->r("role") != 2) {
                header("location:index.php?page=home"); 
            }
        }
        switch ($fetch['status']) {
            case 0:
                $msg = '<li><i class="fa fa-exchange"></i>&nbsp;</li><span class="text-info"><i> قيد المراجعة </i></span>';
                break;
            case 1:
                $msg = '<li><i class="fa fa-check"></i>&nbsp;</li><span class="text-success"><i> تم النشر </i></span>';
                break;
            case 2:
                $msg = '<a href="#show-result-pending'.$fetch['id'].'" class="text-warning" data-toggle="modal"><i class="fa fa-exclamation-triangle"></i>&nbsp;</li><span class="text-warning"><i> معلق </i></span></li></a>';
                break;
                case 3:
                $msg = '<a href="#show-result-reject'.$fetch['id'].'" class="text-danger" data-toggle="modal"><i class="fa fa-close"></i>&nbsp;<span class="text-danger"><i>  مرفوض </i></span></a>';
                break;
            default:
                # code...
                break;
        }
        switch ($fetch['logo']) {
            case 'icons/face.png':
                $type = 'صفحة فيسبوك';
                break;
            case 'icons/instagram.jpg':
                $type = 'صفحة انستقرام';
                break;
            case 'icons/store.jpg':
                $type = ' متجر الكتروني ';
                break;
            default:
                # code...
                break;
        }
        $rows = $query->num_rows;
        if ($rows == 0) {
            box_alert('secondary','لا يوجد طلبات');
            header("location:index.php?page=home");
        }else{
        if ($_GET['S'] == 'page_req' && $fetch['status'] == 0 && $role->r("role")!=2) {
            box_alert('success','تم ارسال الطلب بنجاح');
        }
        ?>
        <div class="col-xs-12 p-10 mt-15 form-add">
            <?
                if ($fetch['status'] == 1 && $fetch['active'] == 0) {
                    $msg = '<li><i class="fa fa-close"></i>&nbsp;</li><span class="text-danger"><i> تم الغاء النشر </i></span>';
                    //box_alert('danger','تم الغاء نشر الصفحة ');
                }
            ?>
            <div id="result"></div>
            <div class="col-xs-12 text-center order-logo">
                <img src="<? echo $fetch['logo'];?>">
            </div>
            <div class="col-xs-12 mt-20">
                <h6><b> اسم الصفحة  : </b><a href="<? echo $fetch['page_url'];?>" target="_blank"><i> <? echo $fetch['page_name'];?> </i></a></h6><hr>
                <div class="col-xs-6">
                    <ul class="list-inline">
                        <li><i class="fa fa-calendar"></i></li>&nbsp; <? get_date( $fetch['date_created']);?>
                    </ul>
                </div>
                <div class="col-xs-6">
                    <ul class="list-inline">
                        <? echo $msg; ?>
                    </ul>
                </div> 

                <div class="col-xs-12 mt-15">
                    <div class="showrating">
                        <? show_rating($fetch['rate']);?>
                    </div>
                </div>
                <div class="col-xs-12">
                    <hr>
                    <label> الفئة :  <span class="text-muted mb-20 mt-10"><? echo category($fetch['category']);?></span></label>
                </div>
                <div class="col-xs-12">
                    <hr>
                    <h6> الملاحظات والتفاصيل :  </h6>
                    <p class="text-muted mb-20 mt-10"><span><? echo $fetch['description'];?></span></p>
                <hr></div>
                
                <h6><b> الصور والدلائل : </b></h6>
                <div class="row mt-10 img-order-screenshot">
                    <?
                        while ($fetchimg = $queryimg->fetch_assoc()) {
                        echo '
                         <div class="col-xs-3">
                            <a href="#zoom-img" data-toggle="modal"><img src="./images/'.$fetchimg['image'].'" class="myImg" data-img="#myModal-img'.$fetch['id'].'" style="width: 80px; height: auto;"></a>
                        </div>
                        ';   
                    }?>
                </div><hr>
                <?php
                if($fetch['status'] == 0 || $fetch['status'] == 2){
                    if($role->r("role") == 1){?> 
                            <div class="mt-15 order-btn">
                                <a href="index.php?page=Edit&&page_id='.$page_id.'&&S=edit_page" class="btn-success btn-info"> تعديل </a>
                                <?
                                    if ($fetch['active'] == 0) {
                                        echo '<a href="#delete-order" class="btn-danger" id="del_page" data-toggle="modal"> حذف </a>';
                                    }
                                ?>
                            </div>
                        <?}     
                    }
                    if($role->r("role") == 2){?>
                        <div class="mt-15 order-btn text-center">
                            <a href="index.php?page=Edit&&page_id=<? echo $_GET['page_id'];?>&&S=edit_page" class="btn-info"><i class="fa fa-edit"></i> تعديل  </a>
                            &nbsp;<a href="#delete-order<? echo $page_id;?>" class="btn-danger" data-toggle="modal"><i class="fa fa-trash"></i> حذف </a>
                        </div>
                        <div class="mt-15 order-btn text-center">
                            <?
                                if($fetch['status'] != 1){
                                    echo '<a href="#acceppt-order'.$fetch['id'].'" class="btn-success" data-toggle="modal"><i class="fa fa-check"></i> قبول </a>';
                                }
                            ?>
                            <a href="#pendding-order<? echo $page_id;?>" class="btn-warning" data-toggle="modal"><i class="fa fa-exclamation-triangle"></i> تعليق </a>
                            <a href="#reject-order<? echo $page_id;?>" class="btn-danger" data-toggle="modal"><i class="fa fa-close"></i> رفض </a>
                        </div>
                    <? } }?>
            </div>
        </div>
        <!--============================== modal zoom img ===========================-->
        <div id="myModal-img<? echo $fetch['id'];?>" class="modal-img">
            <button type="button" class="close close-img" data-dissmision="modal" aria-hidden="true"><b>x</b></button>
            <img class="modal-content-img img01">
        </div>
        <!--============================== End modal zoom img ===========================-->

        <?
            $id = $page_id;
            $user_id = $fetch['user_id'];
            $data_t = 'pages';
            $data_img = 'page_images';
            $routs2 = (string)$page_id;
            $data_routs = "notic_details&&page_id=$routs2&&S=page_req";
            $type = 'page';
            $modals = new Modalss;
            $modals->modals($id,$user_id,$data_t,$data_img,$data_routs,$type);
    }

}
$noticdetails = new NoticDetails;
$product_details = new ManageProducts;
switch ($_GET['S']) {
    case 'page_req':
        $noticdetails->page_req();
        break;
    case 'product_req':
        $product_details->products_details();
        break;
    default:
        break;
}
