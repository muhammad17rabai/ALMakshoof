<?php
    $role = new role;
    if(!isset($_SESSION['id'])){
            header("location:index.php?page=login");
        }elseif($role->r('role') != 2){
            header("location:index.php?page=home");
        }
    require_once './functions/allmembers_func.php';
?>
<!-- –––––––––––––––[ PAGE CONTENT ]––––––––––––––– -->
<main id="mainContent" class="main-content">
    <!-- Page Container -->
    <div class="page-container">
        <div class="container">
            <div class="row" id="myUL">
                <?php
                    $getmember = new getmembers;
                    $getmember->get_member_sub();                 
                ?>
            </div>
            <div id="result-search">
                <? box_alert('secondary','لا توجد نتائج')?>
            </div>
        </div>
    </div>
</main>
<!-- –––––––––––––––[ END PAGE CONTENT ]––––––––––––––– -->
