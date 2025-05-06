<?
if(!isset($_SESSION['id'])){
    header("location:index.php?page=login");
}
require_once './functions/products_func.php';
?>
<!-- –––––––––––––––[ PAGE CONTENT ]––––––––––––––– -->
<main id="mainContent" class="main-content">
    <div class="headers-detaile mt-20">
        <ul class="nav nav-tabs justify-content-center" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" href="index.php?page=Allproducts">
                    <i class="now-ui-icons objects_umbrella-13"></i> تفاصيل المنتج
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=Allrating&&product_id=<? echo validation($_GET['product_id'])?>&&type=product">
                    <i class="now-ui-icons objects_umbrella-13"></i> التقييمات والأراء
                </a>
            </li>
        </ul>
    </div>
	<?
        $product_details = new ManageProducts;
        $product_details->products_details();
    ?>
</main>