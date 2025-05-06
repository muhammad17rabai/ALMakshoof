<?
if(!isset($_SESSION['id'])){
    header("location:index.php?page=login");
}
require_once './functions/manage_page_func.php';
?>
<!-- –––––––––––––––[ PAGE CONTENT ]––––––––––––––– -->
<main id="mainContent" class="main-content">
    <div class="headers-detaile mt-20">
        <ul class="nav nav-tabs justify-content-center" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" href="index.php?page=Allpage">
                    <i class="now-ui-icons objects_umbrella-13"></i> تفاصيل الصفحة
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=Allrating&&page_id=<? echo validation($_GET['page_id'])?>&&type=page">
                    <i class="now-ui-icons objects_umbrella-13"></i> التقييمات والأراء
                </a>
            </li>
        </ul>
    </div>
	<?
        $page_details = new ManagePage;
        $page_details->page_details();
    ?>
</main>