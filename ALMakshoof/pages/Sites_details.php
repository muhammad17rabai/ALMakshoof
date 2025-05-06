<?
if(!isset($_SESSION['id'])){
    header("location:index.php?page=login");
}
require_once './functions/sites_func.php';
$site_id = validation($_GET['site_id']);
?>
<!-- –––––––––––––––[ PAGE CONTENT ]––––––––––––––– -->
<div id="mainContent" class="main-content">
    <div class="headers mt-20 m-5">
        <ul class="nav nav-tabs justify-content-center" role="tablist">
            <li class="nav-item active">
                <a class="nav-link" href="index.php?page=Sites_details&&site_id=<?php echo $site_id;?>">
                    <i class="now-ui-icons objects_umbrella-13 fa fa-bank"></i> تفاصيل المتجر
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=Brokersites&&site_id=<?php echo $site_id;?>">
                    <i class="now-ui-icons objects_umbrella-13 fa fa-group"></i> وسطاء المتجر 
                </a>
            </li>
        </ul>
    </div>
</div>
<div id="mainContent" class="main-content">
    <!-- Page Container -->
    <div class="page-container">
        <div class="container">
            <div class="row" id="myUL">
                <?
                    $sites_details = new Sites;
                    $sites_details->editsites();
                    $sites_details->sites_details();
                ?>
            </div>
        </div>
    </div>
    <!-- End Page Container -->
</div>
<!-- –––––––––––––––[ END PAGE CONTENT ]––––––––––––––– -->