<?
    require_once 'functions.php';
    class Recover_password extends DB
    {
        function check_user()
        {
            $username = validation($_POST['username']);
            $sql = $this->connect()->query("SELECT * FROM users WHERE (email = '$username' || phone = '$username') && role = 'member'");
            $fetch = $sql->fetch_assoc();
            $user_id = $fetch['id'];
            $email = $fetch['email'];
            if (isset($_POST['send_code'])) {
                if ($username != null) {
                    if ($sql->num_rows > 0) {
                        $code = rand(100000,999999);
                        if ($code > 0) {
                            $this->connect()->query("UPDATE users SET code = '$code' WHERE id = '$user_id'");
                            mail($email,'رمز الأمان الخاص بك هو',$code);
                            error_reporting(-1);
                            ini_set('display_errors', 'On');
                            set_error_handler("var_dump");

                            ini_set("mail.log", "/tmp/mail.log");
                            ini_set("mail.add_x_header", TRUE);

                            // مكونات بريدنا الإلكتروني
                            $to = $email;
                            $subject = 'رمز الأمان الخاص بك هو';
                            $message = $code;
                            $headers = implode("\r\n", [
                                'From: webmaster@example.com',
                                'Reply-To: webmaster@example.com',
                                'X-Mailer: PHP/' . PHP_VERSION
                            ]);

                            // إرسال البريد الإلكتروني
                            $result = mail($to, $subject, $message, $headers);
                            if ($result) {
                                // (1)
                                header("location:index.php?page=Recover_password&&user_id=$user_id&&u=check_code");
                                exit;
                            }
                            else {
                                echo 'error';
                            }
                            
                            header("location:index.php?page=Recover_password&&user_id=$user_id&&u=check_code");
                        }
                    }else{
                        box_alert('danger','الايميل أو رقم الهاتف خاطئ وغير مرتبط بأي حساب');
                    }
                }else{
                    box_alert('danger','الرجاء ادخال الايميل أو رقم الهاتف المرتبط بحسابك');
                }
            }

        }

        function check_code()
        {
            $code = validation($_POST['code']);
            $user_id = validation($_GET['user_id']);
            $n = $this->connect()->query("SELECT code FROM users WHERE id = '$user_id'")->fetch_assoc()['code'];
            if (isset($_POST['check_code'])) {
                if ($code != null) {
                    if (strlen($code) == 6) {
                        if ($code == $n) {
                            header("location:index.php?page=Recover_password&&user_id=$user_id&&n=$code&&u=new_pass");
                        }else {
                            box_alert('danger','خطأ ! الرمز المدخل لا يطابق رمزك');
                        }
                    }else{
                        box_alert('danger','يجب ان يكون الرمز 6 أرقام فقط');
                    }
                }else{
                    box_alert('danger','الرجاء ادخال الرمز');
                }
            }
        }
        function chek_pass()
        {
            $n = validation($_POST['n']);
            $user_id = validation($_POST['user_id']);
            $newpass = validation($_POST['newpass']);
            $re_newpass = validation($_POST['re_newpass']);
            $code = $this->connect()->query("SELECT code FROM users WHERE id = '$user_id'")->fetch_assoc()['code'];
            if ($newpass != null) {
                if (strlen($newpass) >= 6) {
                    if ($newpass == $re_newpass) {
                        if ($code == $n && $n != null){
                            $hash_pass = md5($newpass);
                            $sql = $this->connect()->query("UPDATE users SET password = '$hash_pass', code = 000000 WHERE id = '$user_id'");
                            if ($sql) {
                                echo '<input type="hidden" id="success" value="success" />';
                            }else {
                                box_alert('danger','حدث خطأ ولم يتم تعديل كلمة السر');
                            }
                        }else {
                            box_alert('danger','خطأ ! الرمز المدخل لا يطابق رمزك');
                        }
                    }else{
                        box_alert('danger','كلمتا السر غير متطابقتان');
                    }
                }else{
                    box_alert('danger'," كلمة المرور قصيرة !! يجب أن تكون كلمة المرور أكبر من 6 ");
                }
            }else{
                box_alert('danger','الرجاء ادخال كلمة السر الجديدة');
            }
        }   
    }
$recover = new Recover_password;
switch (validation($_GET['f'])) {
    case 'recover_newpass':
        $recover->chek_pass();
        break;
    }
?>