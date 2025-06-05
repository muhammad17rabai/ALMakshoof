<?php
    require_once './functions/signup_func.php';
    if (isset($_SESSION['id'])) {
        header("location:index.php?page=home");
    }
?>
<!-- –––––––––––––––[ PAGE CONTENT ]––––––––––––––– -->
<main id="mainContent" class="main-content mt-20">
    <div class="page-container">
         <div class="header p-10">
            <h5 class="sign-title"> انشاء حساب جديد </h5>
         </div>
         <section class="sign-area panel p-10 pr-10 pl-10 form-add">
            <div class="form-group">
            <?php
                $store_user = new store_user;
                $store_user->adduser();
            ?>
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-12 col-right">
                    <form action="index.php?page=signup" method="post">
                        <div class="form-group">
                            <label class="name"><i class="fa fa-user lg">&nbsp;</i>  الاسم الكامل </label>
                            <input type="text" class="form-control input-lg" name="name" placeholder="الاسم الكامل" required>
                        </div>
                        <div class="form-group">
                            <label class="email"><i class="fa fa-envelope lg">&nbsp;</i> البريد الإلكتروني</label>
                            <input type="text" class="form-control input-lg" name="email" placeholder="الايميل" required>
                        </div>
                        <div class="form-group">
                            <label class="phone"><i class="fa fa-mobile lg">&nbsp;</i> رقم الجوال</label>
                            <input type="text" class="form-control input-lg" name="phone" placeholder="رقم الجوال" required>
                        </div>
                        <div class="form-group">
                            <label class="password"><i class="fa fa-lock lg">&nbsp;</i> كلمة المرور</label>
                            <input type="password" class="form-control input-lg" name="password" placeholder="كلمة السر" required>
                        </div>
                        <div class="form-group city-info">
                            <label for="city"><i class="fa fa-map-marker lg">&nbsp;</i>  المحافظة : </label>
                            <select name="city" class="form-control" required>
                                <option> اختار المحافظة </option>
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
                            <label for="address"><i class="fa fa-map-pin lg">&nbsp;</i> المنطقة : </label>
                            <input type="text" class="form-control" name="address" placeholder="المنطقة" required>
                        </div>
                        <div class="form-group gender-info">
                            <label for="gender"><i class="fa fa-venus lg">&nbsp;</i>  الجنس : </label>
                            <select name="gender" class="form-control" required>
                                <option value=""> اختار </option>
                                <option value="male"> ذكر </option>
                                <option value="femal"> أنثى </option>
                            </select>
                        </div>
                        <div class="custom-checkbox mb-20">
                            <input type="checkbox" id="agree_terms" name="agree_terms" required>
                            <label class="color-mid" for="agree_terms">أنا أوافق على <a href="index.php?page=Polices_terms" class="text-info"> تعليمات الاستخدام و شروط الخصوصية </a></a>.</label>
                        </div>
                        <button type="submit" class="btn btn-block btn-lg btn-info" name="create_user"> انشاء حساب </button>
                    </form>
                </div>
            </div>
        </section>
    </div>
</main>
<!-- –––––––––––––––[ END PAGE CONTENT ]––––––––––––––– -->