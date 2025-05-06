<?
if(!isset($_SESSION['id'])){
    header("location:index.php?page=login");
}
require_once './functions/messages_func.php';
$role = new role;
$all = ''; $unread = '';
if ($role->r('role') == 2) {
    if (validation($_GET['status'] == 'unread')) {
        $unread = 'active';
    }else{
        $all = 'active';
    }?>
    <div class="header mt-20">
        <ul class="nav nav-tabs justify-content-center" role="tablist">
            <li class="nav-item <? echo $all;?>">
                <a class="nav-link" href="index.php?page=Messages">
                    <i class="now-ui-icons objects_umbrella-13"></i> كل الرسائل
                </a>
            </li>
            <li class="nav-item <? echo $unread;?>">
                <a class="nav-link" href="index.php?page=Messages&&status=unread">
                    <i class="now-ui-icons objects_umbrella-13"></i> غير مقروءة
                </a>
            </li>
        </ul>
    </div>
<?}?>
<div class="page-container">
    <div class="container">
        <div class="row" id="myUL">
            <?
                $messages = new Messages;
                $messages->get_messages();
            ?>
        </div>
        <div id="result-search">
            <? box_alert('secondary','لا توجد رسائل')?>
        </div>
    </div>
</div>