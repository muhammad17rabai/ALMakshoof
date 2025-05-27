<?php
    ob_start();
    session_start();
    require_once 'functions/connect.php';
    require_once 'functions/functions.php';
    require_once 'functions/notifications_func.php';
    require_once 'functions/messages_func.php';
    
    $page = htmlentities($_GET['page']);
    $pages = scandir('pages');
    if (!empty($page) && in_array($_GET['page']. ".php",$pages)){

	    $content = 'pages/'. $_GET['page'].".php";
    }
    if($page !='signup' && $page !='login' && $page !='logout' && $page !='home' && $page !='Allmembers' && $page !='Addmember'
    && $page !='Addpage' && $page !='Allpage'  && $page != 'page_details' && $page !='notic_details' && $page !='Edit' && $page !='Allrating' && $page !='Addrating'
    && $page !='orders' && $page !='Myprofile' && $page !='password' && $page !='Myposts' && $page !='Myfavorate' && $page !='Allproducts' && $page !='Addproducts'
    && $page != 'Products_details' && $page != 'subscribe' && $page != 'newsubscribe' && $page != 'Allsubscribe' && $page != 'Allsites'
    && $page != 'Addsites' && $page != 'Sites_details' && $page != 'Createbrokers' && $page != 'Brokersites' && $page != 'Broker_details' 
    && $page !='Allbrokers' && $page != 'Member_details' && $page != 'member_sub' && $page != 'Contact_us' && $page != 'Messages' && $page != 'Chat'
    && $page != 'Recover_password' && $page != 'Max_rating' && $page != 'About_us' && $page != 'Polices_terms'){
        
        header("location:index.php?page=login");
    }
    class info extends DB
    {
        function r($i)
        {
            $sql = $this->connect()->query("SELECT username,avatar,role FROM users WHERE id = '{$_SESSION['id']}'")->fetch_assoc();
            if ($sql['role'] == 'admin') {
                $r = 2;
            }elseif($sql['role'] == 'member'){
                $r = 1;
            }
            $u = $sql['username'];
            $v = $sql['avatar'];
            $data = array("name"=>$u , "avatar"=>$v , "role"=>$r);
            return $data[$i];
        }
        function check_online()
        {
            $id = validation($_SESSION['id']);
            $get_id = $this->connect()->query("SELECT id,date_updated,online FROM users");

            while ($fetch = $get_id->fetch_assoc()) {
                $diff = time() - (int)$fetch['date_updated'];
                if ($diff >= 60) {
                    $minuts = floor($diff/60);   
                }
                if ($fetch['online'] == 1) {
                    $date_updated = time();
                }else{
                    $date_updated = $fetch['date_updated'];
                }
                if ($id == $fetch['id']) {
                    $date_updated = time();
                    $this->connect()->query("UPDATE users SET date_updated='$date_updated',online=1 WHERE id = '$id'");
                }elseif($minuts > 2){
                    $this->connect()->query("UPDATE users SET online=0 WHERE id = '{$fetch['id']}'");
                }
                $diff = 0; $minuts=0;
            };
        }
        function check_device_online()
        {
            $id = validation($_SESSION['id']);
            $finger = validation($_SESSION['finger']);
            $get_d = $this->connect()->query("SELECT * FROM devices");

            while ($fetch = $get_d->fetch_assoc()) {
                $diff_d = time() - (int)$fetch['date_updated'];
                if ($diff_d >= 60) {
                    $minuts_d = floor($diff_d/60); 
                }
                if ($fetch['online_d'] == 1) {
                    $date_updated = time();
                }else{
                    $date_updated = $fetch['date_updated'];
                }
                if ($finger == $fetch['finger']) {
                    $date_updated = time();
                    $this->connect()->query("UPDATE devices SET date_updated='$date_updated',online_d=1 WHERE user_id = '$id' && finger='$finger' && active=1");
                }elseif($minuts_d > 2){
                    $this->connect()->query("UPDATE devices SET online_d=0 WHERE user_id = '{$fetch['user_id']}' && finger ='{$fetch['finger']}' && active=1");
                }
                $diff_d = 0; $minuts_d=0;
            };
            
        }
        function check_subscribe()
        {
            $user_id = validation($_SESSION['id']);
            $sql = $this->connect()->query("SELECT * FROM subscribe WHERE user_id = '$user_id'");
            if ($sql->num_rows > 0) {
                $status = $sql->fetch_assoc()['status'];
            }else{
                $status = 'not_exit';
            }
            return $status;
        }
        function get_subscribes($check , $role)
        {
            $cc = $check->check_subscribe();
            if ($role->r('role') == 1) {
                $url = '<a href="index.php?page=newsubscribe"> من هنا </a>';
                if ($cc == 'not_exit') {
                    $text = "أنت غير مشترك !! لتتمكن من استخدام جميع الميزات الرجاء الاشتراك".$url;
                    echo '<div class="alert alert-danger m-5">'.$text.'</div>';
                }elseif($cc == 0){
                    $text = " طلبك الاشتراك قيد المراجعة !! الرجاء الانتظار للموافقة على الطلب لتتمكن من استخدام جميع الميزات , يمكنك متابعة الطلب ".$url;
                    echo '<div class="alert alert-success m-5">'.$text.'</div>';
                }elseif($cc == 2){
                    $text = " تم تعليق طلب اشتراكك !! لتتمكن من استخدام جميع الميزات الرجاء معالجة الطلب ".$url;
                    echo '<div class="alert alert-warning m-5">'.$text.'</div>';
                }elseif($cc == 3){
                    $text = " تم رفض طلب اشتراكك !! لتتمكن من استخدام جميع الميزات الرجاء تقديم طلب اشتراك جديد".$url;
                    echo '<div class="alert alert-danger m-5">'.$text.'</div>';
                }elseif($cc == 4){
                    $text = " اشتراكك انتهى !! الرجاء تجديد طلب الاشتراك لتتمكن من استخدام جميع الميزات , يمكنك تجديد الطلب ".$url;
                    echo '<div class="alert alert-info m-5">'.$text.'</div>';
                }
                if ($cc == 1) {
                    $user_id = validation($_SESSION['id']);
                    $sql = $this->connect()->query("SELECT * FROM subscribe WHERE user_id = '$user_id'");
                    $date = date('Y-m-d G:i:s');
                    $end = $sql->fetch_assoc()['end'];
                    $end_date = date('Y-m-d G:i:s',strtotime($end));
                    if ($date == $end_date || $end_date < $date) {
                        $this->connect()->query("UPDATE subscribe SET status = 4 WHERE user_id = '$user_id'");
                    }else{?>
                        <div class="col-xs-12 p-5 remain_mainsub">
                            <input type="hidden" id="end_date" value="<? echo $end_date;?>">
                            <label>المتبقي للاشتراك : &nbsp;<label class="p-10" id="remain_mainsub"></label></label>
                        </div>
                   <?}
                }
            }
        }
    }
    $role = new role;
    $check = new info;
    $check->check_online();
    $check->check_device_online();
    $messages = new Messages;
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl" class="no-js">
<head>
    <!-- ––––––––––––––––––––––––––––––––––––––––– -->
    <!-- META TAGS                                 -->
    <!-- ––––––––––––––––––––––––––––––––––––––––– -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ALMakshoof</title>
    <meta name="description" content="brief description here">
    <meta name="keywords" content="insert, keywords, here">
    <meta name="robots" content="index, follow">
    <meta name="author" content="CODASTROID">
    <!-- ––––––––––––––––––––––––––––––––––––––––– -->
    <!-- GOOGLE FONTS                              -->
    <!-- ––––––––––––––––––––––––––––––––––––––––– -->
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=El+Messiri:700" rel="stylesheet">
    <!-- ––––––––––––––––––––––––––––––––––––––––– -->
    <!-- Include CSS Filess                        -->
    <!-- ––––––––––––––––––––––––––––––––––––––––– -->
    <!-- Bootstrap -->
    <link href="css/bootstrap4.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-rtl.css" rel="stylesheet">
    <!--<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">-->
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   <!-- <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>-->

    <!-- Template Stylesheet -->
    <link href="css/base.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

