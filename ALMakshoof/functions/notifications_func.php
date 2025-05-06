<?
require_once 'connect.php';
class Notifications extends DB
{
    /*

      type : 0 = page;
      type : 1 = product;

    */
    function save_notification($item_id,$sender,$receiver,$body,$color,$type)
    {
        $datee = time();
        $sqln = $this->connect()->query("INSERT INTO notifications (item_id,sender,receiver,date_created,body,color,type)
            VALUES('$item_id','$sender','$receiver','$datee','$body','$color','$type')");
            if ($sqln) {
                echo '<input type="hidden" id="notification" value="success"/>';
        }
    }
    function getnotification()
    {
        $role = new role;
        $user_id = $_SESSION['id'];
        if ($role->r('role') == 2) {
            $sql = $this->connect()->query("SELECT * FROM notifications WHERE receiver = 'admin' || receiver = '$user_id' ORDER BY id DESC");
        }else{
            $sql = $this->connect()->query("SELECT * FROM notifications WHERE receiver = '$user_id' ORDER BY id DESC");
        }
        if ($sql->num_rows == 0) {
            box_alert('secondary' , 'لا يوجد اشعارات');
        }else{
            while ($fetch = $sql->fetch_assoc()) {
                if ($fetch['type'] == 0) {
                    $url = "notic_details&&page_id=".$fetch['item_id']."&&S=page_req";
                }elseif($fetch['type'] == 1){
                    $url = "notic_details&&product_id=".$fetch['item_id']."&&S=product_req";
                }elseif($fetch['type'] == 2){
                    if ($fetch['receiver'] == 'admin') {
                        $url = "Allsubscribe";
                    }else{
                        $url = "subscribe&&sub_id=".$fetch['item_id'];
                    }
                }elseif($fetch['type'] == 3){
                    if ($fetch['receiver'] == 'admin') {
                        $url = "Broker_details&&site_id=".$fetch['item_id']."&&user_id=".$fetch['sender'];
                    }else{
                        $url = "Createbrokers";
                    }
                }
            ?>
            <div class="alert alert-<? echo $fetch['color']; ?> fade in radius-bordered alert-shadowed p-2 mt-10">
                    <span class="text-black-50"><? get_date($fetch['date_created']);?></span><br>
                    <span class="badge badge-success graded"></span>
                        <a href="index.php?page=<? echo $url;?>">
                            <?
                            echo $fetch['body'];
                            ?>
                        </a>
                    <label class="close-n" data-dismiss="alert" data-id="<? echo $fetch['id'];?>"> × </label>
                </div>
            <?}
        }
    }
    function count_n()
    {
        $role = new role;
        $user_id = $_SESSION['id'];
        if ($role->r('role') == 2) {
            $sql = $this->connect()->query("SELECT * FROM notifications WHERE receiver = 'admin' || receiver = '$user_id' ORDER BY id DESC");
        }else{
            $sql = $this->connect()->query("SELECT * FROM notifications WHERE receiver = '$user_id' ORDER BY id DESC");
        }
        $rows = $sql->num_rows;
        if ($rows > 0) {
            if ($rows > 99) {
                echo '99+';
            }else{
                echo $rows;
            }
        }
        
    }

    function del_n()
    {
        $id = $_POST['id'];
        if($id != null){
            $sql = $this->connect()->query("DELETE FROM notifications WHERE id = '$id'");
        }
    }
}
$notification = new Notifications;
$f = $_GET['f'];
switch ($f) {
    case 'save':
        $notification->count_n();
        break;

    case 'del':
        $notification->del_n();
        break;
    }
?>