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
<!------------------------------------ add rating --------------------------------->
<div role="tabpanel" class="tab-pane ptb-20" id="reviews">
    <div class="headers ">
        <ul class="nav nav-tabs pr-5" role="tablist">
            <li class="allrating-btn" role="presentation">
                <a href="index.php?page=Allrating&&<? echo $url;?>"><i class="fa fa-user ml-10"></i> التقييمات والاراء </a>
            </li>
            <li class="addrating-btn active" role="presentation">
                <a href="index.php?page=Addrating&&<? echo $url;?>"><i class="fa fa-comment ml-10"></i> اضافة تقييم </a>
            </li>
        </ul>
    </div><br>
    <div class="form-add p-20">
        <?
            $addrating = new Rating;
            $addrating->addrating();
        ?>
        <form action="index.php?page=Addrating&&<? echo $url;?>" method="post">
            <div class="form-group">
                <label for="rating">  التقييم : </label>
                <div class="addrating">
                    <input type="hidden" name="rating" id="rating">
                    <i class='bx bx-star star' style="--i: 0;" data-c="1"></i>
                    <i class='bx bx-star star' style="--i: 1;" data-c="2"></i>
                    <i class='bx bx-star star' style="--i: 2;" data-c="3"></i>
                    <i class='bx bx-star star' style="--i: 3;" data-c="4"></i>
                    <i class='bx bx-star star' style="--i: 4;" data-c="5"></i>
                </div>
            </div>
            <div class="form-group">
                <textarea class="form-control" name="comment_rate" placeholder="اكتب تعليق ...." rows="6"></textarea>
            </div>
            <div class="form-group">
                <button type="submit" name="save_rate" class="btn btn-info"> اضافة </button>
            </div>
        </form>
    </div>
</div>