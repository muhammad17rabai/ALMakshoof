<?php
if (file_exists('config.php')) {
    require_once 'config.php';
}else{
    require_once 'connect.php';
}
require_once 'functions.php';
class Favorate extends DB
{
    function add_wishlist()
    {
        $user_id = validation($_POST['user_id']);
        $item_id = validation($_POST['item_id']);
        $type = validation($_POST['type']);
        if ($user_id != null && $item_id != null) {
            $sql = $this->connect()->query("INSERT INTO favorate (user_id,item_id,type) VALUES('$user_id','$item_id','$type')");
        }if($sql){
            echo '<input type="hidden" id="success" value="success">';
        }else{
            box_alert('danger','حطأ ! لم يتم الاضافة الى قائمة المفضلة');
        }
    }
    function remove_wishlist()
    {
        $id = validation($_POST['id']);
        if ($id != null) {
            $sql = $this->connect()->query("DELETE FROM favorate WHERE id = $id ");
        if($sql){
            echo '<input type="hidden" id="success" value="success">';
        }else{
            box_alert('danger','خطأ ! لم تتم الازالة من قائمة المفضلة');
        }
    }
    }   
}
$favorate = new Favorate;
$f = validation($_GET['f']);
switch ($f) {
case 'fav':
    $favorate->add_wishlist();
    break;
case 'removefav':
    $favorate->remove_wishlist();
    break;
}
?>