</head>
<body id="body" class="wide-layout text-right">
    <div class="refresh-indicator" id="refresh-indicator">
        <img src="icons/loading.gif" width="180">
    </div>
    <div id="mySidenav_p" class="sidenav">
        <div class="img-leftside text-center">
            <img src="<? $info = new info; echo $info->r("avatar");?>" class="img-circle" width="100">
            <h5><span> <? $info = new info; echo $info->r("name");?> </span></h5>
            <span> مرحبا بك </span>
        </div><hr>
        <div class="text-right">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav_profile()">&times;</a>
            <a href="index.php?page=Myprofile">الملف الشخصي<span class="icon fa fa-user"></span><hr></a>
            <a href="index.php?page=Myposts&&type=pages"> منشوراتي <span class="icon fa fa-edit"></span><hr></a>
            <a href="index.php?page=Myfavorate"> المفضلة <span class="icon fa fa-heart"></span><hr></a>
            <?
            if ($role->r('role') == 1){?>
                <a href="index.php?page=subscribe">الاشتراك<span class="icon fa fa-money"></span><hr></a>
            <? }elseif($role->r('role') == 2){ ?>
                <a href="index.php?page=Allsubscribe">طلبات الاشتراك<span class="icon fa fa-money"></span><hr></a>
                <!--<a href="#">التقارير<span class="icon fa fa-line-chart"></span><hr></a>-->
            <?}?>
            <a href="index.php?page=logout">تسجيل الخروج<span class="icon fa fa-sign-out"></span><hr></a>
        </div>
    </div>

    <div id="mySidenav_n" class="sidenav">
        <div class="text-right">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav_notification()">&times;</a>
            <div class="p-5 m-5 notic_body">
                <h6 class="h-title t-uppercase"> الاشعارات &nbsp;<span class="icon fa fa-bell"></span></h6>
                <?
                    $notification = new Notifications;
                    $notification->getnotification();
                ?>
            </div>
        </div>
    </div>
    <!-- ––––––––––––––––––––––––––––––––––––––––– -->
    <!-- PRELOADER                                 -->
    <!-- ––––––––––––––––––––––––––––––––––––––––– -->
    <div id="preloader" class="preloader">
        <div class="loader-cube">
            <div class="loader-cube__item1 loader-cube__item"></div>
            <div class="loader-cube__item2 loader-cube__item"></div>
            <div class="loader-cube__item4 loader-cube__item"></div>
            <div class="loader-cube__item3 loader-cube__item"></div>
        </div>
    </div>
    <?
    if (isset($_SESSION['id'])) {?>
        <div>
            <div class="col-xs-3 mt-20">
                <a onclick="history.go(-1)">
                    <i class="fa fa-mail-forward text-secondary" style="font-size:25px"></i>
                </a>
            </div>
            <div class="col-xs-6">
                <img src="icons/logo2.png" height="200">
            </div>
            <div class="col-xs-3 mt-20">
                <a onclick="history.go(+1)" style="float: left;">
                    <i class="fa fa-mail-reply text-secondary" style="font-size:25px"></i>
                </a>
            </div>
        </div>
    <?}?>
    <!--
    <div class="brand col-md-3 t-xs-center t-md-right valign-middle">
        <img src="icons/logo.jpg" width="100">
    </div>
    -->
    <!-- ––––––––––––––––––––––––––––––––––––––––– -->
    <!-- WRAPPER                                   -->
    <!-- ––––––––––––––––––––––––––––––––––––––––– -->
    <div id="pageWrapper" class="page-wrapper">
        <!-- –––––––––––––––[ HEADER ]––––––––––––––– -->
        <header id="mainHeader" class="main-header">
            <!-- Top Bar -->
            <?
            if (!isset($_SESSION['id'])) {?>
                <div class="top-bar bg-gray">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-10 col-md-8">
                                <ul class="nav-top nav-top-right list-inline t-xs-center t-md-left">
                                    <li><a href="index.php?page=login"><i class="fa fa-lock lg text-info"></i>تسجيل الدخول</a>
                                    </li>
                                    <li><a href="index.php?page=signup"><i class="fa fa-user lg text-info"></i>حساب جديد</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?}/*else{
                echo '<div class="text-center">
                        <img src="./icons/logos.jpg" width="100">logo
                    </div>';
            }*/?>
            <!-- End Top Bar -->
            <!-- Header Header -->
            <div class="header-header bg-white">
                <div class="container">
                    <div class="row row-rl-0 row-tb-20 row-md-cell">
                    <?
                    if (isset($_SESSION['id']) && $page != "home" && $page != "Myprofile" && $page != "Myfavorate"){?>
                        <div class="header-search col-md-9 mt-20">
                            <div class="row row-tb-10 ">
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control input-lg search-input" id="search-input" placeholder="ادخل كلمة أو رابط للبحث..." required="required" onkeyup="search_item();">
                                        <div class="input-group-btn">
                                            <div class="input-group">
                                                <div class="input-group-btn">
                                                    <button type="button" class="btn btn-lg btn-search btn-block btn-secondary">
                                                        <i class="fa fa-search font-16"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?}?>
                    </div>
                </div>
            </div>
            <!-- End Header Header -->
            <!-- Header Menu -->
            <div class="header-menu bg-blue" style="border-radius: 7px;">
                <div class="container">
                    <nav class="nav-bar">
                        <div class="nav-header">
                            <span class="nav-toggle" data-toggle="#header-navbar">
                                <i></i>
                                <i></i>
                                <i></i>
                            </span>
                        </div>
                        <div id="header-navbar" class="nav-collapse">
                            <ul class="nav-menu">
                            <?
                            if (isset($_SESSION['id'])) {?>
                                <li class="active">
                                    <a href="index.php?page=home">الرئيسية</a>
                                </li>
                                <?
                                    if ($role->r('role') === 2) {?>
                                         <li>
                                            <a href="index.php?page=Allmembers">المستخدمين</a>
                                        </li>
                                    <?}
                                ?>
                                <li>
                                    <a href="index.php?page=Allpage">الصفحات</a>
                                </li>
                                <li>
                                    <a href="index.php?page=Allproducts">المنتجات</a>
                                </li>
                                <li>
                                    <a href="index.php?page=orders&&type=pages&&st=4">الطلبات</a>
                                </li>
                                <li>
                                    <a href="index.php?page=Contact_us">اتصل بنا</a>
                                </li>
                            <? }?>
                                <li>
                                    <a href="index.php?page=Polices_terms"> الشروط والأحكام </a>
                                </li>
                                <li>
                                    <a href="index.php?page=About_us"> من نحن</a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
            <!-- End Header Menu -->
        </header>
        <!-- –––––––––––––––[ HEADER ]––––––––––––––– -->
        <!-- –––––––––––––––[ PAGE CONTENT ]––––––––––––––– -->
        <div class="container">
            <div class="row">
                <div class="mt-20 mr-10 ml-10 radius">
                    <? 
                        if ($_GET['page'] == 'home') {
                           $info->get_subscribes($check,$role);
                        }
                    ?>
                </div>
                <?php 
                        if($content != null){
                        include ($content);
                    }
                ?>
            </div>
        </div><br><br>
        <!-- –––––––––––––––[ END PAGE CONTENT ]––––––––––––––– -->
        <!-- –––––––––––––––[ FOOTER ]––––––––––––––– -->
        <?
        if (isset($_SESSION['id'])) {?>
        <div class="container-fluied footer">
            <div class="row">
                <div class="col-xs-3">
                    <div class="header-wishlist text-center">
                        <a href="index.php?page=home" class="dropdown-toggle profile">
                        <span class="icon fa fa-home text-white"></span>
                        <!--<span class="indicator"><i class="fa fa-angle-right"></i></span>-->
                        </a>
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="header-wishlist">
                        <a href="index.php?page=Messages" class="message">
                            <span class="icon fa fa-envelope text-white"></span>
                            <span class="badge"><? echo $messages->count_m();?></span>
                        </a>
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="header-wishlist dropdown">
                        <a class="dropdown-toggle notification" data-toggle="slide-collapse" data-target="#slide-navbar-collapse" onclick="openNav_notification()">
                            <span class="icon fa fa-bell text-white"></span>
                            <span class="badge" id="count_n"><? $notification->count_n(); ?></span>
                        </a>
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="header-wishlist text-center">
                        <a class="dropdown-toggle profile" data-toggle="slide-collapse" data-target="#slide-navbar-collapse" onclick="openNav_profile()">
                        <span class="icon fa fa-user text-white"></span>
                        <!--<span class="indicator"><i class="fa fa-angle-right"></i></span>-->
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <? } ?>
        <!-- –––––––––––––––[ END FOOTER ]––––––––––––––– -->
    <!-- ––––––––––––––––––––––––––––––––––––––––– -->
    <!-- END WRAPPER                               -->
    <!-- ––––––––––––––––––––––––––––––––––––––––– -->

    <!-- ========== BUY THEME ========== -->
    <!-- ––––––––––––––––––––––––––––––––––––––––– -->
    <!-- BACK TO TOP                               -->
    <!-- ––––––––––––––––––––––––––––––––––––––––– -->
    <div id="backTop" class="back-top is-hidden-sm-down">
        <i class="fa fa-angle-up" aria-hidden="true"></i>
    </div>
    <!-- ––––––––––––––––––––––––––––––––––––––––– -->
    <!-- SCRIPTS                                   -->
    <!-- ––––––––––––––––––––––––––––––––––––––––– -->
    <!-- (!) Placed at the end of the document so the pages load faster -->
    <!-- ––––––––––––––––––––––––––––––––––––––––– -->
    <!-- Initialize jQuery library                 -->
    <!-- ––––––––––––––––––––––––––––––––––––––––– -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.3/jquery.min.js"></script>
  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <!-- ––––––––––––––––––––––––––––––––––––––––– -->
    <!-- Latest compiled and minified Bootstrap    -->
    <!-- ––––––––––––––––––––––––––––––––––––––––– -->
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <!-- ––––––––––––––––––––––––––––––––––––––––– -->
    <!-- Custom Template JavaScript                -->
    <!-- ––––––––––––––––––––––––––––––––––––––––– -->
    <!--<script src="https://cdn.bootcss.com/sweetalert/1.1.3/sweetalert.min.js"></script>-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="js/main.js"></script>
    <script type="text/javascript" src="js/ajax.js"></script>
</body>

</html>
