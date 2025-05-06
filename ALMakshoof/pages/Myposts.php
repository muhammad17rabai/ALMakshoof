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
<div class="container mt-5 text-right">
    <div class="row mt-10" id="myUL">
        <div class="col-xs-12 d-flex justify-content-between install">
            <label class="form-control bg-white text-st col-xs-8"> نوع المنشورات  : </label>
            <select class="form-control bg-white" id="type_post">
                <option value=""><? echo $tt;?></option>
                <option value="pages">  منشورات الصفحات </option>
                <option value="products">  منشورات المنتجات </option>
            </select>
        </div>
        <div class="form-add col-xs-12 m-2 p-5 pr-10">
            <span class=""><i class="fa fa-edit text-info"></i> منشوراتي : </span>
        </div>
<?
class getmyposts extends DB
{
    function getallpage()
    {
        $sql = $this->connect()->query("SELECT * FROM pages WHERE status = 1 && active = 1 && user_id = '{$_SESSION['id']}' ORDER BY id DESC");
        if($sql->num_rows == 0){
            box_alert('secondary',' لا يوجد لديك أي منشورات ');
        }
        while ($fetch = $sql->fetch_assoc()){
        $sqlimg = $this->connect()->query("SELECT * FROM page_images WHERE page_id = '{$fetch['id']}'");
        $rows = $this->connect()->query("SELECT * FROM favorate WHERE item_id ='{$fetch['id']}' && type ='page'")->num_rows;
        switch ($fetch['status']) {
            case 1:
                $st = ' <i class="fa fa-check text-success"></i>&nbsp;<span class="text-success" style="font-size:13px;"> تم النشر </span>';
                break;
        }
        ?>
        <main>
            <div class="col-md-4 form-add card m-2 mt-5">
                <div class="p-3">
                    <div class="d-flex flex-row mb-3"><img src="<? echo $fetch['logo'];?>" class="rounded-circle" width="90"  style="height:50px;">
                        <div class="d-flex flex-column mt-3"><a href="<? echo $fetch['page_url'];?>" target="blanek"><h5><? echo $fetch['page_name'];?></h5></a><span class="text-black-50"><? get_date($fetch['date_created']);?></span>
                            <span style="font-size: 1.7rem;"><? show_rating($fetch['rate']); echo'&nbsp;&nbsp;'.$st; ?></span>
                        </div>
                    </div>
                    <h6><? echo substr($fetch['description'],0,150).' .......';?></h6><hr>
                    <div class="d-flex justify-content-between install mt-3">
                        <a href="index.php?page=page_details&&page_id=<? echo $fetch['id'];?>" data-toggle="modal"> عرض التفاصيل &nbsp;<i class="fa fa-angle-right"></i></a>
                        <a href="index.php?page=Allrating&&page_id=<? echo $fetch['id'];?>&&type=page">عرض التقييمات &nbsp;<i class="fa fa-angle-right"></i></a>
                            <h3 class="count_favorate"><span class="m-2" style="font-size:15px;">
                            <? 
                                if($rows > 0){
                                    echo $rows;
                                    echo '</span><i class="fa fa-heart text-danger" style="font-size:15px;"></i>';
                                }?>
                            </h3>                   
                    </div>
                </div>
            </div>
        </main>
    <?    
        }

    }   
    function getallproducts()
    {
        $sql = $this->connect()->query("SELECT * FROM products WHERE status = 1 && active = 1 && user_id = '{$_SESSION['id']}' ORDER BY id DESC");
        if($sql->num_rows == 0){
            box_alert('secondary',' لا يوجد لديك أي منشورات ');
        }
        while ($fetch = $sql->fetch_assoc()){
        $sqlimg = $this->connect()->query("SELECT * FROM products_images WHERE product_id = '{$fetch['id']}'");
        $rows = $this->connect()->query("SELECT * FROM favorate WHERE item_id ='{$fetch['id']}' && type ='product'")->num_rows;
        switch ($fetch['status']) {
            case 1:
                $st = ' <i class="fa fa-check text-success"></i>&nbsp;<span class="text-success" style="font-size:13px;"> تم النشر </span>';
                break;
        }
        ?>
        <main>
            <div class="col-md-4 col-sm-6 col-xs-12 p-20 mt-5 list-product m-5">
                <div class="row">
                    <div class="col-xs-3 img-product text-center">
                        <img src="images/products_img/<? echo $fetch['main_img'];?>" width="80">
                    </div>
                    <div class="col-xs-9 all-info">
                        <div class="product-info">
                            <h4><? echo $fetch['name'];?></h4><span class="text-black-50"><? get_date($fetch['date_created']);?></span><br>
                            <label class="rating"> <? show_rating($fetch['rating']);?></label> / 
                            <b><label class="mr-10"><i class="fa fa-money text-success">&nbsp;</i> </label>
                            <label><b> <? echo $fetch['price'];?> شيكل </label></b><br>
                            <label><b> ينصح به : </b></label>&nbsp;&nbsp;<label> <? echo $fetch['uses'] == 1 ? 'نعم':'لا ';?> </label>&nbsp;&nbsp;&nbsp;
                            <label><? echo $st;?></label>
                        </div>
                    </div>
                </div><hr>
                <div class="d-flex justify-content-between install mt-3">
                    <a href="index.php?page=Products_details&&product_id=<?echo $fetch['id'];?>" class="show-details-member" data-toggle="modal"> عرض تفاصيل أكثر  >></a>
                    <a href="index.php?page=Allrating&&product_id=<? echo $fetch['id'];?>&&type=product">عرض التقييمات &nbsp;<i class="fa fa-angle-right"></i></a>
                        <h3 class="count_favorate"><span class="m-2" style="font-size:15px;">
                        <? 
                            if($rows > 0){
                                echo $rows;
                                echo '</span><i class="fa fa-heart text-danger" style="font-size:15px;"></i>';
                            }?>
                        </h3>                   
                </div>
            </div>
        </main>
    <?
        }
    }
}
    $type = validation($_GET['type']);
    $myposts = new getmyposts;
    switch ($type) {
        case 'pages':
            $myposts->getallpage();
            break;
        case 'products':
            $myposts->getallproducts();
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