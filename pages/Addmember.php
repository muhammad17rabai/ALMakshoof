<?php
    if(!isset($_SESSION['id'])){
        header("location:index.php?page=login");
    }
    require_once './functions/signup_func.php';
?>
<!-- –––––––––––––––[ PAGE CONTENT ]––––––––––––––– -->
<main id="mainContent" class="main-content">
    <!-- Page Container -->
    <div class="page-container">
        <div class="container">
            <div class="row mt-15">
                <div class="header">
                    <ul class="nav nav-tabs justify-content-center" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=Allmembers" role="tab">
                                <i class="now-ui-icons objects_umbrella-13"></i> قائمة الأعضاء
                            </a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="index.php?page=Addmember" role="tab">
                                <i class="now-ui-icons objects_umbrella-13"></i> اضافة عضو جديد +
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="p-10 Addmember-tittle mt-15"><h6> اضافة عضو جديد </h6></div>
                <div class="form-add p-10">
                    <?php
                        $store_user = new store_user;
                        $store_user->adduser();
                    ?>
                    <form action="index.php?page=Addmember" method="post">
                        <div class="form-group name-info">
                            <label for="name">الاسم الكامل : </label>
                            <input type="text" class="form-control" name="name" placeholder="الاسم الكامل" required>
                        </div>
                        <div class="form-group email-info">
                            <label for="email"> البريد الالكتروني : </label>
                            <input type="email" class="form-control" name="email" placeholder="الايميل" required>
                        </div>
                        <div class="form-group phone-info">
                            <label for="phone"> رقم الجوال : </label>
                            <input type="text" class="form-control" name="phone" placeholder="رقم الجوال" required>
                        </div>
                        <div class="form-group">
                            <label class="password">كلمة المرور</label>
                            <input type="password" class="form-control input-lg" name="password" placeholder="كلمة السر" autocomplete="" required>
                        </div>
                        <div class="form-group city-info">
                            <label for="city">  المحافظة : </label>
                            <select name="city" class="form-control" required>
                                <option value=""> اختار المحافظة </option>
                                <option value="مناطق 48">مناطق 48</option>
                                <option value="القدس">القدس</option>
                                <option value="رام الله">رام الله</option>
                                <option value="نابلس">نابلس</option>
                                <option value="جنين">جنين</option>
                                <option value="طولكرم">طولكرم</option>
                                <option value="سلفيت">سلفيت</option>
                                <option value="قلقيلية">قلقيلية</option>
                                <option value="طوباس">طوباس</option>
                                <option value="أريحا">أريحا</option>
                                <option value="بيت لحم">بيت لحم</option>
                                <option value="الخليل">الخليل</option>
                            </select>
                        </div>
                        <div class="form-group city-info">
                            <label for="city">  المنطقة : </label>
                            <input type="text" class="form-control" name="address" placeholder="المنطقة" required>
                        </div>
                        <div class="form-group gender-info">
                            <label for="gender">  الجنس : </label>
                            <select name="gender" class="form-control" required>
                                <option value=""> اختار </option>
                                <option value="male"> ذكر </option>
                                <option value="femal"> أنثى </option>
                            </select>
                        </div>
                        <div class="form-group gender-info">
                            <label for="gender">  الصلاحية : </label>
                            <select name="role" class="form-control" required>
                                <option value=""> اختار </option>
                                <option value="member"> مستخدم عادي </option>
                                <option value="admin"> مسؤول نظام </option>
                            </select>
                        </div>
                        <input type="hidden" name="agree_terms" value="1">
                        <div class="form-group">
                            <button type="submit" class="btn btn-info" name="create_user"><i class="fa fa-plus"></i> اضافة </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Page Container -->
</main>
<!-- –––––––––––––––[ END PAGE CONTENT ]––––––––––––––– -->