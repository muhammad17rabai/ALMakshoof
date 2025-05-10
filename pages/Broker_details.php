<?
$user_id = validation($_GET['user_id']);
if(!isset($_SESSION['id'])){
    header("location:index.php?page=login");
}
require_once './functions/brokers_func.php';

?>
<!-- –––––––––––––––[ PAGE CONTENT ]––––––––––––––– -->
<div class="p-5 m-10">
    <h5 class="h-title t-uppercase"> تفاصيل الوسيط </h5>
</div>
<div id="mainContent" class="main-content">
    <!-- Page Container -->
    <div class="page-container">
        <div class="container">
            <div class="row" id="myUL">
                <?
                    $broker_details = new Brokers;
                    $broker_details->form_edit_broker();
                    $broker_details->broker_details();
                ?>
            </div>
        </div>
    </div>
    <!-- End Page Container -->
</div>
<!-- –––––––––––––––[ END PAGE CONTENT ]––––––––––––––– -->