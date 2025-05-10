<?
    $role = new role();
    if(!isset($_SESSION['id'])){
        header("location:index.php?page=login");
    }elseif($role->r('role') < 2){
        header("location:index.php?page=home");
    }
    require_once './functions/subscribe_func.php';

    $status = validation($_GET['st']);
    switch ($status) {
        case '':
            $st = ' كل الطلبات';
            break;
        case 0:
            $st = 'قيد المراجعة';
            break;
        case 1:
            $st = ' تم الاشتراك';
            break;
        case 2:
            $st = ' معلق';
            break;
        case 3:
            $st = ' مرفوض';
            break;
        case 4:
            $st = ' مكتمل ';
            break;
        case 5:
            $st = ' كل الطلبات';
            break;
        default:
            $st = ' كل الطلبات';
            break;
    }
?>
<!-- –––––––––––––––[ PAGE CONTENT ]––––––––––––––– -->
<div class="container mt-10">
    <div class="row" id="myUL">
        <div class="col-xs-12 d-flex justify-content-between install">
            <label class="form-control bg-white text-st col-xs-6"> فلتر الحالة : </label>
            <select class="form-control bg-white" name="choice-status" id="choice_status_sub">
                <option value=""><? echo $st;?></option>
                <option value="5"> كل الطلبات </option>
                <option value="0"> قيد المراجعة </option>
                <option value="1"> تم الاشتراك </option>
                <option value="2"> معلق </option>
                <option value="3"> مرفوض </option>
                <option value="4"> مكتمل </option>
            </select>
        </div>
        <?
            $newsub = new Newsubscribe;
            $newsub->getorder_subscribe();
        ?>
    </div>
    <div id="result-search">
        <? box_alert('secondary','لا توجد نتائج')?>
    </div>
</div>
