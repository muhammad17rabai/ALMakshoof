<?
    if(!isset($_SESSION['id'])){
        header("location:index.php?page=login");
}
class getmyfav extends DB
{
    function count()
    {
        $count = $this->connect()->query("SELECT * FROM favorate WHERE user_id = '{$_SESSION['id']}'")->num_rows;?>
        <div class="container">
            <div class="row">
                <div class="p-10 m-10">
                    <h3 class="h-title t-uppercase"><i class="fa fa-heart alert-gray"></i>&nbsp; المفضلة ( <? echo $count;?> )</h3>
                </div>
        <div id="result-fav"></div>
<?
    }
    function myfav()
    {
        $sql = $this->connect()->query("SELECT p.*,pr.*, u.*, f.* FROM favorate as f LEFT JOIN pages as p ON p.id = f.item_id
        LEFT JOIN products as pr ON pr.id = f.item_id LEFT JOIN users as u ON u.id = f.item_id WHERE f.user_id = '{$_SESSION['id']}' ORDER BY f.id DESC");
        if($sql->num_rows == 0){
            box_alert('secondary',' قائمة المفضلة لديك فارغة ');
        }
        while ($fetch = $sql->fetch_assoc()){
            if($fetch['type'] == 'page') {
            $sqlimg = $this->connect()->query("SELECT * FROM page_images WHERE page_id = '{$fetch['item_id']}'");
            ?>
            <div class="col-md-4 col-sm-6 col-xs-12 list-page m-5">
                <div class="p-3">
                    <div class="d-flex flex-row mb-3"><img src="<? echo $fetch['logo'];?>" class="rounded-circle" width="90"  style="height:50px;">
                        <div class="d-flex flex-column mt-3"><a href="<? echo $fetch['page_url'];?>" target="blanek"><h5><? echo $fetch['page_name'];?></h5></a>
                            <span style="font-size: 1.7rem;"><? show_rating($fetch['rate']);?></span>
                        </div>
                    </div>
                    <h6><? echo substr($fetch['description'],0,150).' .......';?></h6><hr>
                    <div class="d-flex justify-content-between install mt-3">
                        <a href="#detailspage<? echo $fetch['id'];?>" data-toggle="modal"> عرض التفاصيل >></a>
                        <a href="index.php?page=Allrating&&page_id=<? echo $fetch['item_id'];?>&&type=page">عرض التقييمات &nbsp;<i class="fa fa-angle-right"></i></a>
                        <h3 class="favorate" data-u="1" data-p="fav" data-id = "<? echo $fetch['id'];?>" data-userid = "<? echo $_SESSION['id'];?>" data-pageid = "<? echo $fetch['item_id'];?>" data-type="page">
                            <span class="m-2 text-danger" style="font-size:15px;"> <i class="fa fa-trash"></i></span>
                        </h3>                   
                    </div>
                </div>
            </div>
            <!-- Modal details -->
            <div class="modal text-center" id="detailspage<? echo $fetch['id'];?>" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div id="result"></div>
                    <div class="modal-content text-right">
                        <div class="modal-header">
                            <button type="button" class="clos" data-dismiss="modal" aria-hidden="true"><b>x</b></button>
                            <h6> الملاحظات والتفاصيل :  </h6>
                        </div>
                        <div class="col-xs-12">
                            <p class="text-muted mb-20 mt-10"><span><? echo $fetch['description'];?></p>
                        <hr></div>
                        <div class="col-xs-12 pb-10">
                            <span style="border-bottom: 1px solid;"><i class="fa fa-picture-o text-info" style="font-size: 15px;"></i>&nbsp; الصور والدلائل : </span>
                        </div>
                        <div class="col-xs-12 list-img-screenshot">
                            <?
                                while ($fetchimg = $sqlimg->fetch_assoc()) {
                                    echo '<a href="#zoom-img"><img src="./images/'.$fetchimg['image'].'" class="col-xs-3 myImg" data-img="#myModal-img'.$fetch['id'].'" data-detail="#detailspage'.$fetch['id'].'" data-dismiss="modal" ></a>';
                                }
                            ?>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal details-->
        <?}elseif($fetch['type'] == 'product'){?>
            <main>
                <div class="col-md-4 col-sm-6 col-xs-12 p-20 mt-5 list-product m-5">
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
                                <label><b> ينصح به : </b></label>&nbsp;&nbsp;<label> <? echo $fetch['uses'] == 1 ? 'نعم':'لا ';?> </label>
                            </div>
                        </div>
                    </div><hr>
                    <div class="d-flex justify-content-between install mt-3">
                        <a href="index.php?page=Products_details&&product_id=<?echo $fetch['id'];?>" class="show-details-member" data-toggle="modal"> عرض التفاصيل  >></a>
                        <a href="index.php?page=Allrating&&product_id=<? echo $fetch['item_id'];?>&&type=product">عرض التقييمات &nbsp;<i class="fa fa-angle-right"></i></a>
                        <h3 class="favorate" data-u="1" data-p="fav" data-id = "<? echo $fetch['id'];?>" data-userid = "<? echo $_SESSION['id'];?>" data-pageid = "<? echo $fetch['item_id'];?>" data-type="product">
                            <span class="m-2 text-danger" style="font-size:15px;"> <i class="fa fa-trash"></i></span>
                        </h3>                   
                    </div>
                </div>
            </main>
        <?}elseif($fetch['type'] == 'broker'){
            $item_id = $fetch['item_id'];
            $sql_rate = $this->connect()->query("SELECT * FROM rating WHERE item_id = $item_id && type = 'broker'");
            $n = 0;
            $rows_rate = 0;
            if ($sql_rate->num_rows > 0) {
                while ($fetch_rate = $sql_rate->fetch_assoc()) {
                    $n = $n + $fetch_rate['rate'];
                }
                $rows_rate = ($n / $sql_rate->num_rows);
            }?>
            <main>
                <div class="col-md-4 col-sm-6 col-xs-12 p-20 mt-5 list-broker m-5">
                    <div class="row">
                        <div class="col-xs-3 img-product text-center">
                            <img src="<? echo $fetch['avatar'];?>" width="80">
                        </div>
                        <div class="col-xs-9 all-info">
                            <div class="product-info">
                                <h4><? echo $fetch['username'];?></h4>
                                <label> <? echo $fetch['phone'];?></label> /
                                <label class="rating"><? show_rating(round($rows_rate,0));?></label><br>
                                <label><b><label><i class="fa fa-exchange">&nbsp;</i> </label> وسيط للمتاجر العالمية </label>
                            </div>
                        </div>
                    </div><hr>

                    <div class="d-flex justify-content-between install mt-3">
                        <a href="index.php?page=Broker_details&&user_id=<? echo $fetch['item_id'];?>" class="show-details-member" data-toggle="modal"> عرض التفاصيل  >></a>
                        <a href="index.php?page=Allrating&&broker_id=<? echo $fetch['item_id'];?>&&type=broker">عرض التقييمات &nbsp;<i class="fa fa-angle-right"></i></a>
                        <h3 class="favorate" data-u="1" data-p="fav" data-id = "<? echo $fetch['id'];?>" data-userid = "<? echo $_SESSION['id'];?>" data-pageid = "<? echo $fetch['item_id'];?>" data-type="product">
                            <span class="m-2 text-danger" style="font-size:15px;"> <i class="fa fa-trash"></i></span>
                        </h3>                   
                    </div>
                </div>
            </main>
        <?}?>
        <!--============================== modal zoom img ===========================-->
        <div id="myModal-img<? echo $fetch['id'];?>" class="modal-img m-0">
            <a href="#detailspage<? echo $fetch['id'];?>" class="close close-img" data-toggle="modal" aria-hidden="true" data-details="#detailspage<? echo $fetch['id'];?>"><b>x</b></a>
            <img class="modal-content-img img01">
        </div>
        <!--============================== End modal zoom img ===========================-->
    <?    
        }
    }
}
    $myfav = new getmyfav;
    $myfav->count();
    $myfav->myfav();
    ?>
    </div>
</div>