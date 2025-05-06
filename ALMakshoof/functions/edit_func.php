<?
class EDIT extends DB
{
    function showinfo_page()
    {
        $role = new role;
        $role->r("role");
        $sql = "SELECT * FROM pages WHERE id = '{$_GET['page_id']}'";
        $query = $this->connect()->query($sql);
        $queryimg = $this->connect()->query("SELECT * FROM page_images WHERE page_id = '{$_GET['page_id']}'");
        $fetch = $query->fetch_assoc();
        if($fetch['user_id'] != $_SESSION['id'] &&  $role->r("role") != 2){
            header("location:index.php?page=home"); 
        }
        switch ($fetch['status']) {
            case 0:
                $msg = '<li><i class="fa fa-exchange"></i>&nbsp;</li><span class="text-info"><i> قيد المراجعة </i></span>';
                break;
            case 1:
                $msg = '<li><i class="fa fa-check"></i>&nbsp;</li><span class="text-success"><i> تم النشر </i></span>';
                break;
            case 2:
                $msg = '<li><i class="fa fa-exclamation-triangle"></i>&nbsp;</li><span class="text-warning"><i> معلق </i></span>';
                break;
                case 3:
                $msg = '<li><i class="fa fa-close"></i>&nbsp;</li><span class="text-danger"><i>  مرفوض </i></span>';
                break;
            default:
                # code...
                break;
        }
        switch ($fetch['logo']) {
            case 'icons/face.png':
                $type = 'صفحة فيسبوك';
                break;
            case 'icons/Instagram.jpg':
                $type = 'صفحة انستقرام';
                break;
            case 'icons/store.jpg':
                $type = ' متجر الكتروني ';
                break;
            default:
                # code...
                break;
        }?>

        <div class="form-add p-15">
            <form action="index.php?page=Edit&&page_id=<? echo $_GET['page_id']?>&&S=<? echo $_GET['S']?>" method="post" enctype="multipart/form-data">
                <div class="form-group pagename">
                    <label for="name"> اسم الصفحة : </label>
                    <input type="text" class="form-control" name="pagename" value="<? echo $fetch['page_name'];?>">
                </div>
                <div class="form-group">
                    <label for="urlpage"> رابط الصفحة : </label>
                    <input type="text" class="form-control" name="pageurl" value="<? echo $fetch['page_url'];?>">
                </div>
                <div class="form-group">
                    <label for="type">  النوع : </label>
                    <select name="typepage" class="form-control">
                        <option value="<? echo $fetch['logo'];?>"> <? echo $type;?></option>
                        <option value="icons/face.png"> صفحة فيسبوك </option>
                        <option value="icons/Instagram.jpg"> صفحة انستقرام </option>
                        <option value="icons/store.jpg"> متجر الكتروني </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="category">  الفئة : </label>
                    <select name="cetegory" class="form-control" required>
                        <option value="<? echo $fetch['category'];?>"><? echo category($fetch['category'])?></option>
                        <option value="home_tools">أدوات منزلية</option>
                        <option value="clothes_shoes">ملابس وأحذية</option>
                        <option value="devices_electric">أجهزة كهربائية</option>
                        <option value="computer_mobile">حواسيب وهواتف</option>
                        <option value="elecetronics">الكترونيات عامة</option>
                        <option value="prefumes_accessories">عطور واكسسوارات</option>
                        <option value="health_beauty">صحة وجمال</option>
                        <option value="bags_prose">شنط ونثريات</option>
                        <option value="books">كتب ومطبوعات</option>
                        <option value="cleane">مواد تنظيف</option>
                        <option value="car_accessories">كماليات سيارات</option>
                        <option value="medicals">أدوات ومشدات طبية</option>
                        <option value="others">غير ذلك</option>
                    </select>
                </div>
                <div class="form-group edit-descreption">
                    <label for="descreption">  الوصف والملاحظات : </label>
                    <textarea name="descpage" class="form-control" cols="20" rows="7"><? echo $fetch['description'];?></textarea>
                </div>
                <div class="form-group">
                    <label for="rating">  التقييم : </label>
                    <div class="addrating">
                        <input type="hidden" name="rating" id="rating" value="<? echo $fetch['rate']; ?>">
                        <? show_rating($fetch['rate']); ?>
                    </div>
                </div>
                <label><b>  الصور والدلائل : </b></label>
                <div class="col-xs-12 form-group form-add-screenshot p-10">
                <?
                    $queryimg = $this->connect()->query("SELECT * FROM page_images WHERE page_id = '{$_GET['page_id']}'");
                    for ($j=0; $j<$queryimg->num_rows;$j++) {
                    $fetchimg = $queryimg->fetch_assoc();
                    echo '
                    <div class="col-xs-3">
                        <a href="#zoom-img" data-toggle="modal"><img src="./images/'.$fetchimg['image'].'" class="myImg myImg_'.+$j.' cc" width="45" data-img="#myModal-img'.$fetch['id'].'" style="width: 80px; height: auto;"></a>
                        <input type="text" name="img_id[]" value="'.$fetchimg['id'].'">
                    </div>
                    ';
                }?> 
                </div>
                <input type="hidden" name="c_img" id="c_img">
                <div class="form-group form-add-screenshot p-10" id="form-add-screenshot">
                    <input type="file" class="form-control imgpage" name="img_name[]" multiple>
                    <span> تعديل الصور <i class="fa fa-upload"></i></span><br>
                </div>
                <div class="form-group mt-10">
                    <button type="submit" class="btn btn-info" name="save_page"> حفظ </button>
                </div>
            </form>
        </div>
        <!--============================== modal zoom img ===========================-->
        <div id="myModal-img<? echo $fetch['id'];?>" class="modal-img m-0">
            <a class="close close-img" data-dissmision="modal" aria-hidden="true"><b>x</b></a>
            <img class="modal-content-img img01">
        </div>
        <!--============================== End modal zoom img ===========================-->
    <?
    }
     function edit_pagereq()
    {
        $user_id = validation($_SESSION['id']);
        $pagename = validation($_POST['pagename']);
        $pageurl = validation($_POST['pageurl']);
        $category = validation($_POST['cetegory']);
        $logo = validation($_POST['typepage']);
        $descpage = validation($_POST['descpage']);
        if(validation($_POST['rating']) > 5){
            $rating = validation($_POST['rating']) - 5;
        }else{
            $rating = validation($_POST['rating']);
        }
        $rows_url = 0;
        $imagename = $_FILES['img_name']['name'];
        $imagesize = $_FILES['img_name']['size'];
        $page_id = validation($_GET['page_id']);
    
        $val = validate_page($pagename,$pageurl,$rows_url,$category,$descpage,$logo,$rating);
        $val_img = validate_img($imagename , $imagesize , 3 , 8);
        $cc = count($imagename);
        $rows = $this->connect()->query("SELECT * FROM page_images WHERE page_id = '{$_GET['page_id']}'")->num_rows;

        if (isset($_POST['save_page'])) {
            if(empty($val)){
                if($_POST['c_img'] < 1){
                    $sql = "UPDATE pages SET page_name='$pagename', page_url='$pageurl',category='$category', description='$descpage', logo='$logo', rate='$rating', 
                    status=0 WHERE id = '$page_id'";
                    $query = $this->connect()->query($sql);
                if($query){
                    $m = new Notifications;
                    $m->save_notification($page_id,$user_id,'admin','تم تعديل بيانات الصفحة','secondary',0);
                    header("location:index.php?page=notic_details&&page_id=".$page_id."&&S=page_req");
                }
                }elseif(empty($val_img)){

                    $sql = "UPDATE pages SET page_name='$pagename', page_url='$pageurl',category='$category', description='$descpage', logo='$logo', rate='$rating', 
                    status=0 WHERE id = '$page_id'";
                    $query = $this->connect()->query($sql);
                    if ($query) {
                        $m = new Notifications;
                        $m->save_notification($page_id,$user_id,'admin','تم تعديل بيانات الصفحة','secondary',0);
                    }
                    if($cc <= $rows){
                        for ($i=0; $i < $cc; $i++) { 
                            $imagename = $_FILES['img_name']['name'][$i];                            
                            $tmp = $_FILES['img_name']['tmp_name'][$i];
                            $newimgname = rand(1000, 10000) . $imagename;
                            $img_id = $_POST['img_id'][$i];

                            $queryimg = $this->connect()->query("UPDATE page_images SET image='$newimgname' WHERE page_id = '$page_id' && id = '$img_id'");
                            move_uploaded_file($tmp,'./images/'.$newimgname);
                        }

                    }else{
                        for ($i=0; $i < $cc-$rows; $i++) { 

                            for ($j=0; $j < $rows; $j++) { 
                                $imagename = $_FILES['img_name']['name'][$j];                            
                                $tmp = $_FILES['img_name']['tmp_name'][$j];
                                $newimgname = rand(1000, 10000) . $imagename;
                                $img_id = $_POST['img_id'][$j];
    
                                $queryimg = $this->connect()->query("UPDATE page_images SET image='$newimgname' WHERE page_id = '$page_id' && id = '$img_id'");
                                move_uploaded_file($tmp,'./images/'.$newimgname);
                            }

                            $imagename = $_FILES['img_name']['name'][$i];                            
                            $tmp = $_FILES['img_name']['tmp_name'][$i];
                            $newimgname = rand(1000, 10000) . $imagename;
                            //$img_id = $_POST['img_id'][$j];
                            $sqlimg = $this->connect()->query("INSERT INTO page_images(page_id,image)VALUES('$page_id','$newimgname')");
                            move_uploaded_file($tmp,'./images/'.$newimgname);
                        }
                    }
                    
                if($query && $queryimg){
                    header("location:index.php?page=notic_details&&page_id=".$page_id."&&S=page_req");
                }
                }else{
                    box_alert('danger',$val_img);
                }
            }else{
                box_alert('danger',$val);
            }
        }
    ?>
<?    
    }
////////////////////////////////////////// products ////////////////////////////////////////////////
    function showinfo_product()
    {
        $role = new role;
        $role->r("role");
        $product_id = validation($_GET['product_id']);
        $sql = "SELECT * FROM products WHERE id = '$product_id'";
        $query = $this->connect()->query($sql);
        $fetch = $query->fetch_assoc();
        if($fetch['user_id'] != $_SESSION['id'] &&  $role->r("role") != 2){
            header("location:index.php?page=home"); 
        }
        switch ($fetch['status']) {
            case 0:
                $msg = '<li><i class="fa fa-exchange"></i>&nbsp;</li><span class="text-info"><i> قيد المراجعة </i></span>';
                break;
            case 1:
                $msg = '<li><i class="fa fa-check"></i>&nbsp;</li><span class="text-success"><i> تم النشر </i></span>';
                break;
            case 2:
                $msg = '<li><i class="fa fa-exclamation-triangle"></i>&nbsp;</li><span class="text-warning"><i> معلق </i></span>';
                break;
                case 3:
                $msg = '<li><i class="fa fa-close"></i>&nbsp;</li><span class="text-danger"><i>  مرفوض </i></span>';
                break;
            default:
                # code...
                break;
        }?>
        <div class="form-add p-10 m-5">
            <form action="index.php?page=Edit&&product_id=<? echo $product_id;?>&&S=edit_product" method="post" enctype="multipart/form-data">
                <div class="form-group name">
                    <label for="name">اسم المنتج  : </label>
                    <input type="text" class="form-control" name="name" placeholder=" اسم المنتج " value="<? echo $fetch['name'];?>">
                </div>
                <div class="form-group">
                    <label for="urlpage">رابط مصدر المنتج  : </label>
                    <input type="text" class="form-control" name="url" placeholder=" الرابط " value="<? echo $fetch['source'];?>">
                </div>
                <div class="form-group">
                    <label for="price"> السعر التقريبي للمنتج : </label>
                    <input type="text" class="form-control" name="price" placeholder=" سعر المنتج " value="<? echo $fetch['price'];?>">
                </div>
                <div class="form-group descreption">
                    <label for="descreption">  الوصف والملاحظات : </label>
                    <textarea name="descreption" class="form-control" cols="20" rows="7" placeholder="تقاصيل المنتج ..." ><? echo $fetch['descreption'];?></textarea>
                </div>
                <div class="form-group border p-10">
                    <div class="form-group">
                        <label for="rating">  التقييم : </label>
                        <div class="addrating">
                            <input type="hidden" name="rating" id="rating" value="<? echo $fetch['rating']; ?>">
                            <?
                                show_rating($fetch['rating']);
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="uses"> هل تنصح باستخدام المنتج : </label>
                        <?
                            if ($fetch['uses'] == 1) {
                                $check_y = "checked";
                            }else{
                                $check_n = "checked";
                            }
                        ?>
                        <div class="col-xs-12">
                            <div class="col-xs-6 form-check">
                                <input class="form-check-input" type="radio" name="uses" value="1" <? echo $check_y;?>>
                                &nbsp;<label class="form-check-label mr-15" for="flexRadioDefault1">
                                    نعم
                                </label>
                            </div>
                            <div class="col-xs-6 form-check">
                                <input class="form-check-input" type="radio" name="uses" value="0" <? echo $check_n;?>>
                                &nbsp;<label class="form-check-label mr-15" for="flexRadioDefault2">
                                    لا  
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border p-10">
                    <div class="form-group p-10">
                        <label><b> الصورة الرئيسية للمنتج : </b> / <span class="text-info">اجباري</span></label>
                        <input type="file" class="form-control" name="main_img">
                        <img src="./images/products_img/<? echo $fetch['main_img'];?>" class="myImg m-5 mt-10" data-img="#myModal-img<? echo $fetch['id'];?>" width="45">
                    </div><hr>
                    <label><b>   صور أخرى للمنتج : </b></label>
                    <div class="form-group form-add-screenshot p-10" id="form-add-screenshot">
                    <?
                        $queryimg = $this->connect()->query("SELECT * FROM products_images WHERE product_id = '{$_GET['product_id']}'");
                        for ($j=0; $j<$queryimg->num_rows;$j++) {
                        $fetchimg = $queryimg->fetch_assoc();
                        echo '
                        <div class="col-xs-3">
                            <a><img src="./images/products_img/'.$fetchimg['image'].'"class="myImg cc" width="45" data-img="#myModal-img'.$fetch['id'].'"></a>
                            <input type="text" name="img_id[]" value="'.$fetchimg['id'].'">
                        </div>
                        ';
                    }?>
                    <div>
                        <input type="file" class="form-control imgpage" name="allimg_product[]" multiple>
                        <span> اختيار الصور <i class="fa fa-upload"></i></span><br> 
                    </div>  
                </div>
            </div>

                <div class="form-group mt-10">
                    <button type="submit" class="btn btn-info" name="save_edit_products"> حفظ </button>
                </div>
            </form>
        </div>
        <!--============================== modal zoom img ===========================-->
        <div id="myModal-img<? echo $fetch['id'];?>" class="modal-img m-0">
            <a class="close close-img" data-dissmision="modal" aria-hidden="true"><b>x</b></a>
            <img class="modal-content-img img01">
        </div>
        <!--============================== End modal zoom img ===========================-->
    <?       
    }
    function edit_product_req()
    {
        $user_id = $_SESSION['id'];
        $product_id = validation($_GET['product_id']);
        $name = validation($_POST['name']);
        $url = validation($_POST['url']);
        $price = validation($_POST['price']);
        $descreption = validation($_POST['descreption']);
        $rating = validation($_POST['rating']);
        $uses = validation($_POST['uses']);

        $main_img_name = $_FILES['main_img']['name'];
        $main_img_tmp = $_FILES['main_img']['tmp_name'];
        $main_img_size = $_FILES['main_img']['size'];

        $all_img_name = $_FILES['allimg_product']['name'];
        $all_img_size = $_FILES['allimg_product']['size'];

        $cc = count($_FILES['main_img']['name']);
        $ccc = count($_FILES['allimg_product']['name']);
        $rows = $this->connect()->query("SELECT * FROM products_images WHERE product_id = '$product_id'")->num_rows;
    
        $val = validate_products($name,$url,$price,$descreption,$rating,$uses);
        $val_mainimg = validate_img($main_img_name , $main_img_size , 0 , 1);
        $val_allimg = validate_img($all_img_name,$all_img_size,0,7);

        if (isset($_POST['save_edit_products'])) {
            if(empty($val)){
                if($main_img_name == null){
                    $sql = "UPDATE products SET name='$name', source='$url', rating='$rating', price='$price', descreption='$descreption', uses='$uses' WHERE id = '$product_id'";
                    $query = $this->connect()->query($sql);
                if($query){
                    header("location:index.php?page=notic_details&&product_id=".$product_id."&&S=product_req");
                }
                }elseif(empty($val_mainimg)){
                    $new_mainimgname = rand(1000, 10000).$main_img_name;
                    $sql = "UPDATE products SET name='$name', source='$url', rating='$rating', price='$price', descreption='$descreption', main_img='$new_mainimgname', uses='$uses' WHERE id = '$product_id'";
                    $query = $this->connect()->query($sql);
                    move_uploaded_file($main_img_tmp,'./images/products_img/'.$new_mainimgname);
                }else{
                        box_alert('danger',$val_mainimg);
                    }
                if($_FILES['allimg_product']['name'][0] != null) {
                    if (empty($val_allimg)){
                        if($ccc <= $rows){
                            for ($i=0; $i < $ccc; $i++) { 
                                $all_img_name = $_FILES['allimg_product']['name'][$i];
                                $all_img_tmp = $_FILES['allimg_product']['tmp_name'][$i];
                                $all_img_size = $_FILES['allimg_product']['size'][$i];
                           
                                $new_allimgname = rand(1000, 10000).$all_img_name;
                                $img_id = validation($_POST['img_id'][$i]);
                                $queryimg = $this->connect()->query("UPDATE products_images SET image='$new_allimgname' WHERE product_id = '$product_id' && id = '$img_id'");
                                move_uploaded_file($all_img_tmp,'./images/products_img/'.$new_allimgname);
                            }
                        }else{
                            for ($i=0; $i < $ccc-$rows; $i++) { 
                                for ($j=0; $j < $rows; $j++) { 
                                    $all_img_name = $_FILES['allimg_product']['name'][$i];
                                    $all_img_tmp = $_FILES['allimg_product']['tmp_name'][$i];
                                    $all_img_size = $_FILES['allimg_product']['size'][$i];
                                    $val_allimg = validate_img($all_img_name,$all_img_size,0,(8-$rows));
                                    $new_allimgname = rand(1000, 10000).$all_img_name;
                                    $img_id = validation($_POST['img_id'][$i]);
                                    $queryimg = $this->connect()->query("UPDATE products_images SET image='$new_allimgname' WHERE product_id = '$product_id' && id = '$img_id'");
                                    move_uploaded_file($all_img_tmp,'./images/products_img/'.$new_allimgname);
                                    }
                                }
                                $all_img_name = $_FILES['allimg_product']['name'][$i];
                                $all_img_tmp = $_FILES['allimg_product']['tmp_name'][$i];
                                $all_img_size = $_FILES['allimg_product']['size'][$i];
                                $val_allimg = validate_img($all_img_name,$all_img_size,0,(8-$rows));
                                $new_allimgname = rand(1000, 10000).$all_img_name;
                                $sqlimg = $this->connect()->query("INSERT INTO products_images(product_id,image)VALUES('$product_id','$new_allimgname')");
                                move_uploaded_file($all_img_tmp,'./images/'.$new_allimgname);
                            }
                        }else{
                            box_alert('danger',$val_allimg);
                        }
                        
                    if($query && $queryimg){
                        header("location:index.php?page=notic_details&&product_id=".$product_id."&&S=product_req");
                    }
                }
            }else{
                box_alert('danger',$val);
            }        
        }  
    }
}
?>