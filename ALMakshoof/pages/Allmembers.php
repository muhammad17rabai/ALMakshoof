<?php
    $role = new role;
    if(!isset($_SESSION['id'])){
            header("location:index.php?page=login");
        }elseif($role->r('role') != 2){
            header("location:index.php?page=home");
        }
    require_once './functions/allmembers_func.php';
    switch (validation($_GET['type'])) {
        case 'online_m':
            $st = ' الأعضاء النشطون';
            break;
        case 'active_m':
            $st = ' الحسابات المفعلة ';
            break;
        case 'disable_m':
            $st = ' الحسابات المعطلة ';
            break;
        default:
            $st = 'كل الأعضاء';
            break;
    }
?>
<!-- –––––––––––––––[ PAGE CONTENT ]––––––––––––––– -->
<main id="mainContent" class="main-content">
    <!-- Page Container -->
    <div class="page-container">
        <div class="container">
            <div class="row mt-10 p-5">
                <div class="col-xs-12 d-flex justify-content-between install">
                    <label class="form-control bg-white text-st col-xs-8"> عرض حسب : </label>
                    <select class="form-control bg-white" id="type_member">
                        <option value=" "><? echo $st ;?> <span id="count_m"></span> </option>
                        <option value="all_m"> كل الأعضاء </option>
                        <option value="online_m"> الأعضاء النشطون </option>
                        <option value="active_m">  الحسابات المفعلة </option>
                        <option value="disable_m"> الحسابات المعطلة </option>
                    </select>
                </div>
                <div class="header col-xs-12 mt-10">
                    <ul class="nav nav-tabs justify-content-center" role="tablist">
                        <li class="nav-item active">
                            <a class="nav-link" href="index.php?page=Allmembers" role="tab">
                                <i class="now-ui-icons objects_umbrella-13"></i> قائمة الأعضاء
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=Addmember" role="tab">
                                <i class="now-ui-icons objects_umbrella-13"></i> اضافة عضو جديد +
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row" id="myUL">
                <?php
                    $getmember = new getmembers;
                    $getmember->get_member();                    
                ?>
            </div>
            <div id="result-search">
                <? box_alert('secondary','لا توجد نتائج')?>
            </div>
        </div>
    </div>
    <!-------------------------------------- End details modal ---------------------------------------------->
<!-- End Page Container -->
</main>
<!-- –––––––––––––––[ END PAGE CONTENT ]––––––––––––––– -->
