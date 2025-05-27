<?
if (file_exists('config.php')) {
    require_once 'config.php';
}else{
    require_once 'connect.php';
}
require_once 'functions.php';

class AllOrders extends DB
{
    function getallorder()
{
    $role = new role;   
    $user_id = $_SESSION['id'];
    $status = validation($_GET['st']);
    $table = validation($_GET['type']);
    if ($table == "pages") {
        $table_img = "page_images";
        $item_id = "page_id";
        $type = 'page';
        $s = 'edit_page';
    }elseif($table == "products"){
        $table_img = "products_images";
        $item_id = "product_id";
        $type = 'product';
        $s = 'edit_product';
    }elseif($table == "brokers"){
        $table_img = " ";
        $item_id = "broker_id";
        $type = 'broker';
        $s = 'edit_broker';
    }
    if ($role->r("role") == 2) {
        if ($status == 4 || $status == null) {
            if($table == 'brokers'){
                $sql = $this->connect()->query("SELECT u.*, b.* FROM brokers as b LEFT JOIN users as u ON u.id = b.user_id ORDER BY b.id DESC");
            }else{
                $sql = $this->connect()->query("SELECT * FROM $table ORDER BY id DESC");
            }
        }elseif($table == 'brokers'){
            $sql = $this->connect()->query("SELECT u.*, b.* FROM brokers as b INNER JOIN users as u ON u.id = b.user_id WHERE b.status = $status ORDER BY b.id DESC");
        }else{
            $sql = $this->connect()->query("SELECT * FROM $table WHERE status = '$status' ORDER BY id DESC");
        }
        
    }else {
        if ($status == 4 || $status == null) {
            $sql = $this->connect()->query("SELECT * FROM $table WHERE user_id = '$user_id' ORDER BY id DESC");
        }else {
            $sql = $this->connect()->query("SELECT * FROM $table WHERE user_id = '$user_id' && status = '$status' ORDER BY id DESC");
        }
    }
    $rows = $sql->num_rows;
    if ($rows == 0) {
        box_alert('secondary','لا يوجد طلبات');
    }
    while ($fetch = $sql->fetch_assoc()){
    if ($table != 'brokers') {
        $sqlimg = $this->connect()->query("SELECT * FROM $table_img WHERE $item_id = '{$fetch['id']}'");
    }else{
        $broker_id = $fetch['id'];
        $sql_site = $this->connect()->query("SELECT s.*, sb.* FROM sites as s LEFT JOIN broker_sites as sb ON sb.site_id = s.id WHERE sb.broker_id = $broker_id");
    }
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
        }
    ?>
    <main>
        <div class="container col-lg-4 col-sm-6 col-xs-12">
            <div id="result-fav"></div>
            <div class="row d-flex">
                <div class="col-md-12 form-add card m-2">
                    <div class="p-3">
                        <?
                        if ($table == "pages") {?>
                            <div class="d-flex flex-row mb-3"><img src="<? echo $fetch['logo'];?>" class="rounded-circle" width="90"  style="height:50px;">
                                <div class="d-flex flex-column mt-3"><a href="<? echo $fetch['page_url'];?>" target="blanek"><h5><? echo $fetch['page_name'];?></h5></a><span class="text-black-50"><? get_date($fetch['date_created']);?>
                                    <span style="font-size: 1.7rem; margin:15px 10px"><? show_rating($fetch['rate']);?></span></span>
                                </div>
                            </div>
                        <?
                        }elseif($table == "products"){?>
                            <div class="d-flex flex-row mb-3"><img src="./images/products_img/<? echo $fetch['main_img'];?>" class="rounded-circle p-2" width="60" style="height:50px;">
                                <div class="d-flex flex-column mt-3"><a href="<? echo $fetch['source'];?>" target="blanek"><h5><? echo $fetch['name'];?></h5></a><span class="text-black-50"><? get_date($fetch['date_created']);?>
                                    <span style="font-size: 1.7rem; margin:15px 10px"><? show_rating($fetch['rating']);?></span></span>
                                </div>
                            </div>
                        <?
                        }elseif($table == "brokers"){?>
                            <div class="d-flex flex-row mb-3"><img src="<? echo $fetch['avatar'];?>" class="rounded-circle p-2" width="60" style="height:50px;">&nbsp;
                                <div class="d-flex flex-column mt-3"><a href="<? echo $fetch['source'];?>" target="blanek"><h5><? echo $fetch['username'];?></h5></a><span class="text-black-50"><? get_date($fetch['date_created']);?></span></div>
                            </div>
                        <?}?>
                        <h6><? echo 'حالة الطلب : ' .$msg ;?></h6><hr>
                        <?
                        if ($table == "pages") {?>
                            <div class="d-flex justify-content-between install mt-3">
                                <a href="index.php?page=page_details&&page_id=<? echo $fetch['id'];?>"> عرض التفاصيل >></a>
                            </div><hr>
                        <?
                        }elseif($table == "products"){?>
                            <div class="d-flex justify-content-between install mt-3">
                                <a href="index.php?page=Products_details&&product_id=<? echo $fetch['id'];?>"> عرض التفاصيل >></a>
                            </div><hr>
                        <?}
                        if($fetch['status'] == 0 || $fetch['status'] == 2){
                            if($role->r("role") == 1){?>
                                <div class="mt-15 order-btn">
                                    <?
                                        if ($table == 'brokers') {
                                            echo '<a href="index.php?page=Broker_details&&user_id='.$fetch['user_id'].'" class="btn-success btn-info" data-toggle="modal"> عرض التفاصيل  </a>';
                                        }else{
                                            echo '<a href="index.php?page=Edit&&'.$item_id.'='. $fetch['id'].'&&S='.$s.'" class="btn-success btn-info"> تعديل </a>';
                                        }
                                        echo '<a href="#delete-order'.$fetch['id'].'" class="btn-danger" id="del_page" data-toggle="modal"> حذف </a>';
                                    ?>
                                </div>
                            <?}
                        }
                        if($role->r("role") == 2){?>
                            <div class="mt-15 order-btn text-center">
                                <?
                                    if ($table == 'brokers') {?>
                                        <a href="index.php?page=Broker_details&&user_id=<? echo $fetch['user_id'];?>" class="btn-info" data-toggle="modal" data-user_id="<? echo $fetch['user_id'];?>"> عرض التفاصيل   </a>
                                    <?}else{?>
                                        <a href="index.php?page=Edit&&<? echo $item_id.'='.$fetch['id'].'&&S='.$s;?>" class="btn-info"><i class="fa fa-edit"></i> تعديل  </a>
                                   <? }
                                ?>
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
                        <?}?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?    
        $id = $fetch['id'];
        $user_id = $fetch['user_id'];
        $data_t = $table;
        $data_img = $table_img;
        if ($data_t == 'brokers') {
            $data_routs = "orders&&type=$table&&st={$_GET['st']}&&user_id=$user_id";
        }else{
            $data_routs = "orders&&type=$table&&st={$_GET['st']}";
        }
        $modals = new Modalss;
        $modals->modals($id,$user_id,$data_t,$data_img,$data_routs,$type);
        }
    }    
    }
?>