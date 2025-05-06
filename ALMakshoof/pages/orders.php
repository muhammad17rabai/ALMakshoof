<?php
    if(!isset($_SESSION['id'])){
        header("location:index.php?page=login");
    }
    require_once './functions/orders_func.php';
    $role = new role;
    $status = validation($_GET['st']);
    switch ($status) {
        case 0:
            $st = 'قيد المراجعة';
            break;
        case 1:
            $st = ' تم النشر';
            break;
        case 2:
            $st = ' معلق';
            break;
        case 3:
            $st = ' مرفوض';
            break;
        case 4:
            $st = ' كل الطلبات';
            break;
        default:
            $st = ' كل الطلبات';
            break;
    }
    $table = validation($_GET['type']);
    switch ($table) {
        case 'pages':
            $tt = " طلبات الصفحات ";
            break;
        case 'products':
            $tt = " طلبات المنتجات ";
            break;
        case 'brokers':
            $tt = " طلبات الوسطاء ";
            break;
    }
?>
<!-- –––––––––––––––[ PAGE CONTENT ]––––––––––––––– -->
<main id="mainContent" class="main-content">
    <!-- Page Container -->
    <div class="page-container">
        <div class="container">
            <div class="row" id="myUL">
                <div class="col-xs-12 mt-15 d-flex justify-content-between install">
                    <label class="form-control bg-white text-st col-xs-6"> نوع الطلبات  : </label>
                    <select class="form-control bg-white" name="choice-type" id="choice_type" data-st="<? echo $status;?>">
                        <option value=""><? echo $tt;?></option>
                        <option value="pages">  طلبات الصفحات </option>
                        <option value="products">  طلبات المنتجات </option>
                        <?
                            if ($role->r('role')==2){
                                echo '<option value="brokers">  طلبات الوسطاء </option>';
                            }
                        ?>
                    </select>
                </div> 
                <div class="col-xs-12 d-flex justify-content-between install">
                    <label class="form-control bg-white text-st col-xs-6"> فلتر الحالة : </label>
                    <select class="form-control bg-white" name="choice-status" id="choice_status" data-table="<? echo $table;?>">
                        <option value=""><? echo $st;?></option>
                        <option value="4"> كل الطلبات </option>
                        <option value="0"> قيد المراجعة </option>
                        <option value="1"> تم النشر </option>
                        <option value="2"> معلق </option>
                        <option value="3"> مرفوض </option>
                    </select>
                </div>
                <?
                    $allorderds = new AllOrders;
                    $allorderds->getallorder();
                ?>
            </div>
            <div id="result-search">
                <? box_alert('secondary','لا توجد نتائج');?>
            </div>
        </div>
    </div>
</main>