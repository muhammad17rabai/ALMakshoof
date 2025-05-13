<?
require_once 'functions.php';
class Messages extends DB
{
    function getsender()
    {
        $user_id = validation($_SESSION['id']);
        $sender = $this->connect()->query("SELECT username FROM users WHERE id = $user_id")->fetch_assoc()['username'];
        return $sender;
    }
    function send_contact()
    {
        $sender_id = validation($_POST['sender_id']);
        $body = validation($_POST['body']);
        $date_created = time();
        if ($body != null) {
            $sql = $this->connect()->query("INSERT INTO contact(sender_id,receiver,body,date_created,seen) VALUES('$sender_id','admin','$body','$date_created',0)");
            if ($sql) {
                echo '<input type="hidden" id="contact" value="success"/>';
            }
        }
    }
    function get_messages()
    {
        $user_id = validation($_SESSION['id']);
        $role = new role;
        if ($role->r('role') == 1) {
            $sql = $this->connect()->query("SELECT * FROM users INNER JOIN contact ON users.id = contact.sender_id WHERE contact.sender_id = $user_id ORDER BY contact.date_created DESC");
        }else {
            if (validation($_GET['status'] == 'unread')) {
                $sql = $this->connect()->query("SELECT * FROM users INNER JOIN contact ON users.id = contact.sender_id WHERE seen = 0 ORDER BY contact.date_created DESC");
            }else{
                $sql = $this->connect()->query("SELECT * FROM users INNER JOIN contact ON users.id = contact.sender_id ORDER BY contact.date_created DESC");
            }
        }
        if ($sql->num_rows == 0) {
            box_alert('secondary','لا توجد رسائل');
        }else{
            while ($fetch = $sql->fetch_assoc()) {?>
            <main>
                <div class="col-md-4 col-sm-6 col-xs-12 mt-10 form-add">
                    <div class="text-center" id="result-message"></div>
                    <a href="index.php?page=Chat&&message_id=<? echo $fetch['id'];?>" class="text-black">
                        <div class="row p-10">
                            <div class="col-xs-3 img-product text-center">
                                <img src="<? echo $fetch['avatar'];?>" class="rounded-circle mb-10" width="100" style="height:65px;">
                                <?
                                    if ($fetch['seen'] == 0) {
                                        echo '<i class="fa fa-circle"></i>';
                                    }
                                ?>
                            </div>
                            <div class="col-xs-9">
                                <div class="message-info">
                                    <h5><? echo $fetch['username'];?><label class="text-secondary" style="float:left;font-size:13px;"><? echo get_date($fetch['date_created']);?></label></h5><hr>
                                    <?
                                        if ($fetch['seen'] == 0) {
                                            echo '<h5>'.$fetch['body'].'</h5>';
                                        }else{
                                            echo '<span>'.$fetch['body'].'</span>'; 
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </main>
        <?  }
        }
    }
    function chating()
    {
        $role = new role;
        $user_id = validation($_SESSION['id']);
        $message_id = validation($_GET['message_id']);
        $page = validation($_GET['page']);
        if ($page == 'Chat' && $message_id != null) {
            if ($role->r('role') == 2) {
                $sql_message = $this->connect()->query("SELECT * FROM chat WHERE message_id = '$message_id' && receiver = 'admin' && seen = 0")->num_rows;
                if ($sql_message > 0) {
                    $sql = $this->connect()->query("UPDATE chat Set seen = 1 WHERE message_id = '$message_id' && receiver = 'admin'");
                    $this->connect()->query("UPDATE contact SET seen = 1 WHERE id = '$message_id'");
                }
            }else{
                $sql_message = $this->connect()->query("SELECT * FROM chat WHERE message_id = '$message_id' && receiver = '$user_id' && seen = 0")->num_rows;
                if ($sql_message > 0) {
                    $sql = $this->connect()->query("UPDATE chat Set seen = 1 WHERE message_id = '$message_id' && receiver = '$user_id'");
                    $this->connect()->query("UPDATE contact SET seen = 1 WHERE id = '$message_id'");
                }
            }
        }
        $sql = $this->connect()->query("SELECT * FROM users LEFT JOIN contact ON users.id = contact.sender_id WHERE contact.id = $message_id");
        while ($fetch = $sql->fetch_assoc()) {?>
            <div class="row form-add p-10 all-messages">
                <div class="col-xs-3 text-center">
                    <img src="<? echo $fetch['avatar'];?>" class="rounded-circle" width="100" style="height:65px;">
                </div>
                <div class="col-xs-9">
                    <div class="mt-15">
                        <h5><? echo $fetch['username'];?></h5>
                        <?
                            if ($fetch['seen'] == 0) {
                                echo '<h5>'.$fetch['body'].'</h5>';
                            }else{
                                echo '<span>'.$fetch['body'].'</span>'; 
                            }
                        ?>
                    </div>
                </div>
            </div>
            <input type="hidden" id="user_id" value="<? echo $user_id;?>">
            <input type="hidden" id="r" value="<? echo $role->r('role');?>">
        <?}
    }
    function rows_m($message_id)
    {
        $rows_message = $this->connect()->query("SELECT * FROM chat WHERE message_id = '$message_id'")->num_rows;
        return $rows_message;

    }
    function send_chat()
    {
        $user_id = validation($_POST['user_id']);
        $message_id = validation($_POST['message_id']);
        $body = validation($_POST['body']);
        $page = validation($_POST['page']);
        $date_updated = time();
        $sql_message = $this->connect()->query("SELECT id,sender_id FROM contact WHERE id = $message_id ")->fetch_assoc();
        if ($sql_message['sender_id'] == $user_id) {
            $sender = $user_id;
            $receiver = 'admin';
        }else{
            $sender = 'admin';
            $receiver = $sql_message['sender_id'];
        }
        if ($body != null && $sender != null && $receiver != null) {
            $sql = $this->connect()->query("INSERT INTO chat(message_id,sender,receiver,body_chat,seen) 
            VALUES('$message_id','$sender','$receiver','$body',0)");
            if ($sql) {
                $this->connect()->query("UPDATE contact SET date_created = '$date_updated', seen = 0 WHERE id = '$message_id'");
                echo '<input type="hidden" id="send_chat" value="success" />';
            }
        }
    }
    function get_chat()
    {
        $user_id = validation($_POST['user_id']);
        $role = validation($_POST['r']);
        $message_id = validation($_POST['message_id']);
        $sql = $this->connect()->query("SELECT * FROM chat WHERE message_id = $message_id");
        $dd = '';
        if ($sql->num_rows == 0) {
            if ($role == 1) {
                box_alert('secondary','سيتم الرد على رسالتك في أقرب وقت ممكن');
            }
        }else{
            while ($fetch = $sql->fetch_assoc()) {
                $date = date('Y-m-d',strtotime($fetch['date_created']));
                switch ($date) {
                    case date('Y-m-d'):
                        $d = 'اليوم';
                        break;
                    case date('Y-m-d',strtotime('-1 day')):
                        $d = 'الأمس';
                        break;
                    default :
                        $d = $date;
                        break;
                }
            if ($dd != $d) {
                $dd = $d;
            ?>
                <div class="position-relative">
                    <div class="chat-messages p-4">
                        <div class="text-center">
                            <span class="text-secondary"><? echo $d;?></span>
                        </div>
                    </div>
                </div>
            <?
            }
                if ($role == 1) {
                    $sql_user = $this->connect()->query("SELECT * FROM users WHERE id = $user_id ")->fetch_assoc();
                    if ($fetch['sender'] == $user_id) {?>
                        <div class="chat-message-left pb-3 m-5">
                            <div>
                                <img src="<? echo $sql_user['avatar'];?>" class="rounded-circle mr-1" alt="Sharon Lessman" width="40" style="height:40px;">
                                <div class="text-muted small text-nowrap mt-2"><? echo date('h:i-a',strtotime($fetch['date_created']));?></div>
                            </div>
                            <div class="flex-shrink-1 rounded py-2 px-3 mr-3">
                                <label class="font-weight-bold mb-1 alert-info p-10" style="border-radius:15px;"><? echo $fetch['body_chat'];?></label>   
                            </div>
                        </div>
                  <? }elseif($fetch['receiver'] == $user_id){
                    $sql_user = $this->connect()->query("SELECT * FROM users WHERE role = '{$fetch['sender']}' ")->fetch_assoc();?>
                    <div class="chat-message-right pb-3 m-5">
                        <div>
                            <img src="<? echo $sql_user['avatar'];?>" class="rounded-circle mr-1" alt="Chris Wood" width="40" style="height:40px;">
                            <div class="text-muted small text-nowrap mt-2"><? echo date('h:i-a',strtotime($fetch['date_created']));?></div>
                        </div>
                        <div class="flex-shrink-1 rounded py-2 px-3 ml-3">
                            <label class="font-weight-bold mb-1 bg-light p-10" style="border-radius:15px;"><? echo $fetch['body_chat'];?></label>   
                        </div>
                    </div>
                <? }
                }elseif($role == 2) {
                    $sql_user = $this->connect()->query("SELECT * FROM users WHERE role = '{$fetch['sender']}' ")->fetch_assoc();
                    if ($fetch['sender'] == 'admin') {?>
                        <div class="chat-message-left pb-3 m-5">
                            <div>
                                <img src="<? echo $sql_user['avatar'];?>" class="rounded-circle mr-1" alt="Sharon Lessman" width="40" style="height:40px;">
                                <div class="text-muted small text-nowrap mt-2"><? echo date('h:i-a',strtotime($fetch['date_created']));?></div>
                            </div>
                            <div class="flex-shrink-1 rounded py-2 px-3 mr-3">
                                <label class="font-weight-bold mb-1 alert-info p-10" style="border-radius:15px;"><? echo $fetch['body_chat'];?></label><br>
                                <?
                                    if ($fetch['seen'] == 1) {
                                        echo '<span class="text-secondary">seen</span>';
                                    }
                                ?>
                            </div>
                        </div>
                  <? }elseif($fetch['receiver'] == 'admin'){
                    $sql_user = $this->connect()->query("SELECT * FROM users WHERE id = '{$fetch['sender']}'")->fetch_assoc();?>
                    <div class="chat-message-right pb-3 m-5">
                        <div>
                            <img src="<? echo $sql_user['avatar'];?>" class="rounded-circle mr-1" alt="Chris Wood" width="40" style="height:40px;">
                            <div class="text-muted small text-nowrap mt-2"><? echo date('h:i-a',strtotime($fetch['date_created']));?></div>
                        </div>
                        <div class="flex-shrink-1 rounded py-1 px-3">
                            <label class="font-weight-bold mb-1 bg-light p-10" style="border-radius:15px;"><? echo $fetch['body_chat'];?></label>   
                        </div>
                    </div>
                <? }
                }
            }
        }
    }

    function count_m()
    {
        $role = new role;
        $user_id = $_SESSION['id'];
        if ($role->r('role') == 2) {
            $sql_chat = $this->connect()->query("SELECT * FROM chat WHERE receiver = 'admin' && seen = 0 ORDER BY id DESC")->num_rows;
            $sql_contact = $this->connect()->query("SELECT * FROM contact WHERE seen = 0 ORDER BY id DESC")->num_rows;
            $rows = $sql_contact + $sql_chat;
        }else{
            $sql_chat = $this->connect()->query("SELECT * FROM chat WHERE receiver = '$user_id' && seen = 0 ORDER BY id DESC")->num_rows;
            $rows = $sql_chat;
        }
        if ($rows > 0) {
            if ($rows > 99) {
                echo '99+';
            }else{
                echo $rows;
            }
        }
        
    }
}
$messages = new Messages;
$f = validation($_GET['f']);
switch ($f) {
    case 'send_contact':
        $messages->send_contact();
        break;
    case 'send_chat':
        $messages->send_chat();
        break;
    case 'get_chat':
        $messages->get_chat();
        break;
}
?>