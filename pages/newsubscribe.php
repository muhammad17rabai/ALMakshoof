<?
    if(!isset($_SESSION['id'])){
        header("location:index.php?page=login");
    }
    require_once './functions/subscribe_func.php';
?>
<div class="headers mt-20">
    <ul class="nav nav-tabs justify-content-center" role="tablist">
        <li class="nav-item">
            <a class="nav-link" href="index.php?page=subscribe">
                <i class="now-ui-icons objects_umbrella-13"></i> كل الطلبات
            </a>
        </li>
        <li class="nav-item active">
            <a class="nav-link" href="index.php?page=newsubscribe">
                <i class="now-ui-icons objects_umbrella-13"></i> طلب اشتراك جديد
            </a>
        </li>
    </ul>
</div>
<div class="container">
    <div class="row d-flex">
    <?
        $newsub = new Newsubscribe;
        $newsub->addnew_sub();
        $newsub->edit_sub();
        $newsub->getorder_subscribe();
        $newsub->form_sub();
    ?>
    </div>
</div>