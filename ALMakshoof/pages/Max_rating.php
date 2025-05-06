<?
if(!isset($_SESSION['id'])){
    header("location:index.php?page=login");
}
$type = validation($_GET['type']);
switch ($type) {
    case 'pages':
        $tt = "الصفحات";
        break;
    case 'products':
        $tt = "المنتجات";
        break;
    default:
        break;
}
?>
<div class="container">
    <div class="row" id="myUL">
<?
class Max extends DB
{
    function getallpage()
    {
        $n = 0;
        $role = new role;
        $sql = $this->connect()->query("SELECT * FROM pages WHERE status = 1 && active = 1 ORDER BY id DESC");
        if($sql->num_rows == 0){
            box_alert('secondary',' لا يوجد صفحات لها تقييم عالي');
        }
        while ($fetch = $sql->fetch_assoc()){
        $rate = 0;$avg = 0;
        $item_id = $fetch['id'];
        $sqlf = $this->connect()->query("SELECT * FROM favorate WHERE user_id ='{$_SESSION['id']}' && item_id ='$item_id' && type ='page'");
        $sql_rate = $this->connect()->query("SELECT * FROM rating WHERE item_id = '$item_id' && type = 'page'");
        while ($fetch_rate = $sql_rate->fetch_assoc()) {
            $rate = $rate + $fetch_rate['rate'];
            $avg = ($rate + $fetch['rate'])/($sql_rate->num_rows + 1);
        }
        $rows = $sqlf->num_rows;
        if ($rows > 0) {
            $u = 1;
            $fetchf = $sqlf->fetch_assoc();
        }else{
            $u = 0;
        }
        if ($avg > 4) {
        $n  = 1;
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
    if ($n == 0) {
        box_alert('secondary',' لا يوجد صفحات لها تقييم عالي');
    }
    }   
    function getallproducts()
    {
        $n = 0;
        $role = new role;
        $sql = $this->connect()->query("SELECT * FROM products WHERE status = 1 && active = 1 ORDER BY id DESC");
        if($sql->num_rows == 0){
            box_alert('secondary',' لا يوجد منتجات لها تقييم عالي');
        }
        while ($fetch = $sql->fetch_assoc()){
        $rate = 0;$avg = 0;
        $item_id = $fetch['id'];
        $sqlf = $this->connect()->query("SELECT * FROM favorate WHERE user_id ='{$_SESSION['id']}' && item_id ='$item_id' && type ='product'");
        $sql_rate = $this->connect()->query("SELECT * FROM rating WHERE item_id = '$item_id' && type = 'product'");
        while ($fetch_rate = $sql_rate->fetch_assoc()) {
            $rate = $rate + $fetch_rate['rate'];
            $avg = ($rate + $fetch['rating'])/($sql_rate->num_rows + 1);
        }
        $rows = $sqlf->num_rows;
        if ($rows > 0) {
            $u = 1;
            $fetchf = $sqlf->fetch_assoc();
        }else{
            $u = 0;
        }
        if ($avg > 4) {
        $n  = 1;
        ?>

        <main class="m-5">
            <div class="container col-md-4 col-sm-6 col-xs-12 p-20 list-product">
                <div class="text-center" id="result-fav"></div>
                <div class="row">
                    <div class="col-xs-3 img-product text-center">
                        <img src="images/products_img/<? echo $fetch['main_img'];?>" width="80">
                    </div>
                    <div class="col-xs-9 all-info">
                        <div class="product-info">
                            <h5><? echo $fetch['name'];?></h5><span class="text-black-50"><? get_date($fetch['date_created']);?></span><br>
                            <label class="rating"> <? show_rating($fetch['rating']);?></label> / 
                            <b><label class="mr-10"><i class="fa fa-money text-success">&nbsp;</i> </label>
                            <label><b> <? echo $fetch['price'];?> شيكل </label></b><br>
                            <label><b> ينصح باستخدامه : </b></label>&nbsp;&nbsp;<label> <? echo $fetch['uses'] == 1 ? 'نعم':'لا ';?> </label>
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
    if ($n == 0) {
        box_alert('secondary',' لا يوجد منتجات لها تقييم عالي');
    }
    }
}
    $type = validation($_GET['type']);
    $max = new Max;
    switch ($type) {
        case 'pages':
            $max->getallpage();
            break;
        case 'products':
            $max->getallproducts();
            break;
        default:
            break;
    }
?>
    </div>
    <div id="result-search">
        <? box_alert('secondary','لا توجد نتائج')?>
    </div>
</div>