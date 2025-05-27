<?
if (file_exists('config.php')) {
    require_once 'config.php';
}else{
    require_once 'connect.php';
}
require_once 'functions.php';
global $st;
$st = validation($_POST['st']);
class Newsub extends DB
{
    function newsub()
    {
        $periods = validation($_POST['periods']);
        $user_id = validation($_POST['user_id']);
        $start = date('Y-m-d');
        if($periods > 0 ){
            if($periods == 6){
                $new_enddate = date('Y-m-d',date(strtotime("+6 month")));
            }elseif($periods == 1){
                $new_enddate =date('Y-m-d',date(strtotime("+1 year")));
            }
            $sql = "INSERT INTO subsecribe (user_id,start,end,status) VALUES('$user_id','$start','$new_enddate','1')";
            $query = $this->connect()->query($sql);
            if($query){
                //header('location:../index.php?page=Allmembers');
                box_alert('success' , 'تم الاشتراك بنجاح');
            }else{
                box_alert('danger' , ' حدث خطأ يرجى اعادة المحاولة');
            }
        }    
    }    
}
class Renewsub extends DB
{
     function renewsub()
    {
        $periods = validation($_POST['periods']);
        $user_id = validation($_POST['user_id']);
        if($periods > 0){
            if($periods == 6){
                $new_enddate = date('Y-m-d',date(strtotime("+6 month")));
            }elseif($periods == 1){
                $new_enddate =date('Y-m-d',date(strtotime("+1 year")));
        }
        $sql = "UPDATE subsecribe SET end = '$new_enddate' , status = 1 WHERE user_id = '$user_id'";
        $query = $this->connect()->query($sql);
        if($query){
            //header('location:../index.php?page=Allmembers');
            box_alert('success' , 'تم تجديد الاشتراك بنجاح');
        }else{
            box_alert('danger' , ' حدث خطأ يرجى اعادة المحاولة');
        }
    }   
    }
}

switch ($st) {
    case 'null':
        $newsub = new Newsub;
        break;
    default:
        $newsub = new Renewsub;
        break;
}