<?php
//include "newsub_func.php";
class getmembers extends DB
{
    public function get_member()
    {
        $type = validation($_GET['type']);
        if ($type == 'online_m') {
            $sql1 = "SELECT * FROM users WHERE role = 'member' && online = 1 ORDER BY date_updated DESC";
        }elseif($type == 'active_m'){
            $sql1 = "SELECT * FROM users WHERE role = 'member' && active = 1 ORDER BY date_updated DESC";
        }elseif($type == 'disable_m'){
            $sql1 = "SELECT * FROM users WHERE role = 'member' && active = 0 ORDER BY date_updated DESC";
        }else{
            $sql1 = "SELECT * FROM users WHERE role = 'member' ORDER BY date_updated DESC";
        }
        $query_member1 = $this->connect()->query($sql1);
        $rows_member1 = $query_member1->num_rows;
        echo '<input type="hidden" class="count_m" value="('.$rows_member1.')"/>';
        if($rows_member1 > 0){
            //echo '<div class="text-center"><span class="btn btn-secondary">'.$rows_member1.'</span></div>';
            while($fetch_member = $query_member1->fetch_assoc()){
                $data[] = $fetch_member;
            }
            foreach($data as $key=>$getmember){
                $user_id = $getmember['id'];
                $sql2 = "SELECT * FROM subscribe WHERE user_id = '$user_id'";
                $query_member2 = $this->connect()->query($sql2);
                $rows_member2 = $query_member2->num_rows;
                if($rows_member2 > 0){
                    $fetch_member2 = $query_member2->fetch_assoc();
                }
                ?>
                <main>
                    <div class="col-md-4 col-sm-6 col-xs-12 p-20 list-member m-5">
                        <div class="row">
                            <div class="col-xs-3 img-member text-center">
                                <img src="<? echo $getmember['avatar'];?>" class="img-circle mb-10" width="80" style="height:65px;"><br>
                                <?
                                    if ($getmember['active'] == 1) {
                                        if ($getmember['online'] == 1) {
                                            echo '<span class="text-success" style="font-size:10px;"><i class="fa fa-circle" aria-hidden="true"></i> نشط </span>';
                                        }
                                    }else{
                                        echo '<span class="text-danger" style="font-size:10px;"><i class="fa fa-close" aria-hidden="true"></i> معطل </span>';
                                    }
                                ?>

                            </div>
                            <div class="col-xs-9 all-info">
                                <div class="member-info">
                                    <h4><label> <? echo $getmember['username'];?>  </label>&nbsp;&nbsp;<label><?
                                        if($fetch_member2['user_id'] == $getmember['id']){
                                            $get_status = $fetch_member2['status'];
                                        }else{
                                            $get_status = 3;
                                        }
                                        switch ($get_status) {
                                            case 1:
                                                echo '<i class="fa fa-money text-success"></i>';
                                                break;
                                        };
                                    ?>
                                    </label></h4>
                                    <label> <? echo $getmember['email'];?>  &nbsp;<i class="fa fa-envelope"></i></label><br>
                                    <label><i class="fa fa-phone">&nbsp;</i> </label>
                                    <label> <? echo $getmember['phone'];?> </label>&nbsp;&nbsp;&nbsp;
                                    <label><i class="fa fa-map-marker">&nbsp;</i>  <? echo $getmember['city'];?> / </label><label> <? echo $getmember['address'];?> </label><br>
                                    <?
                                        if($getmember['online'] == 0){?>
                                            <span class="online" style="font-size:12px;color:rgb(150, 150, 150);"><i>نشط <? get_date($getmember['date_updated']);?></i></span>
                                    <? }?>
                                </div><hr>
                                <div class="col-xs-4">
                                    <a href="index.php?page=Member_details&&user_id=<?echo $getmember['id'];?>&&p=member_info" class="show-details-member" data-toggle="modal"><i class="fa fa-eye"></i></a>
                                </div>
                                <div class="col-xs-4">
                                    <a href="#edit-info-member_<?echo $getmember['id'];?>" class="edit-info-member" data-toggle="modal" data-user="<? echo $getmember['id'];?>"><i class="fa fa-edit"></i></a>
                                </div>
                                <div class="col-xs-4 delet-member">
                                    <a href="#delete-member" class="delete_member" data-toggle="modal" data-user="<? echo $getmember['id'];?>"><i class="fa fa-trash"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!----------------------------------------- Modal edit member --------------------------------------->
                    <div class="modal text-center edit-info-member" id="edit-info-member_<? echo $getmember['id'];?>" tabindex="-1" role="dialog">
                        <div class="">
                            <div class="modal-content text-right">
                                <form action="#" method="post">
                                    <div class="modal-header">
                                        <button type="button" class="clos" data-dismiss="modal" aria-hidden="true"><b>x</b></button>
                                        <span id="result_<? echo $getmember['id'];?>"></span>
                                        <h5> تعديل بيانات العضو </h5>
                                    </div>
                                        <div class="add-member p-10">
                                            <input type="hidden" id="active" value="<?echo $getmember['active'];?>">
                                            <!--
                                                <div class="form-group addimg-member text-center">
                                                    <img src="" class="img-circle avatar" width="90"><br>
                                                    <a href="#" title="تحميل صورة شخصية"><i class="fa fa-upload"></i> تغيير الصورة الشخصية </a>
                                                    <input type="file" class="form-control box_add_avatar" name="addavatar" title="تحميل صورة شخصية">
                                                </div>
                                            -->
                                            <div class="form-group name-info">
                                                <label for="name">الاسم الكامل : </label>
                                                <input type="text" class="form-control" name="name" id="name_<? echo $getmember['id'];?>" placeholder="الاسم الكامل" value="<? echo $getmember['username'];?>">
                                            </div>
                                            <div class="form-group email-info">
                                                <label for="email"> البريد الالكتروني : </label>
                                                <input type="email" class="form-control" name="email" id="email_<? echo $getmember['id'];?>" placeholder="الايميل" value="<? echo $getmember['email'];?>">
                                            </div>
                                            <div class="form-group phone-info">
                                                <label for="phone"> رقم الجوال : </label>
                                                <input type="text" class="form-control" name="phone" id="phone_<? echo $getmember['id'];?>" placeholder="رقم الجوال" value="<? echo $getmember['phone'];?>">
                                            </div>
                                            <div class="form-group city-info">
                                                <label for="city">  المحافظة : </label>
                                                <select name="city" id="city_<? echo $getmember['id'];?>" class="form-control">
                                                    <option value="<? echo $getmember['city'];?>"> <? echo $getmember['city'];?></option>
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
                                                <label for="address">  المنطقة : </label>
                                                <input type="text" name="address" id="address_<? echo $getmember['id'];?>" class="form-control" placeholder="المنطقة" value="<? echo $getmember['address'];?>">
                                            </div>
                                            <div class="form-group gender-info">
                                                <label for="gender">  الجنس : </label>
                                                <select name="gender" id="gender_<? echo $getmember['id'];?>" class="form-control">
                                                    <option value="<? echo $getmember['gender'];?>"><?
                                                    switch ($getmember['gender']){
                                                        case 'male':
                                                            echo 'ذكر';
                                                            break;
                                                        
                                                        default:
                                                            echo 'أنثى';
                                                            break;
                                                        }?>
                                                    </option>
                                                    <option value="male">ذكر</option>                                        
                                                    <option value="female">أنثى</option>                                        
                                                </select>
                                            </div>
                                            <div class="form-group active-info">
                                                <label for="active">
                                                    <?
                                                        switch ($getmember['active']) {
                                                            case 1:
                                                                $stt = "نشط";
                                                                echo  $stt . ' <i class="fa fa-check-circle text-success"></i>';
                                                                break;
                                                            
                                                            default:
                                                                $stt = "معطل";
                                                                echo $stt . ' <i class="fa fa-check-circle text-danger"></i>';
                                                                break;
                                                        }
                                                    ?>
                                                </label>
                                                <select name="active" id="active_<? echo $getmember['id'];?>" class="form-control">
                                                    <option value="<? echo $getmember['active'];?>"><? echo $stt;?></option>                                       
                                                    <option value="1">تنشيط</option>                                       
                                                    <option value="0">تعطيل</option>                                        
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-info save_editmember" data-user="<? echo $getmember['id'];?>"> حفظ </button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">اغلاق</button>
                                        </div>
                                    </form>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal details-->
                        <!----------------------------------------- Modal delete customer --------------------------------------->
                        <!-- Modal details -->
                        <div class="modal text-center" id="delete-member" tabindex="-1" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content text-right">
                                    <div class="modal-header">
                                        <button type="button" class="clos" data-dismiss="modal" aria-hidden="true"><b>x</b></button>
                                        <span id="result"></span>
                                        <h5> حذف العضو </h5>
                                    </div>
                                    <form method="post">
                                        <div class="p-10">
                                            <h5>  هل انت متأكد من حذف هذا العضو </h5>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" id="btn_deletemember"> حذف </button>
                                            <button type="button" class="btn btn-info" data-dismiss="modal">اغلاق</button>
                                        </div>
                                    </form>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal details-->
                    <!-------------------------------------- End delete-member modal ---------------------------------------------->
                </main>
                <?php
                    }
                }elseif($type == 'online_m'){
                    $color = "secondary";
                    $text = "لا يوجد أعضاء نشطون ";
                    box_alert($color , $text);
                }elseif($type == 'disable_m'){
                    $color = "secondary";
                    $text = "لا يوجد حسابات معطلة ";
                    box_alert($color , $text);
                }else {
                    $color = "secondary";
                    $text = "لا يوجد أعضاء مسجلين ";
                    box_alert($color , $text);
                }
        //return $rows_member;
    }
    public function get_member_sub()
    {
        $sql1 = "SELECT * FROM subscribe INNER JOIN users ON subscribe.user_id = users.id WHERE users.role = 'member' && subscribe.status = 1 ORDER BY subscribe.id DESC";
        $query_member1 = $this->connect()->query($sql1);
        $rows_member1 = $query_member1->num_rows;
        if($rows_member1 > 0){?>
            <div class="p-5 m-10">
                <h5 class="h-title t-uppercase"> قائمة المشتركين ( <? echo $rows_member1;?> )</h5>
            </div>
            <?
            while($fetch_member = $query_member1->fetch_assoc()){
                $data[] = $fetch_member;
            }
            foreach($data as $key=>$getmember){
                $user_id = $getmember['id'];
                $sql2 = "SELECT * FROM subscribe WHERE user_id = '$user_id'";
                $query_member2 = $this->connect()->query($sql2);
                $rows_member2 = $query_member2->num_rows;
                if($rows_member2 > 0){
                    $fetch_member2 = $query_member2->fetch_assoc();
                }
                ?>
                <main>
                    <div class="col-md-4 col-sm-6 col-xs-12 p-20 list-member m-5">
                        <div class="row">
                            <div class="col-xs-3 img-member text-center">
                                <img src="<? echo $getmember['avatar'];?>" class="img-circle" width="80" style="height:65px;">
                            </div>
                            <div class="col-xs-9 all-info">
                                <div class="member-info">
                                    <h4><label> <? echo $getmember['username'];?>  </label>&nbsp;&nbsp;<label><?
                                        if($fetch_member2['user_id'] == $getmember['id']){
                                            $get_status = $fetch_member2['status'];
                                        }else{
                                            $get_status = 3;
                                        }
                                        switch ($get_status) {
                                            case 1:
                                                echo '<i class="fa fa-money text-success"></i>';
                                                break;
                                        };
                                    ?>
                                    </label></h4>
                                    <label> <? echo $getmember['email'];?>  &nbsp;<i class="fa fa-envelope"></i></label><br>
                                    <label><i class="fa fa-phone">&nbsp;</i> </label>
                                    <label> <? echo $getmember['phone'];?> </label>&nbsp;&nbsp;&nbsp;
                                    <label><i class="fa fa-map-marker">&nbsp;</i>  <? echo $getmember['city'];?> / </label><label> <? echo $getmember['address'];?> </label>
                                </div><hr>
                                <div class="col-xs-10">
                                    <a href="index.php?page=Member_details&&user_id=<?echo $getmember['id'];?>&&p=member_info" class="show-details-member" data-toggle="modal">عرض تفاصيل أكثر >></a>
                                </div>
                                
                                <div class="col-xs-2a text-left delet-member">
                                    <a href="#delete-member" class="delete_member" data-toggle="modal" data-user="<? echo $getmember['id'];?>"><i class="fa fa-trash"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
            }
        }
    }
}
?>
