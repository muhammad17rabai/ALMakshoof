<?
if(!isset($_SESSION['id'])){
    header("location:index.php?page=login");
}
require_once './functions/products_func.php';

?>
<!-- –––––––––––––––[ PAGE CONTENT ]––––––––––––––– -->
<div id="mainContent" class="main-content">
    <div class="headers mt-20 m-5">
        <ul class="nav nav-tabs justify-content-center" role="tablist">
            <li class="nav-item active">
                <a class="nav-link" href="index.php?page=Allproducts">
                    <i class="now-ui-icons objects_umbrella-13"></i>  قائمة المنتجات
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=Addproducts">
                    <i class="now-ui-icons objects_umbrella-13"></i> اضافة منتج جديد +
                </a>
            </li>
        </ul>
    </div>
    <!-- Page Container -->
    <div class="page-container">
        <div class="container">
            <div class="row" id="myUL">
                <?php
                    $getproduct = new ManageProducts;
                    $getproduct->getallproducts();
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