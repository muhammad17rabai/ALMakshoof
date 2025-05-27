<?
require_once 'connect.php';
require_once 'functions.php';
require_once 'notifications_func.php';
class Actions extends DB
{
    function accept_order($t)
    {
        $id = validation($_POST['id']);
        $user_id = validation($_POST['user_id']);
        $table = validation($_POST['table']);
        if ($table == 'pages') {
            $t = 0;
            //$type = 'page';
        }elseif($table == 'product'){
            $t = 1;
            //$type = 'product';
        }elseif($table == 'subscribe'){
            $t = 2;
        }elseif($table == 'brokers'){
            $t = 3;
        }
        $acceptorder = $this->connect()->query("UPDATE $table SET status = 1, active = 1 WHERE id = '$id'");
        if($acceptorder){
            echo '<input type="hidden" id="success" value="success">';
            $m = new Notifications;
            $m->save_notification($id,'admin',$user_id,' حسنا ✅ تمت الموافقة على طلبك ','success',$t);
        }
    }
    function del_order($t,$type)
    {
        $id = validation($_POST['id']);
        $user_id = validation($_POST['user_id']);
        $table = validation($_POST['table']);
        $img = validation($_POST['img']);
        if ($table == 'pages') {
            $item_id = "page_id";
            $t = 0;
            $type = 'page';
        }else if($table == 'product'){
            $item_id = "product_id";
            $t = 1;
            $type = 'product';
        }elseif($table == 'subscribe'){
            $t = 2;
            $type = 'subscribe';
        }elseif($table == 'brokers'){
            $t = 3;
            $type = 'broker';
        }
        $delorder = $this->connect()->query("DELETE FROM $table WHERE id = '$id'");
        $delimg = $this->connect()->query("DELETE FROM $img WHERE $item_id = '$id'");
        $delnotes = $this->connect()->query("DELETE FROM order_notes WHERE order_id = '$id' && type = '$type'");
        $delnotic = $this->connect()->query("DELETE FROM notifications WHERE item_id = '$id' && type = '$type'");
        if($delorder && $delnotes){
            echo '<input type="hidden" id="success" value="success">';
            $sql = $this->connect()->query("DELETE FROM notifications WHERE item_id = '$id' && type = '$t'");
        }
    }
    function pendding_order($t,$type)
    {
        $id = validation($_POST['id']);
        $user_id = validation($_POST['user_id']);
        $table = validation($_POST['table']);
        $date = time();
        $notes = validation($_POST['notes']);
        if ($table == 'pages') {
            $t = 0;
            $type = 'page';
        }elseif($table == 'products'){
            $t = 1;
            $type = 'product';
        }elseif($table == 'subscribe'){
            $t = 2;
            $type = 'subscribe';
        }elseif($table == 'brokers'){
            $t = 3;
            $type = 'broker';
        }
        if($notes == null){
            box_alert('danger','يرجى ادخال سبب تعليق الطلب');
        }else{
            $sql = $this->connect()->query("INSERT INTO order_notes(order_id,date_created,notes,status,type) VALUES('$id','$date','$notes',0,'$type')");
            $query = $this->connect()->query("UPDATE $table SET status = 2, active = 1 WHERE id = '$id'");
            $m = new Notifications;
            $m->save_notification($id,'admin',$user_id,' عفوا ⚠️ تم تعليق طلبك ','success',$t);

        }
        if($sql && $query){
            echo '<input type="hidden" id="success" value="success">';
        }
    }
    function edit_pendding($t,$type)
    {
        $id = validation($_POST['id']);
        $user_id = validation($_POST['user_id']);
        $notes = validation($_POST['notes']);
        $table = validation($_POST['table']);
        if ($table == 'pages') {
            $t = 0;
            $type = 'page';
        }elseif($table == 'product'){
            $t = 1;
            $type = 'product';
        }elseif($table == 'subscribe'){
            $t = 2;
            $type = 'subscribe';
        }elseif($table == 'brokers'){
            $t = 3;
            $type = 'broker';
        }
        if($notes == null){
            box_alert('danger','يرجى ادخال سبب تعليق الطلب');
        }else{
            $query = $this->connect()->query("UPDATE order_notes SET notes = '$notes' WHERE id = '$id' && type = '$type'");
        }
        if($query){
            echo '<input type="hidden" id="success" value="success">';
            $m = new Notifications;
            $m->save_notification($id,'admin',$user_id,' عفوا ⚠️ تم تعليق طلبك ','success',$t);
        }
    }
    function delete_pendding()
    {
        $id = validation($_POST['id']);
        if($id != null){
            $query = $this->connect()->query("DELETE FROM order_notes WHERE id = '$id'");
        }
        if($query){
            echo '<input type="hidden" id="success" value="success">';
        }
    }
    function reject_order($t,$type)
    {
        $id = $_POST['id'];
        $user_id = validation($_POST['user_id']);
        $table = $_POST['table'];
        $date = time();
        $notes = validation($_POST['notes']);
        if ($table == 'pages') {
            $t = 0;
            $type = 'page';
        }elseif($table == 'products'){
            $t = 1;
            $type = 'product';
        }elseif($table == 'subscribe'){
            $t = 2;
            $type = 'subscribe';
        }elseif($table == 'brokers'){
            $t = 3;
            $type = 'broker';
        }
        if($notes == null){
            box_alert('danger','يرجى ادخال سبب رفض الطلب');
        }else{
            $sql = $this->connect()->query("INSERT INTO order_notes(order_id,date_created,notes,status,type) VALUES('$id','$date','$notes',1,'$type')");
            $query = $this->connect()->query("UPDATE $table SET status = 3, active = 1 WHERE id = '$id'");
            $m = new Notifications;
            $m->save_notification($id,'admin',$user_id,' عذرا ❌ تم رفض طلبك ','success',$t);

        }
        if($sql && $query){
            echo '<input type="hidden" id="success" value="success">';
        }
    }
    function edit_reject($t,$type)
    {
        $id = $_POST['id'];
        $user_id = validation($_POST['user_id']);
        $notes = validation($_POST['notes']);
        $table = validation($_POST['table']);
        if ($table == 'pages') {
            $t = 0;
            $type = 'page';
        }elseif($table == 'products'){
            $t = 1;
            $type = 'product';
        }elseif($table == 'subscribe'){
            $t = 2;
            $type = 'subscribe';
        }elseif($table == 'brokers'){
            $t = 3;
            $type = 'broker';
        }
        if($notes == null){
            box_alert('danger','يرجى ادخال سبب رفض الطلب');
        }else{
            $query = $this->connect()->query("UPDATE order_notes SET notes = '$notes' WHERE id = '$id' && type = '$type'");
        }
        if($query){
            echo '<input type="hidden" id="success" value="success">';
            $m = new Notifications;
            $m->save_notification($id,'admin',$user_id,' عذرا ❌ تم رفض طلبك ','success',$t);
        }
    }
    function delete_reject()
    {
        $id = validation($_POST['id']);
        if($id != null){
            $query = $this->connect()->query("DELETE FROM order_notes WHERE id = '$id'");
        }
        if($query){
            echo '<input type="hidden" id="success" value="success">';
        }
    }
}
$actions = new Actions;
switch ($_GET['action']) {
    case 'delorder':
        $actions->del_order($t,$type);
        break;
    case 'acceptorder':
        $actions->accept_order($t);
        break;
    case 'pendding':
        $actions->pendding_order($t,$type);
        break;
    case 'editpendding':
        $actions->edit_pendding($t,$type);
        break;
    case 'delpendding':
        $actions->delete_pendding();
        break;
    case 'reject':
        $actions->reject_order($t,$type);
        break;
    case 'editreject':
        $actions->edit_reject($t,$type);
        break;
    case 'delreject':
        $actions->delete_reject();
        break;
}
