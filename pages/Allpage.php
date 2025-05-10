<?
if(!isset($_SESSION['id'])){
    header("location:index.php?page=login");
}
require_once './functions/manage_page_func.php';
$category = validation($_GET['category']);
$type = validation($_GET['type']);
$check_sub = new info;
$cc = $check_sub->check_subscribe();
$role = new role;
if ($category == null) {
    $st = 'اختار الفئة';
}elseif($category == 'all'){
    $st = 'الكل';
}else{
    $st = category($category);
}
if ($type == null) {
    $tp = 'اختار النوع';
}elseif($type == 'all'){
    $tp = 'الكل';
}else{
    switch ($type) {
        case 'facebook':
            $tp = 'فيسبوك';
            break;
        case 'instagram':
            $tp = 'انستقرام';
            break;
        case 'store':
            $tp = 'متجر الكتروني';
            break;
    }
}
?>
<!-- –––––––––––––––[ PAGE CONTENT ]––––––––––––––– -->
<div id="mainContent" class="main-content">
    <div class="row pr-10 pl-10">
        <? 
        if ($cc == 1 || $role->r('role') == 2) {?>
            <div class="col-xs-12 mt-15 d-flex justify-content-between">
                <select id="type" class="form-control bg-white col-xs-5 mr-1" data-category="<? echo $category?>">
                    <option value="<? echo $_GET['type'];?>"><? echo $tp;?></option>
                    <option value="all"> الكل </option>
                    <option value="facebook"> فيسبوك </option>
                    <option value="instagram"> انستقرام </option>
                    <option value="store"> متجر الكتروني </option>
                </select>
                <select id="category" class="form-control bg-white col-xs-7 mr-1" data-type="<? echo $type;?>">
                    <option value="<? echo $_GET['category'];?>"><? echo $st;?><span class="count_category"></span></option>
                    <option value="all"> الكل </option>
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
        <?}?>
    </div>
    <div class="headers mt-10 m-5">
        <ul class="nav nav-tabs justify-content-center" role="tablist">
            <li class="nav-item active">
                <a class="nav-link" href="index.php?page=Allpage">
                    <i class="now-ui-icons objects_umbrella-13"></i>  قائمة الصفحات
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=Addpage">
                    <i class="now-ui-icons objects_umbrella-13"></i> اضافة صفحة جديدة +
                </a>
            </li>
        </ul>
    </div>
    <!-- Page Container -->
    <div class="page-container">
        <div class="container">
            <div class="row" id="myUL">
                <?php
                    $getpage = new ManagePage;
                    $getpage->getallpage();
                ?>
            </div>
            <div id="result-search">
                <? box_alert('secondary','لا توجد نتائج')?>
            </div>
        </div>
    </div>
    <!-- End Page Container -->
</div>
<!-- –––––––––––––––[ END PAGE CONTENT ]––––––––––––––– -->