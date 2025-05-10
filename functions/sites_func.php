<?
require_once 'functions.php';
class Sites extends DB
{
    function addsites()
    {
        $name_ar = validation($_POST['name_ar']);
        $name_en = validation($_POST['name_en']);
        $url = validation($_POST['url']);
        $descreption = validation($_POST['descreption']);
        $new_url = explode('?',$url)[0];
        $date = time();
        $status = validation($_POST['status']);
        $rows_url = $this->connect()->query("SELECT * FROM sites WHERE url = '$new_url' && active = 1")->num_rows;
        $logo_name = $_FILES['logo']['name'];
        $logo_tmp = $_FILES['logo']['tmp_name'];
        $logo_size = $_FILES['logo']['size'];
        $val = validate_sites($name_ar,$name_en,$url,$descreption,$status,$rows_url);
        $val_logo = validate_img($logo_name,$logo_size,1,1);
        if (isset($_POST['addsites'])) {
            if(empty($val)){
                if(empty($val_logo)){
                    $new_logoname = rand(1000, 10000).$logo_name;
                    $sql = "INSERT INTO sites(name_ar,name_en,url,descreption,logo,date_created,active) 
                    VALUES('$name_ar','$name_en','$new_url','$descreption','$new_logoname','$date','$status')";
                    $query = $this->connect()->query($sql);
                    if($query){
                        move_uploaded_file($logo_tmp, './icons/'.$new_logoname);
                        box_alert('success','تم اضافة المتجر بنجاح');
                    }else {
                        box_alert('danger','خطأ لم يتم اضافة المتجر');
                    }
                }else {
                    box_alert('danger',$val_logo);
                }
            }else {
                box_alert('danger',$val);
            }
        }
    }
    function getsites()
    {
        $role = new role;
        if ($role->r('role') == 2) {
            $sql = $this->connect()->query("SELECT * FROM sites ORDER BY date_created DESC");
        }else{
            $sql = $this->connect()->query("SELECT * FROM sites WHERE active = 1 ORDER BY date_created DESC");
        }
        $rows = $sql->num_rows;
    ?>
        <div class="col-xs-12 p-10 mt-5">
            <h5 class="h-title t-uppercase"> المتاجر (<? echo $rows;?>)</h5>
        </div>
    <?
        while ($fetch = $sql->fetch_assoc()) {
    ?>
        <main>
            <div class="mt-5 t-center">
                <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
                    <a href="index.php?page=Sites_details&&site_id=<? echo $fetch['id'];?>" class="panel is-block mt-15" style="border-radius:10px;">
                        <div class="embed-responsive embed-responsive-4by3">
                            <div class="store-logo">
                                <img src="./icons/<? echo $fetch['logo'];?>" class="p-10" width="90" height="">
                            </div>
                        </div>
                        <label class="store-name pb-5"><? echo $fetch['name_en']; echo"</br>({$fetch['name_ar']})";?></label>
                    </a>
                </div>
            </div>
        </main>
    <?
        }
    }
    function sites_details()
    {
        $role = new role;
        $id = validation($_GET['site_id']);
        $sql = $this->connect()->query("SELECT * FROM sites WHERE id = '$id'");
        $fetch = $sql->fetch_assoc();
        switch ($fetch['active']) {
            case '0':
                $st = 'تعطيل';
                  break;
            case '1':
              $st = 'تنشيط';
                break;
        }
    ?>
    <div class="col-xs-12 p-10 mt-15 form-add">
        <div id="result"></div>
        <div class="col-xs-12 text-center">
            <img src="./icons/<? echo $fetch['logo'];?>" width="60">
        </div>
        <div class="col-xs-12 mt-20">
            <h6><b> اسم المتجر (AR)  : </b><i class="alert-warning"><? echo $fetch['name_ar'];?></i></h6><hr>
            <h6><b> اسم المتجر (EN)  : </b><i class="alert-warning"><? echo $fetch['name_en'];?></i></h6><hr>
            <h6><a href="<? echo $fetch['url'];?>" target="blanek"><b>  الذهاب للمتجر >> </b></a></h6><hr>
            <div class="col-xs-12">
                <h6> الملاحظات والتفاصيل :  </h6>
                <p class="text-muted mb-20 mt-10"><span><? echo $fetch['descreption'];?></span></p>
            </div>
            <?
            if ($role->r('role') == 2) {?>
                <div class="form-group">
                    <a href="#edit_sites<? echo $fetch['id'];?>" data-toggle="modal"><i class="fa fa-edit text-info"></i> تعديل </a>
                    <a href="#delete_sites<? echo $fetch['id'];?>" class="mr-15" data-toggle="modal"><i class="fa fa-trash text-danger"></i> حذف </a>
                </div><hr>
            <?}?>
        </div>
    </div>
    <!----------------------------------------- Modal Edit  --------------------------------------->
    <!-- Modal details -->
    <div class="modal text-center" id="edit_sites<? echo $fetch['id'];?>" tabindex="-1" role="dialog">
        <div class="form-add">
            <div class="modal-content text-right p-10">
                <div class="modal-header">
                    <button type="button" class="clos" data-dismiss="modal" aria-hidden="true"><b>x</b></button>
                    <h5> تعديل بيانات المتجر </h5>
                </div>
                <form method="post" action="index.php?page=Sites_details&&site_id=<? echo $fetch['id'];?>" enctype="multipart/form-data">
                    <div class="form-group name_ar">
                        <label for="name">اسم المتجر (AR)  : </label>
                        <input type="text" class="form-control" name="name_ar" placeholder=" اسم المتجر باللغة العربية" value="<? echo $fetch['name_ar'];?>">
                    </div>
                    <div class="form-group name_en">
                        <label for="name">اسم المتجر (EN)  : </label>
                        <input type="text" class="form-control" name="name_en" placeholder=" اسم المتجر باللغة الانجليزية" value="<? echo $fetch['name_en'];?>">
                    </div>
                    <div class="form-group url">
                        <label for="urlpage">رابط المتجر  : </label>
                        <input type="text" class="form-control" name="url" placeholder=" الرابط " value="<? echo $fetch['url'];?>">
                    </div>
                    <div class="form-group descreption">
                        <label for="descreption">  الوصف : </label>
                        <textarea name="descreption" class="form-control" cols="20" rows="7" placeholder=" وصف المتجر ..." ><? echo $fetch['descreption'];?></textarea>
                    </div>
                    <div class="form-group status">
                        <label for="status"> الحالة : </label>
                        <select class="form-control" name="status" id="status">
                            <option value="<? echo $fetch['active'];?>"><? echo $st;?></option>
                            <option value="1">تنشيط</option>
                            <option value="0">الغاء التنشيط</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><b> شعار المتجر : </b></label>&nbsp;&nbsp;<img src="./icons/<? echo $fetch['logo'];?>" width="25">
                        <input type="file" class="form-control" name="logo">
                        <input type="hidden" name="logo" value="<? echo $fetch['logo'];?>">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info" name="save_editsites">حفظ</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">اغلاق</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal details-->
    <!-------------------------------------- End Edit Modal ---------------------------------------------->
    <!----------------------------------------- Modal Edit  --------------------------------------->
    <!-- Modal details -->
    <div class="modal text-center" id="delete_sites<? echo $fetch['id'];?>" tabindex="-1" role="dialog">
        <div class="form-add">
            <div class="modal-content text-right p-10">
                <div class="modal-header">
                    <button type="button" class="clos" data-dismiss="modal" aria-hidden="true"><b>x</b></button>
                    <h5>  حذف المتجر </h5>
                    <div id="result_del"></div>

                </div>

                    <h5 class="p-10"> هل أنت متاكد من حذف هذا المتجر </h5>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="delete_sites" data-id="<? echo $fetch['id'];?>">حذف</button>
                    <button type="button" class="btn btn-info" data-dismiss="modal">اغلاق</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal details-->
    <!-------------------------------------- End Edit Modal ---------------------------------------------->
<?
}
    function editsites()
    {
        $id = validation($_GET['site_id']);
        $name_ar = validation($_POST['name_ar']);
        $name_en = validation($_POST['name_en']);
        $url = validation($_POST['url']);
        $descreption = validation($_POST['descreption']);
        $new_url = explode('?',$url)[0];
        $status = validation($_POST['status']);
        $rows_url = 0;
        $logo_name = $_FILES['logo']['name'];
        $logo_tmp = $_FILES['logo']['tmp_name'];
        $logo_size = $_FILES['logo']['size'];
        if ($logo_name == null) {
            $new_logoname = validation($_POST['logo']);
        }else{
            $new_logoname = rand(1000, 10000).$logo_name;
        }
        $val = validate_sites($name_ar,$name_en,$url,$descreption,$status,$rows_url);
        $val_logo = validate_img($logo_name,$logo_size,0,1);
        if (isset($_POST['save_editsites'])) {
            if(empty($val)){
                if(empty($val_logo)){
                    $sql = "UPDATE sites SET name_ar='$name_ar',name_en='$name_en', url='$new_url', descreption='$descreption', logo='$new_logoname', active='$status' WHERE id = '$id'";
                    $query = $this->connect()->query($sql);
                    if($query){
                        move_uploaded_file($logo_tmp, './icons/'.$new_logoname);
                        box_alert('success','تم تعديل بيانات المتجر بنجاح');
                    }else {
                        box_alert('danger','خطأ لم يتم تعديل بيانات المتجر');
                    }
                }else {
                    box_alert('danger',$val_logo);
                }
            }else {
                box_alert('danger',$val);
            }
        }
    }
    function delete_site()
    {
        $id = validation($_POST['id']);
        $sql = $this->connect()->query("DELETE FROM sites WHERE id = '$id'");
        if ($sql) {
            echo '<input type="hidden" id="success" value="success">';
        }else{
            echo '<input type="hidden" id="success" value="error">';
        }
    }
}
$sites = new Sites;
switch (validation($_GET['f'])) {
    case 'del':
        $sites->delete_site();
        break;
}
?>