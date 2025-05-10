<?
if(!isset($_SESSION['id'])){
    header("location:index.php?page=login");
}
require_once './functions/sites_func.php';
$role = new role;
?>
<!-- –––––––––––––––[ PAGE CONTENT ]––––––––––––––– -->
<div id="mainContent" class="main-content">
    <div class="headers mt-20 m-5">
        <ul class="nav nav-tabs justify-content-center" role="tablist">
            <li class="nav-item active">
                <a class="nav-link" href="index.php?page=Allsites">
                    <i class="now-ui-icons objects_umbrella-13 fa fa-bank"></i>  قائمة المتاجر
                </a>
            </li>
            <?
            if ($role->r('role') == 2) {?>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=Addsites">
                        <i class="now-ui-icons objects_umbrella-13 fa fa-plus-circle"></i> اضافة متجر جديد 
                    </a>
                </li>
            <?}else{?>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=Createbrokers">
                        <i class="now-ui-icons objects_umbrella-13 fa fa-exchange"></i> تعيين نفسي كوسيط 
                    </a>
                </li>
            <?}?>
        </ul>
    </div>
    <!-- Page Container -->
    <div class="page-container">
        <div class="container">
            <div class="row" id="myUL">
                <?
                    $getsites = new Sites;
                    $getsites->getsites();
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