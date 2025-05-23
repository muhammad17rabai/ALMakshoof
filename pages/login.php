<?php
    require_once './functions/login_func.php';
    if (isset($_SESSION['id'])) {
        header("location:index.php?page=home");
    }
?>
<!-- –––––––––––––––[ PAGE CONTENT ]––––––––––––––– -->
<main id="mainContent" class="main-content mt-20">
    <div class="page-container">
         <div class="header p-10">
            <h5 class="sign-title">تسجيل الدخول </h5>
         </div>
        <section class="sign-area p-10 pr-10 pl-10 form-add">
            <div class="form-group">
                <?php
                    $login = new signin;
                    $login->login();
                ?>
            </div>
            <div class="row">
                <div class="col-sm-12 col-right">
                    <form action="index.php?page=login" method="post">
                        <input type="hidden" name="finger" id="finger"/>
                        <div class="form-group">
                            <label><i class="fa fa-user lg text-info">&nbsp;</i>  الايميل أو رقم الهاتف </label>
                            <input type="text" class="form-control input-lg" name="username" placeholder="الايميل أو رقم الهاتف" value="<? echo $_POST['username'];?>" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fa fa-lock lg text-info">&nbsp;</i> كلمة المرور </label>
                            <input type="password" class="form-control input-lg" name="password" placeholder="كلمة السر" value="<? echo $_POST['password'];?>" required>
                        </div>
                        <div class="form-group">
                            <a href="index.php?page=Recover_password" class="forgot-pass-link text-info">نسيت كلمة المرور؟</a>
                        </div>
                        <button type="submit" class="btn btn-block btn-lg btn-info" name="login"> تسجيل الدخول </button>
                    </form>
                </div>
            </div>
        </section>
    </div>
</main>
