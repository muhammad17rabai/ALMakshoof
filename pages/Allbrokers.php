<?
if(!isset($_SESSION['id'])){
    header("location:index.php?page=login");
}
require_once './functions/brokers_func.php';

?>
<!-- –––––––––––––––[ PAGE CONTENT ]––––––––––––––– -->
<div id="mainContent" class="main-content">
    <!-- Page Container -->
    <div class="page-container">
        <div class="container">
            <div class="row" id="myUL">
                <?php
                    $getbrokers = new Brokers;
                    $getbrokers->getbrokers_list();
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