<?
if(!isset($_SESSION['id'])){
    header("location:index.php?page=login");
}
require_once './functions/sites_func.php';
$role = new role;
?>
<!-- –––––––––––––––[ PAGE CONTENT ]––––––––––––––– -->
<div id="mainContent" class="main-content">
    <div class="headers mt-20 m-5">
        <ul class="nav nav-tabs justify-content-center" role="tablist">
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=Allsites">
                    <i class="now-ui-icons objects_umbrella-13 fa fa-bank"></i>  قائمة المتاجر
                </a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="index.php?page=Addsites">
                    <i class="now-ui-icons objects_umbrella-13 fa fa-plus-circle"></i> اضافة متجر جديد 
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- Page Container -->
<div class="page-container">
    <div class="container">
        <?php
            $addsites = new Sites;
            $addsites->addsites();
        ?>
        <div class="row mt-10">
            <!--<div class="header p-10 mr-5 ml-5 text-white"><h5> منتج جديد </h5></div>-->
            <div class="form-add p-10 m-5">
                <form action="index.php?page=Addsites" method="post" enctype="multipart/form-data">
                    <div class="form-group name_ar">
                        <label for="name">اسم المتجر (AR)  : </label>
                        <input type="text" class="form-control" name="name_ar" placeholder=" اسم المتجر باللغة العربية" value="<? echo $_POST['name_ar'];?>">
                    </div>
                    <div class="form-group name_en">
                        <label for="name">اسم المتجر (EN)  : </label>
                        <input type="text" class="form-control" name="name_en" placeholder=" اسم المتجر باللغة الانجليزية" value="<? echo $_POST['name_en'];?>">
                    </div>
                    <div class="form-group url">
                        <label for="urlpage">رابط المتجر  : </label>
                        <input type="text" class="form-control" name="url" placeholder=" الرابط " value="<? echo $_POST['url'];?>">
                    </div>
                    <div class="form-group descreption">
                        <label for="descreption">  الوصف : </label>
                        <textarea name="descreption" class="form-control" cols="20" rows="7" placeholder=" وصف المتجر ..." ><? echo $_POST['descreption'];?></textarea>
                    </div>
                    <div class="form-group status">
                        <label for="status"> الحالة : </label>
                        <select class="form-control" name="status" id="status">
                            <option value="<? echo $_POST['status'];?>"></option>
                            <option value="1">تنشيط</option>
                            <option value="0">الغاء التنشيط</option>
                        </select>
                    </div>
                    <div class="form-group logo">
                        <label><b> شعار المتجر : </b></label>
                        <input type="file" class="form-control" name="logo">
                    </div>

                    <div class="form-group mt-10">
                        <button type="submit" class="btn btn-info" name="addsites"> اضافة <i class="fa fa-plus"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Page Container -->