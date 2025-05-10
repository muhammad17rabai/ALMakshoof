<?
require_once 'functions.php';
class Brokers extends DB
{
    function getbrokers_list()
    {
        $role = new role;
        $check_sub = new info;
        $cc = $check_sub->check_subscribe();

        if ($role->r('role') == 2) {
            $sql = $this->connect()->query("SELECT u.*, b.* FROM brokers as b LEFT JOIN users as u ON b.user_id = u.id ORDER BY b.id DESC");
        }else{
            $sql = $this->connect()->query("SELECT u.*, b.* FROM brokers as b LEFT JOIN users as u ON b.user_id = u.id WHERE b.status = 1 && b.active = 1 && u.active = 1 ORDER BY b.id DESC");
        }
        $rows = $sql->num_rows;
        if ($rows > 0 && ($cc == 1 || $role->r('role') == 2)) {?>
            <div class="p-5 m-10">
                <h5 class="h-title t-uppercase"> قائمة الوسطاء ( <? echo $rows;?> )</h5>
            </div>
        <?
        }
        if ($role->r('role') == 1) {
            $url = '<a href="index.php?page=subscribe"> من هنا </a>';
            if ($cc == 'not_exit') {
                $text = "أنت غير مشترك !! لتتمكن من رؤية جميع الوسطاء الرجاء الاشتراك".$url;
                echo '<div class="alert alert-danger m-5 mt-20">'.$text.'</div>';
            }elseif($cc == 0){
                $text = " طلبك الاشتراك قيد المراجعة !! الرجاء الانتظار للموافقة على الطلب لتتمكن من رؤية جميع الوسطاء , يمكنك متابعة الطلب ".$url;
                echo '<div class="alert alert-success m-5 mt-20">'.$text.'</div>';
            }elseif($cc == 2){
                $text = " تم تعليق طلب اشتراكك !! لتتمكن من رؤية جميع الوسطاء الرجاء معالجة الطلب ".$url;
                echo '<div class="alert alert-warning m-5 mt-20">'.$text.'</div>';
            }elseif($cc == 3){
                $text = " تم رفض طلب اشتراكك !! لتتمكن من رؤية جميع الوسطاء الرجاء تقديم طلب اشتراك جديد".$url;
                echo '<div class="alert alert-danger m-5 mt-20">'.$text.'</div>';
            }elseif($cc == 4){
                $text = " اشتراكك انتهى !! الرجاء تجديد طلب الاشتراك لتتمكن من رؤية جميع الوسطاء , يمكنك تجديد الطلب ".$url;
                echo '<div class="alert alert-info m-5 mt-20">'.$text.'</div>';
            }
        }else{
            $cc = 1;
        }
        if ($rows > 0) {
            if($cc == 1){
                while ($fetch = $sql->fetch_assoc()){
                    $sql_site = $this->connect()->query("SELECT s.*,bs.* FROM broker_sites as bs LEFT JOIN sites as s ON bs.site_id = s.id 
                    WHERE bs.broker_id = '{$fetch['id']}'");
                    
                    switch ($fetch['period']) {
                        case '1w':
                            $period = 'أقل من أسبوع';
                            break;
                        case '2w':
                            $period = ' من أسبوع الى أسبوعين ';
                            break;
                        case '1m':
                            $period = ' من أسبوعين الى شهر ';
                            break;
                        case '2m':
                            $period = ' أكثر من شهر ';
                            break;
                    }
                    $item_id = $fetch['user_id'];
                    $sql_rate = $this->connect()->query("SELECT * FROM rating WHERE item_id = $item_id && type = 'broker'");
                    $n = 0;
                    $rows_rate = 0;
                    if ($sql_rate->num_rows > 0) {
                        while ($fetch_rate = $sql_rate->fetch_assoc()) {
                            $n = $n + $fetch_rate['rate'];
                        }
                        $rows_rate = ($n / $sql_rate->num_rows);
                    }
                    $sqlf = $this->connect()->query("SELECT * FROM favorate WHERE user_id ='{$_SESSION['id']}' && item_id ='$item_id' && type ='broker'");
                    $rows = $sqlf->num_rows;
                    if ($rows > 0) {
                        $u =  1;
                        $fetchf = $sqlf->fetch_assoc();
                    }else{
                        $u = 0;
                    }
                    ?>
                    <main>
                        <div class="col-md-4 col-sm-6 col-xs-12 p-10 list-broker m-5">
                            <div id="result-fav"></div>
                            <div class="row">
                                <div class="col-xs-3 img-member text-center">
                                    <img src="<? echo $fetch['avatar'];?>" width="80">
                                    <?
                                        if ($fetch['active'] == 0) {
                                            echo '<span class="text-danger pt-10" style="font-size:10px;"><i class="fa fa-close" aria-hidden="true"></i> معطل </span>';
                                        }
                                    ?>
                                </div>
                                <div class="col-xs-9 all-info">
                                    <h4><? echo $fetch['username'];?></h4>
                                    <label> <? echo $fetch['phone'];?></label> /
                                    <label class="rating"><? show_rating(round($rows_rate,0));?></label><br>
                                    <label><b><label><i class="fa fa-truck">&nbsp;</i> </label><? echo $period;?></label><br>
                                    <?
                                        while ($fetch2 = $sql_site->fetch_assoc()) {
                                            echo '<div class="col-xs-3 text-right">
                                                    <img src="./icons/'.$fetch2['logo'].'" width="25">
                                                </div>';
                                        }
                                    ?>
                                </div>
                            </div><hr>
                            <div class="col-xs-6">
                                <a href="index.php?page=Broker_details&&user_id=<? echo $fetch['user_id'];?>" class="show-details-member" data-toggle="modal"> عرض تفاصيل أكثر  >></a>
                            </div>
                            <div class="col-xs-4 d-flex justify-content-between">
                                <a href="index.php?page=Allrating&&broker_id=<? echo $fetch['user_id']?>&&type=broker"> التقييم >></a>
                            </div>
                            <div class="col-xs-2 d-flex justify-content-between install">
                            <?                                    
                                if ($u == 0) {?>
                                    <h4 class="favorate" data-u="0" data-id = "<? echo $fetchf['user_id'];?>" data-userid = "<? echo $_SESSION['id'];?>" data-itemid = "<? echo $fetch['user_id'];?>" data-type="broker"><i class="fa fa-heart text-gray"></i></h4>
                                    <? }elseif($u == 1){?>
                                    <h4 class="favorate" data-u="1" data-id = "<? echo $fetchf['id'];?>" data-userid = "<? echo $_SESSION['id'];?>" data-itemid = "<? echo $fetch['user_id'];?>" data-type="broker"><i class="fa fa-heart text-danger"></i></h4>          
                                <? }
                            ?> 
                            </div>
                        </div>
                    </main>
                <?
                }
            }
        }else{
            box_alert('secondary','لا يوجد وسطاء');
        }
    }
    function get_brokers()
    {
        $user_id = validation($_SESSION['id']);
        $type = "broker";
        $role = new role;
        $rows =  $this->connect()->query("SELECT * FROM brokers WHERE user_id = $user_id")->num_rows;
        $fetch1 = $this->connect()->query("SELECT * FROM users INNER JOIN brokers ON brokers.user_id = users.id WHERE user_id = $user_id")->fetch_assoc();
        $sql2 = $this->connect()->query("SELECT * FROM broker_sites LEFT JOIN brokers ON broker_sites.broker_id = brokers.id LEFT JOIN sites on broker_sites.site_id = sites.id WHERE brokers.user_id = '$user_id'");
        $sql3 = $this->connect()->query("SELECT * FROM social INNER JOIN brokers ON social.broker_id = brokers.id WHERE brokers.user_id = '$user_id' ORDER BY social.id desc");
        $sql_rows = $this->connect()->query("SELECT r.* , u.username,avatar FROM rating AS r JOIN users AS u ON r.user_id = u.id WHERE r.item_id = $user_id && r.type = '$type' ORDER BY r.id DESC");
        $rows_rating = $sql_rows->num_rows;
        switch ($fetch1['status']) {
            case 0:
                $msg = '<i class="fa fa-exchange"></i>&nbsp;<span class="text-info"><i> قيد المراجعة </i></span>';
                break;
            case 1:
                $msg = '<i class="text-success"> تمت الموافقة <i class="fa fa-check"></i></i>';
                break;
            case 2:
                $msg = '<a href="#show-result-pending'.$fetch1['id'].'" class="text-warning" data-toggle="modal" data-id='.$fetch1['id'].'><i class="fa fa-exclamation-triangle"></i>&nbsp;
                        <span class="text-warning"><i> معلق </i></span></a>';
                break;
            case 3:
                $msg = '<a href="#show-result-reject'.$fetch1['id'].'" class="text-danger" data-toggle="modal" data-id='.$fetch1['id'].'><i class="fa fa-close"></i>&nbsp;
                        <span class="text-danger"><i>  مرفوض </i></span></a>';
                break;
        }
        if($rows > 0){?>
            <main>
                <div class="bg-white p-5 pr-10 mt-10 border" id="title_status_broker">
                    <? echo "<span><b> حالة الطلب : </b></span>".$msg;?>
                </div>
                <div class="bg-white border">
                    <ul class="nav nav-tabs justify-content-right" role="tablist">
                        <li class="nav-item broker_info">
                            <a class="nav-link active">
                                <i class="now-ui-icons objects_umbrella-13 fa fa-user"></i> معلوماتي
                            </a>
                        </li>
                        <li class="nav-item broker_rating">
                            <a class="nav-link">
                                <i class="now-ui-icons objects_umbrella-13 fa fa-star"></i> تقييمي 
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-xs-12 form-add p-15" id="broker_info">
                    <?
                        switch ($fetch1['period']) {
                            case '1w':
                                $period = 'أقل من أسبوع';
                                break;
                            case '2w':
                                $period = ' من أسبوع الى أسبوعين ';
                                break;
                            case '1m':
                                $period = ' من أسبوعين الى شهر ';
                                break;
                            case '2m':
                                $period = ' أكثر من شهر ';
                                break;
                        }?>
                        <div class="form-group mt-15">
                            <h6> <i class="fa fa-user">&nbsp;</i>الاسم : <? echo $fetch1['username'];?></h6>
                        </div><hr>
                        <div class="form-group">
                            <h6> <i class="fa fa-whatsapp">&nbsp;</i>رقم الواتس اب : <? echo $fetch1['whatsapp'];?></h6>
                        </div><hr>
                        <div class="form-group">
                            <h6> <i class="fa fa-clock-o">&nbsp;</i>  مدة التوصيل : <? echo $period;?></h6>
                        </div><hr>
                        <div class="form-group">
                            <h6> <i class="fa fa-money">&nbsp;</i>  العمولة : <? echo $fetch1['commission'];?> شيكل</h6>
                        </div><hr>
                        <div class="form-group">
                            <span class="h6 mb-10"> المتاجر : </span>
                            <?
                                while ($fetch2 = $sql2->fetch_assoc()) {
                                    echo '<span class="h6 mb-15"><img src="./icons/'.$fetch2['logo'].'" width="20"> '.$fetch2['name_ar'].' ('.$fetch2['name_en'].')</span>';
                                }
                            ?>
                        </div><hr>
                        <?
                        if ($sql3->num_rows > 0) {?>
                            <div class="col-xs-12 form-group">
                                <span class="h6 mb-10"> مواقع التواصل : </span>
                                <?
                                    while ($fetch3 = $sql3->fetch_assoc()) {
                                        echo '
                                        <div class="col-xs-4">
                                            <a href="'.$fetch3['url'].'" class="mt-20" target="blank">&nbsp;&nbsp;<i class="fa '.$fetch3['type'].'" style="font-size:25px"></i></a>&nbsp;
                                        </div>';
                                    }
                                ?>
                            </div>
                            <? } 
                            if($role->r("role") == 1){
                                if($fetch1['status'] != 3){?>
                                    <div class="form-group mt-10">
                                        <a href="#edit_broker" class="btn btn-info btn_newbroker" data-toggle="modal"><i class="fa fa-edit"></i> تعديل </a>
                                    </div>
                            <? 
                                }
                            }
                        ?>
                    </div>
                <!----------------------------------------- broker rating -------------------------------->
                <div class="review-single p-10" id="broker_rating">
                    <div class="row media">
                        <div class="col-xs-12">
                        <?
                            echo '<h5 class="h-title">'.$rows_rating.' تقييم </h5>';
                            if($rows_rating == 0){
                                box_alert('secondary','لا يوجد تقييم');
                            }else{
                            while ($fetch = $sql_rows->fetch_assoc()) {?>
                                <div class="review-wrapper clearfix mt-20">
                                    <ul class="list-inline">
                                        <li>
                                        <img src="<? echo $fetch['avatar'];?>" class="rounded-circle" width="35"> <span class="review-holder-name"><b><? echo $fetch['username'];?></b></span>&nbsp;<i class="review-date mr-1"><? get_date($fetch['date_created']);?></i>
                                        </li><br>
                                        <li>
                                            <div class="rating">
                                                <span class="rating-os">
                                                    <?
                                                        show_rating($fetch['rate']);
                                                    ?>
                                                </span>
                                            </div>
                                        </li>
                                    </ul>
                                    <p class="copy"><? echo $fetch['notes'];?></p><hr>
                                </div>
                            <? }}?>
                        </div>
                    </div>
                </div>
            </main>
        <?
        }else{?>
            <div class="alert alert-warning border mt-5 m-5">
                <i class="fa fa-exclamation-triangle"></i>
                <p> 
                    هل تعمل كوسيط !! <br> اذا كنت تعمل كوسيط للمتاجر الالكترونية العالمية يمكنك الان تعيين نفسك كوسيط والاستفادة من هذه الخدمة.
                </p>
            </div>
            <div class="form-group mt-10 m-5">
                <a href="#create_broker" class="btn btn-info btn_newbroker" data-toggle="modal"><i class="fa fa-plus"></i> تعيين كوسيط </a>
            </div>
    <? 
    }
        $broker_id = $fetch1['id'];
        $site_id = $sql2->fetch_assoc()['site_id'];
        $user_id = $sql2->fetch_assoc()['user_id'];
        $data_t = 'brokers';
        $data_routs = "";
        $type = 'broker';
        $modals = new Modalss;
        $modals->modals($broker_id,$user_id,$data_t,'',$data_routs,$type);
    }
    function form_brokers()
    {
        $user_id = validation($_SESSION['id']);
        $get_phone = $this->connect()->query("SELECT phone FROM users WHERE id = '$user_id'")->fetch_assoc()['phone'];
        $sql = $this->connect()->query("SELECT * FROM sites order by id desc");
    ?>
        <div class="modal" id="create_broker" tabindex="-1" role="dialog">
            <div class="form-add modal-d">
                <div id="result"></div>
                <div class="modal-content p-5 text-right">
                    <div class="modal-header">
                        <button type="button" class="clos" data-dismiss="modal" aria-hidden="true"><b>x</b></button>
                        <h5> وسيط المتاجر الالكترونية </h5>
                    </div>
                    <form action="" method="post">
                        <div class="p-5 mt-5">
                            <h5><i class="fa fa-user"></i>&nbsp; الاسم : <label>muhammad jehad rabai</label></h5>
                        </div>
                        <div class="form-group p-5">
                            <label for="whatsapp"><i class="fa fa-whatsapp"></i>&nbsp; رقم الواتس اب : </label><br>
                            <select name="code" class="form-control col-xs-5" id="code">
                                <option value="">اختار المقدمة</option>
                                <option value="970"> 970 </option>
                                <option value="972"> 972 </option>
                            </select>
                            <input type="text" class="form-control col-xs-7" name="phone" id="phone" value="<? echo $get_phone;?>">
                        </div><br>
                        <div class="col-xs-12 form-group p-5 mt-10 mb-0">
                            <label for="sites"><i class="fa fa-bank"></i>&nbsp;  اختيار المتاجر : </label><br>
                            <?
                                while ($fetch = $sql->fetch_assoc()) {
                                    echo '<div class="">
                                            <ul class="list-group list-group-light">
                                                <li class="list-group-item">
                                                    <input type="checkbox" class="form-check-input sites_check" name="sites_check" id="sites_check" value="'.$fetch['id'].'"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <img src="./icons/'.$fetch['logo'].'" width="25">
                                                    <label>'.$fetch['name_en'].'</label>&nbsp<label>('.$fetch['name_ar'].')</label>
                                                </li>
                                            </ul>
                                        </div>';
                                }
                            ?>
                        </div>
                        <div class="form-group p-5">
                            <label for="period"><i class="fa fa-clock-o"></i>&nbsp; مدة التوصيل :</label>
                            <select name="period" class="form-control" id="period">
                                <option value="">اختار المدة</option>
                                <option value="1w">أقل من أسبوع </option>
                                <option value="2w">من أسبوع الى أسبوعين</option>
                                <option value="1m">من أسبوعين الى شهر</option>
                                <option value="mm"> أكثر من شهر</option>
                            </select>
                        </div>
                        <div class="col-xs-12 form-group p-5">
                            <label for="comission"><i class="fa fa-money"></i>&nbsp; العمولة على الطرد/ شيكل : </label><br>
                            <input type="text" class="form-control col-xs-5" name="commission" id="commission" placeholder=" العمولة">
                        </div><br>
                        <div class="form-group" id="social_broker">
                            <?
                                for ($i=1;$i<4;$i++) { 
                                    $label_ar['1'] = "الفيسبوك";
                                    $label_en['1'] = "facebook-square";
                                    $label_ar['2'] = "الانستقرام";
                                    $label_en['2'] = "instagram";
                                    $label_ar['3'] = "سناب شات";
                                    $label_en['3'] = "snapchat";
                                ?>
                                <div class="form-group col-xs-12">
                                    <label for="social"><i class="fa fa-<? echo $label_en[$i];?>"></i>&nbsp; رابط حساب <? echo $label_ar[$i];?> : </label><br>
                                    <input type="text" class="form-control social" name="social" id="social" placeholder="أدخل رابط حسابك على <? echo $label_ar[$i];?>" data-type="fa-<? echo $label_en[$i];?>">
                                </div>
                                <? } ?>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success" id="save_broker" data-userid="<? echo $user_id ?>"> حفظ </button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">اغلاق</button>
                            </div>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal details-->
    <?
    }
    function savebrokers()
    {        
        $user_id = validation($_POST['user_id']);
        $count = validation($_POST['count']);
        $counts = validation($_POST['counts']);
        $code = validation($_POST['code']);
        $phone = validation($_POST['phone']);
        $period = validation($_POST['period']);
        $commission = validation($_POST['commission']);
        $whatsapp = (string)$code.(string)$phone;
        $date = time();
        $rows_phone = $this->connect()->query("SELECT id,phone FROM users INNER JOIN brokers WHERE (users.phone='$phone' || brokers.whatsapp='$whatsapp') && users.id != '$user_id'")->num_rows;
        $val = validate_broker($code,$phone,$count,$period,$commission,$rows_phone);
        if (empty($val)) {
            if ($counts > 0) {
                $sql = $this->connect()->query("INSERT INTO brokers(user_id,period,commission,whatsapp,date_created,status,active) 
                VALUES('$user_id','$period','$commission','$whatsapp','$date',0,0)");
                if ($sql) {
                    $datee = time();
                    $lid = $this->connect()->query("SELECT MAX(id) as id FROM brokers WHERE user_id = $user_id")->fetch_assoc()['id'];
                    $sqln = $this->connect()->query("INSERT INTO notifications (item_id,sender,receiver,date_created,body,color,type)
                    VALUES('$lid','$user_id','admin','$datee','لديك طلب وسيط جديد ','secondary','3')");
                    for ($i=0; $i < $count; $i++) { 
                        $sites = validation($_POST['sites'][$i]);
                        $sql_site = $this->connect()->query("INSERT INTO broker_sites(broker_id,site_id)VALUES('$lid','$sites')");
                    }
                    if ($sql_site) {
                        for ($j=0; $j < $counts; $j++) { 
                            $social = validation($_POST['social'][$j]);
                            $type_social = validation($_POST['type_social'][$j]);
                            if(filter_var($social, FILTER_VALIDATE_URL)){
                                $sql_social = $this->connect()->query("INSERT INTO social(broker_id,url,type)VALUES('$lid','$social','$type_social')");   
                            }else{
                                box_alert('danger','الرابط خاطئ !! يرجى ادخال رابط صحيح ');
                            }
                        }
                    }
                    if ($sql_social) {
                        box_alert('success','تم تقديم الطلب بنجاح');
                        echo '<input type="hidden" id="success" value="success"/>';
                    }else {
                        box_alert('danger','خطأ لم يتم تقديم الطلب');
                    }
                }
            }else{
                $sql = $this->connect()->query("INSERT INTO brokers(user_id,period,commission,whatsapp,date_created,status,active) 
                VALUES('$user_id','$period','$commission','$whatsapp','$date',0,0)");
                if ($sql) {
                    $datee = time();
                    $lid = $this->connect()->query("SELECT MAX(id) as id FROM brokers WHERE user_id = $user_id")->fetch_assoc()['id'];
                    $sqln = $this->connect()->query("INSERT INTO notifications (item_id,sender,receiver,date_created,body,color,type)
                    VALUES('$lid','$user_id','admin','$datee','لديك طلب وسيط جديد ','secondary','3')");
                }
                for ($i=0; $i < $count; $i++) { 
                    $sites = validation($_POST['sites'][$i]);
                    $sql_site = $this->connect()->query("INSERT INTO broker_sites(broker_id,site_id)VALUES('$lid','$sites')");
                }
                if ($sql_site) {
                    box_alert('success','تم تقديم الطلب بنجاح');
                    echo '<input type="hidden" id="success" value="success"/>';
                }else {
                    box_alert('danger','خطأ لم يتم تقديم الطلب');
                }
            }
        }else{
            box_alert('danger',$val);
        }
            
    }
    function form_edit_broker()
    {
        $role = new role;
        if ($role->r('role') == 2) {
            $user_id = validation($_GET['user_id']);
        }else{
            $user_id = validation($_SESSION['id']);
        }
        $fetch1 = $this->connect()->query("SELECT * FROM users INNER JOIN brokers ON brokers.user_id = users.id WHERE brokers.user_id = $user_id")->fetch_assoc();
        switch ($fetch1['period']) {
            case '1w':
                $period = 'أقل من أسبوع';
                break;
            case '2w':
                $period = ' من أسبوع الى أسبوعين ';
                break;
            case '1m':
                $period = ' من أسبوعين الى شهر ';
                break;
            case '2m':
                $period = ' أكثر من شهر ';
                break;
        }
    ?>
        <!-------------------------------------------- edit brokers ----------------------------------------->
        <div class="modal" id="edit_broker" tabindex="-1" role="dialog">
            <div class="form-add modal-d">
                <div id="result_edit"></div>
                <div class="modal-content p-5 text-right">
                    <div class="modal-header">
                        <button type="button" class="clos" data-dismiss="modal" aria-hidden="true"><b>x</b></button>
                        <h5> التعديل </h5>
                    </div>
                    <form action="" method="post">
                        <div class="p-5 mt-5">
                            <h5><i class="fa fa-user"></i>&nbsp; الاسم : <label> <? echo $fetch1['username'];?></label></h5>
                        </div>
                        <div class="form-group p-5">
                            <label for="whatsapp"><i class="fa fa-whatsapp"></i>&nbsp; رقم الواتس اب : </label><br>
                            <?php $code =  substr((string)$fetch1['whatsapp'],0,3);?>
                            <select name="code" class="form-control col-xs-5" id="codee">
                                <option value="<? echo $code;?>"><? echo $code;?></option>
                                <option value="970"> 970 </option>
                                <option value="972"> 972 </option>
                            </select>
                            <input type="text" class="form-control col-xs-7" name="phone" id="new_phone" value="<? echo substr((string)$fetch1['whatsapp'],3,10);?>">
                        </div><br>
                        <div class="col-xs-12 form-group p-5 mt-10 mb-0">
                            <label for="sites"><i class="fa fa-bank"></i>&nbsp;  اختيار المتاجر : </label><br>
                            <?
                                $sql_broker_sites = $this->connect()->query("SELECT broker_sites.*, brokers.user_id, sites.name_ar,sites.name_en,sites.logo FROM broker_sites LEFT JOIN brokers ON broker_sites.broker_id = brokers.id LEFT JOIN sites on broker_sites.site_id = sites.id WHERE brokers.user_id = '$user_id'");
                                $sql_site = $this->connect()->query("SELECT * FROM sites WHERE active = 1");
                                $count = $sql_site->num_rows - $sql_broker_sites->num_rows;
                            ?>
                            <div class="">
                                <ul class="list-group list-group-light">
                                    <?
                                    $a_site = array();
                                    while ($fetch22 = $sql_broker_sites->fetch_assoc()) {
                                        echo '<input type="hidden" id="broker_id" value="'.$fetch22['broker_id'].'">';
                                        echo "
                                        <li class='list-group-item'> 
                                            <input type='checkbox' class='form-check-input new_sites_check' name='sites_check' id='sites_check' value='{$fetch22['site_id']}' data-broker_id='{$fetch22['broker_id']}' data-site_id='{$fetch22['id']}' checked>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <img src='./icons/{$fetch22['logo']}' width='25'>
                                                <label>{$fetch22['name_en']}</label>&nbsp<label>({$fetch22['name_ar']})</label>
                                        </li>";
                                        array_push($a_site,$fetch22['site_id']);
                                    }
                                    for ($j=0; $j<$sql_site->num_rows; $j++) { 
                                        $fetch_site = $sql_site->fetch_assoc();
                                        if (!in_array($fetch_site['id'],$a_site)) {
                                            echo "
                                                <li class='list-group-item'> 
                                                    <input type='checkbox' class='form-check-input new_sites_check' name='sites_check' id='sites_check' value='{$fetch_site['id']}' data-broker_id='{$fetch22['broker_id']}' data-site_id='{$fetch_site['id']}'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <img src='./icons/{$fetch_site['logo']}' width='25'>
                                                        <label>{$fetch_site['name_en']}</label>&nbsp<label>({$fetch_site['name_ar']})</label>
                                                </li>";
                                        }

                                    }
                                    ?>           
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="form-group p-5">
                            <label for="period"><i class="fa fa-clock-o"></i>&nbsp; مدة التوصيل :</label>
                            <select name="period" class="form-control" id="periods">
                                <option value="<? echo $fetch1['period'];?>"><? echo $period;?></option>
                                <option value="1w">أقل من أسبوع </option>
                                <option value="2w">من أسبوع الى أسبوعين</option>
                                <option value="1m">من أسبوعين الى شهر</option>
                                <option value="2m"> أكثر من شهر</option>
                            </select>
                        </div>
                        <div class="col-xs-12 form-group p-5">
                            <label for="comission"><i class="fa fa-money"></i>&nbsp; العمولة على الطرد/ شيكل : </label><br>
                            <input type="text" class="form-control col-xs-4" name="commission" id="comission" value="<? echo $fetch1['commission'];?>">
                        </div><br>
                        <div class="form-group" id="social_broker">
                            <?
                                $a = array("fa-facebook-square","fa-instagram","fa-snapchat");
                                $aa = array();
                                $sql_broker_social = $this->connect()->query("SELECT * FROM brokers INNER JOIN social ON social.broker_id = brokers.id WHERE brokers.user_id = '$user_id' ORDER BY social.id desc");
                                for ($i=0; $i < $sql_broker_social->num_rows; $i++) { 
                                    $fetch33 = $sql_broker_social->fetch_assoc();
                                    array_push($aa,$fetch33['type']);
                                    switch ($fetch33['type']) {
                                        case 'fa-facebook-square':
                                            $s = 'الفيسبوك';
                                            break;
                                        case 'fa-instagram':
                                            $s = 'الانستقرام';
                                            break;
                                        case 'fa-snapchat':
                                            $s = 'سناب شات';
                                            break;
                                    }
                                    if(in_array($fetch33['type'],$a)){?>
                                        <div class="form-group col-xs-12">
                                            <label for="social"><i class="fa <? echo $fetch33['type'];?>"></i>&nbsp; رابط حساب <? echo $s;?></label><br>
                                            <input type="text" class="form-control social" name="social" id="social" value=" <? echo $fetch33['url'];?> " data-type="<? echo $fetch33['type'];?>" data-social_id="<? echo $fetch33['id'];?>">
                                        </div>
                                    <?}
                                }
                                for ($j=0; $j <count($a); $j++) { 
                                    if(!in_array($a[$j],$aa)){
                                        switch ($a[$j]) {
                                            case 'fa-facebook-square':
                                                $ss = 'الفيسبوك';
                                                break;
                                            case 'fa-instagram':
                                                $ss= 'الانستقرام';
                                                break;
                                            case 'fa-snapchat':
                                                $ss = 'سناب شات';
                                                break;
                                        }?>
                                        <div class="form-group col-xs-12">
                                            <label for="social"><i class="fa <? echo $a[$j];?>"></i>&nbsp; رابط حساب <? echo $ss;?></label><br>
                                            <input type="text" class="form-control social" name="social" id="social" value="" data-type=" <? echo $a[$j];?> ">
                                        </div>
                                    <?
                                        }
                                }
                                //if ($role->r('role') == 2) {
                                    switch ($fetch1['active']) {
                                        case '1':
                                            $active = 'تنشيط';
                                            break;
                                        case '0':
                                            $active = 'الغاء التنشيط';
                                            break;
                                }?>
                                <div class="form-group col-xs-12">
                                    <label for="status"><i class="fa"></i>&nbsp; حالة الحساب : </label><br>
                                    <select name="active" class="form-control" id="active">
                                        <option value="<? echo $fetch1['active'];?>"><? echo $active;?></option>
                                        <option value="1"> تنشيط </option>
                                        <option value="0"> الغاء التنشيط </option>
                                    </select>
                                </div>
                            <? //} ?>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success" id="save_editbroker" data-userid="<? echo $user_id ?>"> حفظ </button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">اغلاق</button>
                            </div>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal details-->
    <?}
    function edit_brokers()
    {
        $user_id = validation($_POST['user_id']);
        $counts = validation($_POST['counts']);
        $count_site = validation($_POST['count_site']);
        $count_r_site = validation($_POST['count_r_site']);
        $broker_id = validation($_POST['broker_id']);
        $code = validation($_POST['code']);
        $phone = validation($_POST['phone']);
        $period = validation($_POST['period']);
        $commission = validation($_POST['commission']);
        $active = validation($_POST['active']);
        $whatsapp = (string)$code.(string)$phone;
        $rows_phone = $this->connect()->query("SELECT * FROM users INNER JOIN brokers WHERE (brokers.whatsapp = '$whatsapp' && brokers.user_id != '$user_id') || (users.phone ='$phone' && users.id != '$user_id')")->num_rows;
        $social_r = $this->connect()->query("SELECT * FROM social WHERE broker_id = '$broker_id'");
        $social_array = array();
        while ($fetch_social = $social_r->fetch_assoc()) { 
            array_push($social_array,$fetch_social['id']);
        }
        $val = validate_broker($code,$phone,$count_site,$period,$commission,$rows_phone);
        if (empty($val)) {
            if ($count_site > 0) {
                $sql = $this->connect()->query("UPDATE brokers SET period='$period',commission='$commission',whatsapp='$whatsapp',active='$active' WHERE user_id ='$user_id'");
                $sql1 = $this->connect()->query("SELECT * FROM brokers INNER JOIN broker_sites ON broker_sites.broker_id = brokers.id WHERE brokers.user_id ='$user_id' order by broker_sites.id desc");
                $a = array();
                while($fetch1 = $sql1->fetch_assoc()) {
                    array_push($a,$fetch1['site_id']);
                }
                for ($i=0; $i < $count_site ; $i++) { 
                    $sites = validation($_POST['sites'][$i]);
                    if (!in_array($sites,$a)) {
                        $sql = $this->connect()->query("INSERT INTO broker_sites(site_id,broker_id) VALUES('$sites','$broker_id')");
                        $sql = 'true';
                    }
                }
                for ($j=0; $j < $count_r_site; $j++) { 
                    $r_sites = validation($_POST['r_sites'][$j]);
                    if (in_array($r_sites,$a)) {
                        $sql = $this->connect()->query("DELETE FROM broker_sites WHERE site_id = '$r_sites' && broker_id = '$broker_id'");
                        $sql = 'true';
                    }
                }                
            }
            if($counts > 0) {
                for ($i=0; $i < 3; $i++){ 
                    $social_id = validation($_POST['social'][0]['id'][$i]);
                    $social_val = validation($_POST['social'][0]['val'][$i]);
                    $social_type = validation($_POST['social'][0]['type'][$i]);
                    $social_remove_id = validation($_POST['social_remove_id'][$i]);
                    if ($social_id != null) {
                        if (in_array($social_remove_id,$social_array)) {
                            $query = $this->connect()->query("DELETE FROM social WHERE id = '$social_remove_id'");
                        }elseif (filter_var($social_val, FILTER_VALIDATE_URL)) {
                            $query = $this->connect()->query("UPDATE social SET url='$social_val',type='$social_type' WHERE id ='$social_id'");
                            $query = 'true';
                        }else {
                            box_alert('danger',' رابط أحد مواقع التواصل الاجتماعي خاطئ !! يرجى ادخال رابط صحيح');
                            $query = 'false';
                        }
                    }elseif($social_val != null){
                        if (filter_var($social_val, FILTER_VALIDATE_URL)){
                            $query = $this->connect()->query("INSERT INTO social(broker_id,url,type) VALUES('$broker_id','$social_val','$social_type')"); 
                            $query = 'true';
                        }else {
                            box_alert('danger',' رابط أحد مواقع التواصل الاجتماعي خاطئ !! يرجى ادخال رابط صحيح');
                            $query = 'false';
                        }
                    }
                }
            }
            
        }else{
            box_alert('danger',$val);
        }
        if ($counts > 0) {
            if ($query == 'true' && $sql = 'true') {
                echo '<input type="hidden" id="success" value="success"/>';
            }
        }elseif($sql = 'true') {
            echo '<input type="hidden" id="success" value="success"/>';
        }
    }
    function get_brokersite()
    {
        $site_id = validation($_GET['site_id']);
        $role = new role;
        $sql = $this->connect()->query("SELECT * FROM brokers LEFT JOIN broker_sites ON broker_sites.broker_id = brokers.id
        LEFT JOIN users ON brokers.user_id = users.id WHERE broker_sites.site_id = $site_id && brokers.status = 1 && brokers.active = 1 ORDER BY brokers.id DESC");
        $rows = $sql->num_rows;
        $check_sub = new info;
        $cc = $check_sub->check_subscribe();
        ?>
        <div class="p-5 m-10">
            <h5 class="h-title t-uppercase"> قائمة الوسطاء للمتجر ( <? echo $rows;?> )</h5>
        </div>
        <?
        if ($rows > 0) {
            if ($role->r('role') == 1) {
                $url = '<a href="index.php?page=subscribe"> من هنا </a>';
                if ($cc == 'not_exit') {
                    $text = "أنت غير مشترك !! لتتمكن من رؤية الوسطاء لهذا الموقع الرجاء الاشتراك".$url;
                    echo '<div class="alert alert-danger m-5 mt-20">'.$text.'</div>';
                }elseif($cc == 0){
                    $text = " طلبك الاشتراك قيد المراجعة !! الرجاء الانتظار للموافقة على الطلب لتتمكن من رؤية الوسطاء لهذا الموقع , يمكنك متابعة الطلب ".$url;
                    echo '<div class="alert alert-success m-5 mt-20">'.$text.'</div>';
                }elseif($cc == 2){
                    $text = " تم تعليق طلب اشتراكك !! لتتمكن من رؤية الوسطاء لهذا الموقع الرجاء معالجة الطلب ".$url;
                    echo '<div class="alert alert-warning m-5 mt-20">'.$text.'</div>';
                }elseif($cc == 3){
                    $text = " تم رفض طلب اشتراكك !! لتتمكن من رؤية الوسطاء لهذا الموقع الرجاء تقديم طلب اشتراك جديد".$url;
                    echo '<div class="alert alert-danger m-5 mt-20">'.$text.'</div>';
                }elseif($cc == 4){
                    $text = " اشتراكك انتهى !! الرجاء تجديد طلب الاشتراك لتتمكن من رؤية الوسطاء لهذا الموقع , يمكنك تجديد الطلب ".$url;
                    echo '<div class="alert alert-info m-5 mt-20">'.$text.'</div>';
                }
            }else{
                $cc = 1;
            }
            if ($cc == 1) {
            while ($fetch = $sql->fetch_assoc()){
                switch ($fetch['period']) {
                    case '1w':
                        $period = 'أقل من أسبوع';
                        break;
                    case '2w':
                        $period = ' من أسبوع الى أسبوعين ';
                        break;
                    case '1m':
                        $period = ' من أسبوعين الى شهر ';
                        break;
                    case '2m':
                        $period = ' أكثر من شهر ';
                        break;
                }
                $item_id = $fetch['user_id'];
                $sql_rate = $this->connect()->query("SELECT * FROM rating WHERE item_id = $item_id && type = 'broker'");
                $n = 0;
                $rows_rate = 0;
                if ($sql_rate->num_rows > 0) {
                    while ($fetch_rate = $sql_rate->fetch_assoc()) {
                        $n = $n + $fetch_rate['rate'];
                    }
                    $rows_rate = ($n / $sql_rate->num_rows);
                }
                $sqlf = $this->connect()->query("SELECT * FROM favorate WHERE user_id ='{$_SESSION['id']}' && item_id ='{$fetch['id']}' && type ='broker'");
                $rows = $sqlf->num_rows;
                if ($rows > 0) {
                    $u =  1;
                    $fetchf = $sqlf->fetch_assoc();
                }else{
                    $u = 0;
                }?>
                <main>
                    <div id="result-fav"></div>
                        <div class="col-md-4 col-sm-6 col-xs-12 p-10 list-broker m-5">
                            <div class="row">
                                <div class="col-xs-3 img-product text-center">
                                    <img src="<? echo $fetch['avatar'];?>" width="80">
                                </div>
                                <div class="col-xs-9 all-info">
                                    <div class="product-info">
                                        <h4><? echo $fetch['username'];?></h4>
                                        <label> <? echo $fetch['phone'];?></label> /
                                        <label class="rating"><? show_rating(round($rows_rate,0));?></label><br>
                                        <label><b><label><i class="fa fa-truck">&nbsp;</i> </label><? echo $period;?></label>
                                    </div>
                                </div>
                            </div><hr>
                            <?
                            if ($fetch['user_id'] == $_SESSION['id']) {?>
                                <div class="col-xs-6">
                                    <a href="index.php?page=Createbrokers" class="show-details-member" data-toggle="modal"> عرض تفاصيل أكثر  >></a>
                                </div>
                            <?}else{?>
                                <div class="col-xs-6">
                                    <a href="index.php?page=Broker_details&&site_id=<? echo $site_id;?>&&user_id=<? echo $fetch['user_id'];?>" class="show-details-member" data-toggle="modal"> عرض تفاصيل أكثر  >></a>
                                </div>
                                <div class="col-xs-4 d-flex justify-content-between">
                                    <a href="index.php?page=Allrating&&broker_id=<? echo $fetch['user_id']?>&&type=broker"> التقييم >></a>
                                </div>
                            <? }?>
                        <div class="col-xs-2 d-flex justify-content-between install">
                        <?
                            if($fetch['user_id'] != $_SESSION['id'] || $role->r("role") == 2){
                                if ($u == 0) {?>
                                    <h4 class="favorate" data-u="0" data-id = "<? echo $fetchf['user_id'];?>" data-userid = "<? echo $_SESSION['id'];?>" data-itemid = "<? echo $fetch['user_id'];?>" data-type="broker"><i class="fa fa-heart text-gray"></i></h4>
                                <? }elseif($u == 1){?>
                                    <h4 class="favorate" data-u="1" data-id = "<? echo $fetchf['id'];?>" data-userid = "<? echo $_SESSION['id'];?>" data-itemid = "<? echo $fetch['user_id'];?>" data-type="broker"><i class="fa fa-heart text-danger"></i></h4>          
                            <? }
                            }
                        ?> 
                        </div>
                    </div>
                </main>
            <?
            }
        }
        }else{
            box_alert('secondary','لا يوجد وسطاء لهذا المتجر');
        }
    }
    function broker_details()
    {
        $user_id = validation($_GET['user_id']);
        $role = new role;
        $sql = $this->connect()->query("SELECT * FROM brokers LEFT JOIN broker_sites ON broker_sites.broker_id = brokers.id
        LEFT JOIN users ON brokers.user_id = users.id WHERE brokers.user_id = $user_id && brokers.active = 1");
        $fetch = $sql->fetch_assoc();
        $broker_id = $fetch['broker_id'];
        $sql_sites = $this->connect()->query("SELECT * FROM sites LEFT JOIN broker_sites ON broker_sites.site_id = sites.id WHERE broker_sites.broker_id = $broker_id && sites.active = 1");
        $sql_social = $this->connect()->query("SELECT * FROM social WHERE broker_id = $broker_id");
        switch ($fetch['period']) {
            case '1w':
                $period = 'أقل من أسبوع';
                break;
            case '2w':
                $period = ' من أسبوع الى أسبوعين ';
                break;
            case '1m':
                $period = ' من أسبوعين الى شهر ';
                break;
            case '2m':
                $period = ' أكثر من شهر ';
                break;
        }
        switch ($fetch['status']) {
            case 0:
                $msg = '<i class="fa fa-exchange"></i>&nbsp;<span class="text-info"><i> قيد المراجعة </i></span>';
                break;
            case 1:
                $msg = '<i class="text-success"> تمت الموافقة <i class="fa fa-check"></i></i>';
                break;
            case 2:
                $msg = '<a href="#show-result-pending'.$fetch['broker_id'].'" class="text-warning" data-toggle="modal" data-id='.$fetch['broker_id'].'><i class="fa fa-exclamation-triangle"></i>&nbsp;
                        <span class="text-warning"><i> معلق </i></span></a>';
                break;
            case 3:
                $msg = '<a href="#show-result-reject'.$fetch['broker_id'].'" class="text-danger" data-toggle="modal" data-id='.$fetch['broker_id'].'><i class="fa fa-close"></i>&nbsp;
                        <span class="text-danger"><i>  مرفوض </i></span></a>';
                break;
        }
        $item_id = validation($_GET['user_id']);
        $sql_rate = $this->connect()->query("SELECT * FROM rating WHERE item_id = $item_id");
        $n = 0;
        while ($fetch_rate = $sql_rate->fetch_assoc()) {
            $n = $n + $fetch_rate['rate'];
        }
        if ($sql_rate->num_rows > 0) {
            $rows_rate = ($n / $sql_rate->num_rows);
        }
        if ($sql->num_rows == 0) {
            box_alert('secondary','انت غير مسجل كوسيط لأي من المتاجر العالمية');
        }else{
            if ($role->r('role') == 2 || $user_id == validation($_SESSION['id'])) {?>
                <div class="bg-white p-10 pr-10 mt-10 border">
                    <? echo "<span><b> حالة الطلب : </b></span>".$msg;?>
                </div>
        <? }?>
        <div class="col-xs-12 p-10 form-add">
            <div id="result"></div>
            <div class="col-xs-12 text-center">
                <img src="<? echo $fetch['avatar'];?>" class="rounded-cercle" width="70">
            </div>
            <div class="col-xs-12 mt-20">
                <h6><i class="fa fa-user"></i><b> اسم الوسيط : </b>&nbsp;<? echo $fetch['username'];?></h6><hr>
                <h6><i class="fa fa-phone"></i><b>  رقم الهاتف : </b>&nbsp;<?php echo substr((string)$fetch['whatsapp'],3,10);?>&nbsp;&nbsp;&nbsp;
                <a href="https://wa.me/<? echo $fetch['whatsapp'];?>" target="blanek"><i class="fa fa-whatsapp text-success" style="font-size:20px;"></i></a> </h6><hr>
                <h6><i class="fa fa-truck"></i><b> مدة التوصيل : </b>&nbsp;<? echo $period;?></h6><hr>
                <h6><i class="fa fa-money text-success"></i><b> العمولة : </b>&nbsp;<? echo $fetch['commission'];?> شيكل</h6><hr>
                <h6><i class="fa fa-star text-warning"></i><b> التقييم : </b>&nbsp;<? show_rating(round($rows_rate,0));?></h6><hr>
                <div class="row p-0 m-0">
                    <h6><b> المتاجر :  </b></h6>
                    <?
                        while ($fetch_site = $sql_sites->fetch_assoc()) {?>
                            <main>
                                <div class="t-center">
                                    <div class="col-xs-4 col-lg-2">
                                            <div class="embed-responsive embed-responsive-4by3">
                                                <div class="store-logo">
                                                    <img src="./icons/<? echo $fetch_site['logo'];?>" class="p-10" width="90" height="">
                                                </div>
                                            </div>
                                            <label class="store-name pb-5"><? echo $fetch_site['name_en']; echo"</br>({$fetch_site['name_ar']})";?></label>
                                        </a>
                                    </div>
                                </div>
                            </main>    
                        <?}
                    ?>
                </div><hr>
                <?
                if ($sql_social->num_rows > 0) {?>
                    <div class="col-xs-12 form-group">
                        <span class="h6 mb-10"> مواقع التواصل : </span>
                        <?
                            while ($fetch_social = $sql_social->fetch_assoc()) {
                                echo '
                                <div class="col-xs-4">
                                    <a href="'.$fetch_social['url'].'" class="mt-20" target="blank">&nbsp;&nbsp;<i class="fa '.$fetch_social['type'].'" style="font-size:25px"></i></a>&nbsp;
                                </div>';
                            }
                        ?>
                    </div>
                <? 
                }
                if($role->r("role") == 1){
                    if($fetch['status'] == 0 || $fetch['status'] == 2){
                        echo '  
                            <div class="mt-15 order-btn">
                                <a href="index.php?page=Edit&&page_id='. $fetch['broker_id'].'&&S=edit_page" class="btn-success btn-info"> تعديل </a>
                                <a href="#delete-order'.$fetch['broker_id'].'" class="btn-danger" id="del_page" data-toggle="modal"> حذف </a>
                            </div>';
                        }
                    }
                if($role->r("role") == 2){?>
                    <div class="mt-15 order-btn text-center">
                        <a href="#edit_broker" class="btn-info admin_edit_broker" data-toggle="modal"><i class="fa fa-edit"></i> تعديل  </a>
                        &nbsp;<a href="#delete-order<? echo $fetch['broker_id'];?>" class="btn-danger" data-toggle="modal"><i class="fa fa-trash"></i> حذف </a>
                    </div>
                    <div class="mt-15 order-btn text-center">
                        <?
                            if($fetch['status'] != 1){
                                echo '<a href="#acceppt-order'.$fetch['broker_id'].'" class="btn-success" data-toggle="modal"><i class="fa fa-check"></i> قبول </a>';
                            }
                        ?>
                        <a href="#pendding-order<? echo $fetch['broker_id'];?>" class="btn-warning" data-toggle="modal"><i class="fa fa-exclamation-triangle"></i> تعليق </a>
                        <a href="#reject-order<? echo $fetch['broker_id'];?>" class="btn-danger" data-toggle="modal"><i class="fa fa-close"></i> رفض </a>
                    </div>
                <?
                }
                    $broker_id = $fetch['broker_id'];
                    $site_id = $fetch['site_id'];
                    $user_id = $fetch['user_id'];
                    $data_t = 'brokers';
                    $data_routs = "Broker_details&&site_id=$site_id&&user_id=$user_id";
                    $type = 'broker';
                    $modals = new Modalss;
                    $modals->modals($broker_id,$user_id,$data_t,'',$data_routs,$type);
                ?>
            </div>
        </div>
    <?
    }
    }
}
$f = validation($_GET['f']);
$broker = new Brokers;
switch ($f) {
    case 'save':
        $broker->savebrokers();
        break;
    case 'save_edit':
        $broker->edit_brokers();
        break;
}
?>