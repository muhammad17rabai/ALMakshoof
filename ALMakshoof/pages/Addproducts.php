<?
if(!isset($_SESSION['id'])){
    header("location:index.php?page=login");
}
require_once './functions/products_func.php';
$check_sub = new info;
$cc = $check_sub->check_subscribe();
?>
<!-- –––––––––––––––[ PAGE CONTENT ]––––––––––––––– -->
<main id="mainContent" class="main-content">
    <div class="headers mt-20 m-5">
        <ul class="nav nav-tabs justify-content-center" role="tablist">
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=Allproducts">
                    <i class="now-ui-icons objects_umbrella-13"></i>  قائمة المنتجات
                </a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="index.php?page=Addproducts">
                    <i class="now-ui-icons objects_umbrella-13"></i> اضافة منتج جديد +
                </a>
            </li>
        </ul>
    </div>
    <?
        if ($role->r('role') == 1) {
            $url = '<a href="index.php?page=subscribe"> من هنا </a>';
            if ($cc == 'not_exit') {
                $text = "أنت غير مشترك !! لتتمكن من اضافة المنتجات الرجاء الاشتراك".$url;
                echo '<div class="alert alert-danger m-5 mt-20">'.$text.'</div>';
            }elseif($cc == 0){
                $text = " طلبك الاشتراك قيد المراجعة !! الرجاء الانتظار للموافقة على الطلب لتتمكن من اضافة المنتجات , يمكنك متابعة الطلب ".$url;
                echo '<div class="alert alert-success m-5 mt-20">'.$text.'</div>';
            }elseif($cc == 2){
                $text = " تم تعليق طلب اشتراكك !! لتتمكن من اضافة المنتجات الرجاء معالجة الطلب ".$url;
                echo '<div class="alert alert-warning m-5 mt-20">'.$text.'</div>';
            }elseif($cc == 3){
                $text = " تم رفض طلب اشتراكك !! لتتمكن من اضافة المنتجات الرجاء تقديم طلب اشتراك جديد".$url;
                echo '<div class="alert alert-danger m-5 mt-20">'.$text.'</div>';
            }elseif($cc == 4){
                $text = " اشتراكك انتهى !! الرجاء تجديد طلب الاشتراك لتتمكن من اضافة المنتجات , يمكنك تجديد الطلب ".$url;
                echo '<div class="alert alert-info m-5 mt-20">'.$text.'</div>';
            }
        }else{
            $cc = 1;
        }
        if ($cc == 1) {?>
        <!-- Page Container -->
        <div class="page-container">
            <div class="container">
                <div class="row mt-10">
                    <div class="p-10 header Addpage-tittle mt-10"><h6> اضافة منتج جديد </h6></div>
                    <?php
                        $addproduct = new ManageProducts;
                        $addproduct->addproducts();
                    ?>
                    <div class="form-add p-10">
                        <form action="index.php?page=Addproducts" method="post" enctype="multipart/form-data">
                            <div class="form-group name">
                                <label for="name">اسم المنتج  : </label>
                                <input type="text" class="form-control" name="name" placeholder=" اسم المنتج " value="<? echo $_POST['name'];?>" required>
                            </div>
                            <div class="form-group">
                                <label for="urlpage">رابط مصدر المنتج  : </label>
                                <input type="text" class="form-control" name="url" placeholder=" الرابط " value="<? echo $_POST['url'];?>" required>
                            </div>
                            <div class="form-group">
                                <label for="price"> السعر التقريبي للمنتج : </label>
                                <input type="text" class="form-control" name="price" placeholder=" سعر المنتج " value="<? echo $_POST['price'];?>" required>
                            </div>
                            <div class="form-group descreption">
                                <label for="descreption">  الوصف والملاحظات : </label>
                                <textarea name="descreption" class="form-control" cols="20" rows="7" placeholder="تقاصيل المنتج ..." required><? echo $_POST['descreption'];?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="rating">  التقييم : </label>
                                <div class="addrating">
                                    <input type="hidden" name="rating" id="rating" required>
                                    <i class='bx bx-star star' style="--i: 0;" data-c="1"></i>
                                    <i class='bx bx-star star' style="--i: 1;" data-c="2"></i>
                                    <i class='bx bx-star star' style="--i: 2;" data-c="3"></i>
                                    <i class='bx bx-star star' style="--i: 3;" data-c="4"></i>
                                    <i class='bx bx-star star' style="--i: 4;" data-c="5"></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="uses"> هل تنصح باستخدام المنتج : </label>
                                <div class="col-xs-12">
                                    <div class="col-xs-6 form-check">
                                        <input class="form-check-input" type="radio" name="uses" value="1" required>
                                        &nbsp;<label class="form-check-label mr-15" for="flexRadioDefault1">
                                            نعم
                                        </label>
                                    </div>
                                    <div class="col-xs-6 form-check">
                                        <input class="form-check-input" type="radio" name="uses" value="0" required>
                                        &nbsp;<label class="form-check-label mr-15" for="flexRadioDefault2">
                                            لا  
                                        </label>
                                    </div>
                                </div>
                            </div><br>
                            <div class="border p-10">
                                <div class="form-group p-10">
                                    <label><b> الصورة الرئيسية للمنتج : </b> / <span class="text-info">اجباري</span></label>
                                    <input type="file" class="form-control" name="main_img" id="bb" required style="display:none;">
                                    <button type="button" class="bb">uploade</button>
                                </div><hr>
                                <label><b>   صور أخرى للمنتج : </b></label>
                                <div class="form-group form-add-screenshot p-10" id="form-add-screenshot">
                                    <input type="file" class="form-control imgpage" name="allimg_product[]" multiple>
                                    <span> اختيار الصور <i class="fa fa-upload"></i></span><br> 
                                </div>
                            </div>

                            <div class="form-group mt-10">
                                <button type="submit" class="btn btn-info" name="addproducts"> اضافة <i class="fa fa-plus"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Container -->
    <?}?>
</main>
<!-- –––––––––––––––[ END PAGE CONTENT ]––––––––––––––– -->
