<?
    $role = new role;
    if(!isset($_SESSION['id'])){
            header("location:index.php?page=login");
        }
        require_once './functions/myprofile_func.php';
?>
<div class="container">
    <div class="row">
        <form action="index.php?page=password" method="post">
            <div class="header mt-20">
                <ul class="nav nav-tabs pr-5">
                    <li class="nav-item title-maininfo">
                        <a href="index.php?page=Myprofile" class="nav-link">المعلومات الشخصية</a>
                    </li>
                    <li class="nav-item title-editpass active">
                        <a href="index.php?page=password" class="nav-link"> تغيير كلمة السر </a>
                    </li>
                </ul>
            </div>
            <!----------------------------------------- edit password box --------------------------------->
            <div class="text-center" id="result-pass"></div>
            <div class="p-10 header-cart mt-15"><h6> تعديل كلمة السر </h6></div>
                <div class="col-xs-12 p-20 form-add">
                    <div class="form-group oldpass">
                        <h5> كلمة السر القديمة : <input type="password" id="oldpass" class="form-control" placeholder="كلمة السر القديمة" required/></h5>
                    </div>
                    <div class="form-group main-email">
                        <h5> كلمة السر الجديدة : <input type="password" id="newpass" class="form-control" placeholder="كلمة السر الجديدة" required/></h5>
                    </div>
                    <div class="form-group main-phone">
                        <h5>  تأكيد كلمة السر الجديدة : <input type="password" id="re_newpass" class="form-control" placeholder=" تأكيد كلمة السر الجديدة" required/></h5>
                    </div>
                    <div class="form-group btn-save-newpassword">
                        <button type="button" class="btn btn-info btn-edit-newpass" id="btn_savenewpass" data-id="<? echo $_SESSION['id'];?>">حفظ</button>
                    </div>
                </div>
        </form>
    </div>
</div>