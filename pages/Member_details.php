<?php
     $role = new role;
    if(!isset($_SESSION['id'])){
        header("location:index.php?page=login");
    }elseif($role->r('role') != 2){
        header("location:index.php?page=home");
    }
    require_once './functions/manage_member_func.php';
    require_once './functions/brokers_func.php';
    require_once './functions/subscribe_func.php';
    require_once './functions/myprofile_func.php';
    $user_id = validation($_GET['user_id']);
    $p = validation($_GET['p']);
    switch ($p) {
        case 'member_info':
            $tt = " المعلومات الشخصية ";
            break;
        case 'member_pass':
            $tt = " كلمة السر ";
            break;
        case 'member_posts':
            $tt = " الصفحات والمنتجات ";
            break;
        case 'member_broker':
            $tt = " وساطة المتاجر ";
            break;
        case 'member_sub':
            $tt = " طلبات الاشتراك ";
            break;
        case 'member_devices':
            $tt = "  الأجهزة المتصلة ";
            break;
    }
?>
<!-- –––––––––––––––[ PAGE CONTENT ]––––––––––––––– -->
<main id="mainContent" class="main-content">
    <!-- Page Container -->
    <div class="page-container">
        <div class="container">
            <form action="index.php?page=Member_details&&user_id=<? echo $user_id;?>&&p=<? echo $p;?>" method="post" enctype="multipart/form-data">
                <div class="row" id="myUL">
                    <div class="col-xs-12 mt-15 d-flex justify-content-between">
                        <label class="form-control bg-white text-st col-xs-6"> الحركات : </label>
                        <select class="form-control bg-white" name="choice-p" id="choice_p" data-user_id="<? echo $user_id;?>">
                            <option value=""><? echo $tt;?></option>
                            <option value="member_info"> المعلومات الشخصية  </option>
                            <option value="member_pass"> كلمة السر </option>
                            <option value="member_posts">  الصفحات والمنتجات </option>
                            <option value="member_broker">  وساطة المتاجر </option>
                            <option value="member_sub">  طلبات الاشتراك </option>
                            <option value="member_devices">   الأجهزة المتصلة </option>
                        </select>
                    </div>
                </div>
                <div class="row">
                <?
                    $getmember = new Manage_member;
                    $getmember_info = new MyProfile;
                    $getmember_broker = new Brokers;
                    $getmember_sub = new Newsubscribe;

                    function form_password()
                    {?>
                        <div class="text-center" id="result-pass"></div>
                            <div class="p-10 header mt-15"><h6> تعديل كلمة السر </h6></div>
                            <div class="col-xs-12 p-20 form-add">
                                <div class="form-group">
                                    <h5> كلمة السر الجديدة : <input type="password" id="newpass" class="form-control" placeholder="كلمة السر الجديدة" required/></h5>
                                </div>
                                <div class="form-group">
                                    <h5>  تأكيد كلمة السر الجديدة : <input type="password" id="re_newpass" class="form-control" placeholder=" تأكيد كلمة السر الجديدة" required/></h5>
                                </div>
                                <div class="form-group btn-save-newpassword">
                                    <button type="button" class="btn btn-info btn-edit-newpass" id="btn_savenewpass_member" data-id="<? echo validation($_GET['user_id']);?>">حفظ</button>
                                </div>
                            </div>
                    <?}
                    switch ($p) {
                        case 'member_info':
                            $getmember_info->edit_myinfo();
                            $getmember_info->getmyinfo();
                            break;
                        case 'member_pass':
                            form_password();
                            break;
                        case 'member_posts':
                            $getmember->member_posts();
                            break;
                        case 'member_broker':
                            $getmember_broker->form_edit_broker();
                            $getmember_broker->broker_details();
                            break;
                        case 'member_sub':
                            $getmember_sub->getorder_subscribe();
                            break;
                        case 'member_devices':
                            $getmember->member_devices();
                            break;
                    }
                ?>
                </div>
                <div id="result-search">
                    <? box_alert('secondary','لا توجد نتائج');?>
                </div>
            </form>
        </div>
    </div>
</main>