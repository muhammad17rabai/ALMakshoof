<?php
require_once 'devices_func.php';
require_once 'functions.php';
class signin extends DB
{
    public function login()
    {
        $username = validation($_POST['username']);
        $password = validation($_POST['password']);
        $password_hash = md5($password);

        $sql = $this->connect()->query("SELECT * FROM users WHERE (email = '$username' || phone = '$username') && password = '$password_hash'");
        $rows_user = $sql->num_rows;
        $fetch = $sql->fetch_assoc();
        
        $user_id = $fetch['id'];
        $finger = validation($_POST['finger']);
        /******************* device info *****************************/
            $user_details = new UserDetails;
            $get_country = $user_details->get_country();

            $device_name = $user_details->get_device();
            $browser = $user_details->get_browser();
            $os = $user_details->get_os();
            $external_ip = $get_country['external_ip'];
            $country = $get_country['country'];
            $city = $get_country['city'];
            $region = $get_country['region'];
            $city_time = $get_country['city_time'];
            $width = $get_country['width'];
            $hieght = $get_country['hieght'];
            $date_created = time();
            $date_updated = time();
<<<<<<< HEAD
            //echo 'ep = '.$external_ip;
=======
            echo 'ep = '.$external_ip;
>>>>>>> 59c534d37b6913ec336867cf6808de8e215e3e08
        /**************************************** end device info *************************************************/
        $sql_d = $this->connect()->query("SELECT * FROM devices WHERE user_id = '$user_id' && active=1");
        $rows_d = $sql_d->num_rows;
        $a = array();
        $fetch_d = $sql_d->fetch_assoc();
        $val = validate_login($username,$password,$rows_user);
        if(isset($_POST['login'])){
            if(empty($val)){
                if ($fetch['active'] == 1) {
                    if ($fetch['role'] == 'member') {
                        if ($device_name == 'Mobile') {
                            if ($rows_d == 0) {
                                $sql = $this->connect()->query("INSERT INTO devices(user_id,finger,external_ip,type,os,country,city,region,city_time,browser,width,hieght,
                                date_created,date_updated,online_d,active) VALUES('$user_id','$finger','$external_ip','$device_name','$os','$country','$city','$region',
                                '$city_time','$browser','$width','$hieght','$date_created','$date_updated',1,1)");
                                $_SESSION['id'] = $user_id;
                                $_SESSION['finger'] = $finger;
                                header('location:index.php?page=home');
                            }elseif($rows_d == 1){
                                if ($finger == $fetch_d['finger'] && $os == $fetch_d['os']) {
                                    $_SESSION['id'] = $user_id;
                                    $_SESSION['finger'] = $finger;
                                    header('location:index.php?page=home');
                                }else {
                                    $sql = $this->connect()->query("INSERT INTO devices(user_id,finger,external_ip,type,os,country,city,region,city_time,browser,width,hieght,
                                    date_created,date_updated,online_d,active) VALUES('$user_id','$finger','$external_ip','$device_name','$os','$country','$city','$region',
                                    '$city_time','$browser','$width','$hieght','$date_created','$date_updated',1,1)");
                                    $_SESSION['id'] = $user_id;
                                    $_SESSION['finger'] = $finger;
                                    header('location:index.php?page=home');
                                }
                            }else{
                                while ($fetch_d = $sql_d->fetch_assoc()) {
                                    array_push($a,$fetch_d['finger']);
                                }
                                if (in_array($finger,$a)) {
                                    $_SESSION['id'] = $user_id;
                                    $_SESSION['finger'] = $finger;
                                    header('location:index.php?page=home');
                                }else{
                                    box_alert('danger','لقد وصلت الى عدد الأجهزة المسموح به لهذا الحساب');
                                }
                            } 
                        }else{
                            box_alert('danger','هذا التطبيق مخصص لأجهزة الموبايل فقط');
                        } 
                    }else {
                        $_SESSION['id'] = $user_id;
                        header('location:index.php?page=home');
                    }
                }else{
                    box_alert('danger','هذا الحساب معطل الرجاء التواصل مع مسؤول النظام لاعادة تفعيله');
                }
            }else{
                box_alert('danger',$val);
            }
            if ($fetch_d['external_ip'] != $external_ip || $fetch_d['country'] != $country || $fetch_d['city'] != $city || $fetch_d['regoin'] != $region || $fetch_d['city_time'] != $city_time) {
                $this->connect()->query("UPDATE devices SET external_ip='$external_ip', country='$country', city='$city', region='$region',
                city_time='$city_time' WHERE user_id='$user_id' && finger='$finger' && active=1");
            }
        }
    }
}

