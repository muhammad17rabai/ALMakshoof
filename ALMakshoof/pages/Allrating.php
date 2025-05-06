<?
    if(!isset($_SESSION['id'])){
        header("location:index.php?page=login");
    }
    require_once './functions/rating_func.php';
    $type = validation($_GET['type']);
    if(validation($_GET['page_id']) != null) {
        $url = 'page_id='.validation($_GET['page_id']).'&&type=page';
    }elseif(validation($_GET['product_id']) != null){
        $url = 'product_id='.validation($_GET['product_id']).'&&type=product';
    }elseif(validation($_GET['broker_id']) != null){
        $url = 'broker_id='.validation($_GET['broker_id']).'&&type=broker';
    }
?>
<!-- –––––––––––––––[ PAGE CONTENT ]––––––––––––––– -->
<div role="tabpanel" class="tab-pane ptb-20" id="reviews">
    <div class="headers">
        <ul class="nav nav-tabs pr-5" role="tablist">
            <li class="allrating-btn active" role="presentation">
                <a href="index.php?page=Allrating&&<? echo $url;?>"><i class="fa fa-user ml-10"></i> التقييمات والاراء </a>
            </li>
            <li class="addrating-btn" role="presentation">
                <a href="index.php?page=Addrating&&<? echo $url;?>"><i class="fa fa-comment ml-10"></i> اضافة تقييم </a>
            </li>
        </ul>
    </div><br>
    <div class="posted-review panel p-10">
        <?
            $getrating = new Rating;
            $getrating->getallrating();
        ?>
    </div>
 
</div>
    <!-- –––––––––––––––[ END PAGE CONTENT ]––––––––––––––– -->