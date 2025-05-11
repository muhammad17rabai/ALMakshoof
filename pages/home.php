<?
if(!isset($_SESSION['id'])){
    header("location:index.php?page=login");
}
class home extends DB
{
    function countpages()
    {
        $rows = $this->connect()->query("SELECT * FROM pages WHERE status = 1 && active = 1")->num_rows;
        return $rows;
    }
    function countproducts()
    {
        $rows = $this->connect()->query("SELECT * FROM products WHERE status = 1 && active = 1")->num_rows;
        return $rows;
    }
    function countsites()
    {
        $rows = $this->connect()->query("SELECT * FROM sites WHERE active = 1")->num_rows;
        return $rows;
    }
    function countbrokers()
    {
        $role = new role;
        if ($role->r('role') == 1) {
            $rows = $this->connect()->query("SELECT * FROM brokers WHERE status = 1 && active = 1")->num_rows;
        }else{
            $rows = $this->connect()->query("SELECT * FROM brokers")->num_rows;
        }
        return $rows;
    }
    function countmembers()
    {
        $rows = $this->connect()->query("SELECT * FROM users WHERE role = 'member'")->num_rows;
        return $rows;
    }
    function countmembers_sub()
    {
        $rows = $this->connect()->query("SELECT * FROM subscribe WHERE status = 1 && active = 1")->num_rows;
        return $rows;
    }
}
$count = new home;
?>
<div class="container">
    <div class="row p-10">
        <div class="col-xs-6">
            <a href="index.php?page=Allpage" class="text-black">
                <div class="pages card-home text-center">
                    <h4><i class="fa fa-pie-chart text-info"></i></h4>
                    <h6><b> الصفحات </b></h6>
                    <span><? echo $count->countpages(); ?></span>&nbsp;
                    <i class="fa fa-arrow-left text-info"></i>
                </div>
            </a>
        </div>
        <div class="col-xs-6">
            <a href="index.php?page=Allproducts" class="text-black">
                <div class="customers card-home text-center">
                    <h4><i class="fa fa-product-hunt text-warning"></i></h4>
                    <h6><b> المنتجات </b></h6>
                    <span><? echo $count->countproducts(); ?></span>&nbsp;
                    <i class="fa fa-arrow-left text-info"></i>
                </div>
            </a>
        </div>
        <div class="col-xs-6">
            <a href="index.php?page=Allsites" class="text-black">
                <div class="pages card-home text-center">
                    <h4><i class="fa fa-globe text-danger"></i></h4>
                    <h6><b> مواقع التجارة العالمية </b></h6>
                    <span><? echo $count->countsites(); ?></span>&nbsp;
                    <i class="fa fa-arrow-left text-info"></i>
                </div>
            </a>
        </div>
        <div class="col-xs-6">
            <a href="index.php?page=Allbrokers" class="text-black">
                <div class="customers card-home text-center">
                    <h4><i class="fa fa-group text-info"></i></h4>
                    <h6><b> الوسطاء </b></h6>
                    <span><? echo $count->countbrokers(); ?></span>&nbsp;
                    <i class="fa fa-arrow-left text-info"></i>
                </div>
            </a>
        </div>
        <div class="col-xs-6">
            <a href="index.php?page=Max_rating&&type=pages" class="text-black">
                <div class="pages card-home text-center">
                    <h4><i class="fa fa-line-chart text-info"></i></h4>
                    <h6><b> أعلى الصفحات تقييما </b></h6>
                    <i class="fa fa-arrow-left text-info"></i>
                </div>
            </a>
        </div>
        <div class="col-xs-6">
            <a href="index.php?page=Max_rating&&type=products" class="text-black">
                <div class="customers card-home text-center">
                    <h4><i class="fa fa-bar-chart text-warning"></i></h4>
                    <h6><b> أعلى المنتجات تقييما </b></h6>
                    <i class="fa fa-arrow-left text-info"></i>
                </div>
            </a>
        </div>
        <?php
        $role = new role;
        if ($role->r('role') == 2) {?>
        <div class="col-xs-6">
            <a href="index.php?page=Allmembers" class="text-black">
                <div class="customers card-home text-center">
                    <h4><i class="fa fa-group text-secondary"></i></h4>
                    <h6><b> الأعضاء </b></h6>
                    <span><? echo $count->countmembers(); ?></span>&nbsp;
                    <i class="fa fa-arrow-left text-info"></i>
                </div>
            </a>
        </div>
        <div class="col-xs-6">
            <a href="index.php?page=member_sub" class="text-black">
                <div class="customers card-home text-center">
                    <h4><i class="fa fa-group text-success"></i></h4>
                    <h6><b> الأعضاء المشتركون</b></h6>
                    <span><? echo $count->countmembers_sub(); ?></span>&nbsp;
                    <i class="fa fa-arrow-left text-info"></i>
                </div>
            </a>
        </div>
        <?}?>
        <div class="col-xs-6">
            <a href="index.php?page=Allbrokers" class="text-black">
                <div class="customers card-home text-center">
                    <h4><i class="fa fa-group text-info"></i></h4>
                    <h6><b> الوسطاء </b></h6>
                    <span><? echo $count->countbrokers(); ?></span>&nbsp;
                    <i class="fa fa-arrow-left text-info"></i>
                </div>
            </a>
        </div>
    </div>
</div><br><br>
