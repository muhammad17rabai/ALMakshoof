<?
    $role = new role;
    if(!isset($_SESSION['id'])){
            header("location:index.php?page=login");
        }
        require_once './functions/myprofile_func.php';
?>
<div class="container">
    <div class="row">
        <form action="index.php?page=Myprofile" method="post" enctype="multipart/form-data">
            <div class="header mt-20">
                <ul class="nav nav-tabs pr-5">
                    <li class="nav-item title-maininfo active">
                        <a href="index.php?page=Myprofile" class="nav-link">المعلومات الشخصية</a>
                    </li>
                    <li class="nav-item title-editpass">
                        <a href="index.php?page=password" class="nav-link"> تغيير كلمة السر </a>
                    </li>
                </ul>
            </div>
            <?
                $myinfo = new MyProfile;
                $myinfo->edit_myinfo();
                $myinfo->getmyinfo();
            ?>
            <!--------------------------- edit info box ---------------------------------->
        </form>
    </div>
</div>