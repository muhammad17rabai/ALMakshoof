<?
if(!isset($_SESSION['id'])){
    header("location:index.php?page=login");
}
require_once './functions/manage_page_func.php';
$check_sub = new info;
$cc = $check_sub->check_subscribe();
?>
<!-- –––––––––––––––[ PAGE CONTENT ]––––––––––––––– -->
<main id="mainContent" class="main-content">
    <div class="headers mt-20">
        <ul class="nav nav-tabs justify-content-center" role="tablist">
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=Allpage">
                    <i class="now-ui-icons objects_umbrella-13"></i>  قائمة الصفحات
                </a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="index.php?page=Addpage">
                    <i class="now-ui-icons objects_umbrella-13"></i> اضافة صفحة جديدة +
                </a>
            </li>
        </ul>
    </div>
    <?
        if ($role->r('role') == 1) {
            $url = '<a href="index.php?page=subscribe"> من هنا </a>';
            if ($cc == 'not_exit') {
                $text = "أنت غير مشترك !! لتتمكن من اضافة الصفحات الرجاء الاشتراك".$url;
                echo '<div class="alert alert-danger m-5 mt-20">'.$text.'</div>';
            }elseif($cc == 0){
                $text = " طلبك الاشتراك قيد المراجعة !! الرجاء الانتظار للموافقة على الطلب لتتمكن من اضافة الصفحات , يمكنك متابعة الطلب ".$url;
                echo '<div class="alert alert-success m-5 mt-20">'.$text.'</div>';
            }elseif($cc == 2){
                $text = " تم تعليق طلب اشتراكك !! لتتمكن من اضافة الصفحات الرجاء معالجة الطلب ".$url;
                echo '<div class="alert alert-warning m-5 mt-20">'.$text.'</div>';
            }elseif($cc == 3){
                $text = " تم رفض طلب اشتراكك !! لتتمكن من اضافة الصفحات الرجاء تقديم طلب اشتراك جديد".$url;
                echo '<div class="alert alert-danger m-5 mt-20">'.$text.'</div>';
            }elseif($cc == 4){
                $text = " اشتراكك انتهى !! الرجاء تجديد طلب الاشتراك لتتمكن من اضافة الصفحات , يمكنك تجديد الطلب ".$url;
                echo '<div class="alert alert-info m-5 mt-20">'.$text.'</div>';
            }
        }else{
            $cc = 1;
        }
        if ($cc == 1) {?>
        <!-- Page Container -->
        <div class="page-container">
            <div class="container">
                <div class="row mt-5">
                    <div class="p-10 header Addpage-tittle mt-15"><h6> اضافة صفحة جديدة </h6></div>
                    <div class="col-xs-12">
                        <?php
                            $addpage = new ManagePage;
                            $addpage->addpage();
                        ?>
                    </div>
                    <div class="form-add p-10">
                        <form action="index.php?page=Addpage" method="post" enctype="multipart/form-data">
                            <div class="form-group pagename">
                                <label for="name">اسم الصفحة  : </label>
                                <input type="text" class="form-control" name="pagename" placeholder=" اسم الصفحة " value="<? echo $_POST['pagename'];?>" required>
                            </div>
                            <div class="form-group">
                                <label for="urlpage"> رابط الصفحة : </label>
                                <input type="text" class="form-control" name="pageurl" placeholder=" الرابط " value="<? echo $_POST['pageurl'];?>" required>
                            </div>
                            <div class="form-group">
                                <label for="type">  النوع : </label>
                                <select name="typepage" class="form-control" required>
                                    <option value="<? echo $_POST['typepage'];?>"> اختار النوع </option>
                                    <option value="icons/face.png"> صفحة فيسبوك </option>
                                    <option value="icons/Instagram.jpg"> صفحة انستقرام </option>
                                    <option value="icons/store.jpg"> متجر الكتروني </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="category">  الفئة : </label>
                                <select name="category" class="form-control" required>
                                    <option value="<? echo $_POST['category'];?>"> اختار الفئة </option>
                                    <option value="home_tools">أدوات منزلية</option>
                                    <option value="clothes_shoes">ملابس وأحذية</option>
                                    <option value="devices_electric">أجهزة كهربائية</option>
                                    <option value="computer_mobile">حواسيب وهواتف</option>
                                    <option value="elecetronics">الكترونيات عامة</option>
                                    <option value="prefumes_accessories">عطور واكسسوارات</option>
                                    <option value="health_beauty">صحة وجمال</option>
                                    <option value="bags_prose">شنط ونثريات</option>
                                    <option value="books">كتب ومطبوعات</option>
                                    <option value="cleane">مواد تنظيف</option>
                                    <option value="car_accessories">كماليات سيارات</option>
                                    <option value="medicals">أدوات ومشدات طبية</option>
                                    <option value="others">غير ذلك</option>
                                </select>
                            </div>
                            <div class="form-group descreption">
                                <label for="descreption">  الوصف والملاحظات : </label>
                                <textarea name="descpage" class="form-control" cols="20" rows="7" placeholder="تقاصيل المشكلة ..." required><? echo $_POST['descpage'];?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <div class="alert alert-warning">
                                    <span>التطبيق مخصص للصفحات ذات السمعة الجيدة لذلك يجب أن يكون تقييم الصفحة أكبر أو يساوي 3</span>
                                </div>
                                <label for="rating">  التقييم : </label>
                                <div class="addrating">
                                    <input type="hidden" name="rating" id="rating" required>
                                    <i class='bx bxs-star star active-star' style="--i: 0;" data-c="3"></i>
                                    <i class='bx bxs-star star active-star' style="--i: 1;" data-c="3"></i>
                                    <i class='bx bxs-star star active-star' style="--i: 2;" data-c="3"></i>
                                    <i class='bx bx-star star' style="--i: 3;" data-c="4"></i>
                                    <i class='bx bx-star star' style="--i: 4;" data-c="5"></i>
                                </div>
                            </div>
                            <label><b>  الصور والدلائل : </b> / <span class="text-info">اجباري</span></label>
                            <div class="col-xs-12 form-group form-add-screenshot p-10" id="form-add-screenshot">
                                <input type="file" class="form-control imgpage" name="img_name[]" required multiple>
                                <span> اختيار الصور <i class="fa fa-upload"></i></span><br> 
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-info" name="addpage" id="addpage"> اضافة <i class="fa fa-plus"></i></button>
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