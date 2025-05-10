<?
if(!isset($_SESSION['id'])){
    header("location:index.php?page=login");
}
require_once './functions/brokers_func.php';

?>
<!-- –––––––––––––––[ PAGE CONTENT ]––––––––––––––– -->
<div id="mainContent" class="main-content">
    <div class="headers mt-20 m-5">
        <ul class="nav nav-tabs justify-content-center" role="tablist">
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=Allsites">
                    <i class="now-ui-icons objects_umbrella-13 fa fa-bank"></i>  قائمة المتاجر
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="index.php?page=Createbrokers">
                    <i class="now-ui-icons objects_umbrella-13 fa fa-exchange"></i> تعيين نفسي كوسيط 
                </a>
            </li>
        </ul>
    </div>
    <!-- Page Container -->
    <div class="page-container">
        <div class="container">
            <div class="row" id="myUL">
                <!----------------------------------------- Modal delete order --------------------------------------->
                <!-- Modal details -->
                <?
                    $addbroker = new Brokers;
                    $addbroker->form_edit_broker();
                    $addbroker->form_brokers();
                    $addbroker->get_brokers();
                ?>
                <!-------------------------------------- End reject order modal ---------------------------------------------->
                
            </div>
        </div>
    </div>
</div>