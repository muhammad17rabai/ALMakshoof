<?php
    require_once 'functions.php';
    class store_user extends DB
    {
        public function adduser()
        {
            $name = validation($_POST['name']);
            $email = validation($_POST['email']);
            $phone = validation($_POST['phone']);
            $password = validation($_POST['password']);
            $password_hash = md5($password);
            $city = validation($_POST['city']);
            $address= validation($_POST['address']);
            $gender = validation($_POST['gender']);
            $agree = validation($_POST['agree_terms']);
            $avatar = "icons/user.jpg";
            if (isset($_SESSION['id'])) {
                $role = validation($_POST['role']);
            }else{
                $role = 'member';
            }
            $date = time();
            $active = 1;
            
            $sql_email = "SELECT email FROM users WHERE email = '$email'";
            $query_email = $this->connect()->query($sql_email);
            $rows_email = $query_email->num_rows;
            
            $sql_phone = "SELECT phone FROM users WHERE phone = '$phone'";
            $query_phone = $this->connect()->query($sql_phone);
            $rows_phone = $query_phone->num_rows;

            $val = validate_user($name,$email,$rows_email,$phone,$rows_phone,$password_hash,$city,$address,$gender,$agree,$role);
            if(isset($_POST['create_user'])){
                //echo $val;
               if(empty($val)){
                    $sql = "INSERT INTO users (username,email,phone,password,city,address,gender,avatar,role,created_at,active) 
                    VALUES('$name','$email','$phone','$password_hash','$city','$address','$gender','$avatar','$role','$date','$active')";
                    $query = $this->connect()->query($sql);
                    if($query){
                        $color = "success";
                        $text = "تم اضافة الحساب بنجاح";
                        box_alert($color , $text);
                        //header('location:index.php?page='.$_GET["page"]);

                    }
                }else{
                    $color = "danger";
                    box_alert($color , $val);
                }
        }
        }
        

    }
