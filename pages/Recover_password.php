<?php
    require_once './functions/recover_password_func.php';
?>
<!-- –––––––––––––––[ PAGE CONTENT ]––––––––––––––– -->
<?
$recover = new Recover_password;
class recovory extends DB
{
    function get_users($recover)
{?>
    <main id="mainContent" class="main-content mt-20 m-2">
        <div class="page-container">
            <div class="header p-10">
                <h5 class="sign-title"><i class="fa fa-key">&nbsp;&nbsp;</i> اعادة تعيين كلمة السر </h5>
            </div>
            <section class="sign-area p-10 pr-10 pl-10 form-add">
                <div class="form-group">
                    <?php
                        $recover->check_user();
                    ?>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-md-7">
                        <form action="index.php?page=Recover_password" method="post">
                            <div class="form-group">
                                <label><i class="fa fa-locks lg text-info">&nbsp;</i> أدخل الايميل أو رقم الهاتف المرتبط بحسابك</label>
                                <input type="text" class="form-control input-lg" name="username" placeholder="الايميل أو رقم الهاتف" value="<? echo $_POST['username'];?>">
                            </div>
                            <button type="submit" class="btn btn-block btn-lg btn-info" name="send_code"> ارسال رمز الأمان </button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </main>
<?
    }
    function get_code($recover)
    {
        $email = $this->connect()->query("SELECT email FROM users WHERE id = '{$_GET['user_id']}'")->fetch_assoc()['email'];
        $e = explode('@',$email);
        $end = end($e);
        $user_id = validation($_GET['user_id']);

    ?>
        <main id="mainContent" class="main-content mt-20 m-2">
            <div class="page-container">
                <div class="header p-10">
                    <h5 class="sign-title"> رمز الامان </h5>
                </div>
                <section class="sign-area p-10 pr-10 pl-10 form-add">
                    <div class="form-group">
                        <?php
                            $recover->check_code();
                        ?>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-7">
                            <form action="index.php?page=Recover_password&&user_id=<? echo $user_id ;?>&&u=check_code" method="post">
                                <div class="form-group">
                                    <label> أدخل الرمز المكون من 6 أرقام الذي تم ارساله الى ايميل </label><br>
                                    <label><? echo substr($email,0,3).'*****@'.$end;?></label>
                                    <input type="text" class="form-control input-lg" name="code" placeholder="######" value="<? echo $_POST['code'];?>">
                                </div>
                                <button type="submit" class="btn btn-block btn-lg btn-info" name="check_code"> التحقق من الرمز </button>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    <?
    }
    function get_newpass()
    {
        $user_id = validation($_GET['user_id']);
        $n = validation($_GET['n']);
    ?>
        <main id="mainContent" class="main-content mt-20 m-2">
            <div class="p-10 header mt-15"><h6>  كلمة السر الجديدة</h6></div>
            <div class="col-xs-12 form-add">
                <div class="text-center" id="result_recoverpassword"></div>
                <div class="form-group mt-15">
                    <h5> كلمة السر الجديدة : <input type="password" id="newpass" class="form-control" placeholder="كلمة السر الجديدة" /></h5>
                </div>
                <div class="form-group">
                    <h5>  تأكيد كلمة السر الجديدة : <input type="password" id="re_newpass" class="form-control" placeholder=" تأكيد كلمة السر الجديدة" /></h5>
                </div>
                <div class="form-group btn-save-newpassword">
                    <button type="submit" class="btn btn-info btn-edit-newpass" id="save_newpass_recover" data-id="<? echo $user_id;?>" data-n="<? echo $n;?>">حفظ</button>
                </div>
            </div>
        </main>
    <?
    }
}

$recovery = new recovory;
$u = validation($_GET['u']);
switch ($u) {
    case 'check_code':
        $recovery->get_code($recover);
        break;
    case 'new_pass':
        $recovery->get_newpass($recover);
        break;
    default:
        $recovery->get_users($recover);
        break;
}
?>