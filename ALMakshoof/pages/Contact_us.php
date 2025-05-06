<?
if(!isset($_SESSION['id'])){
    header("location:index.php?page=login");
}
require_once './functions/messages_func.php';
$messages = new Messages;
?>
<!-- Page Container -->
<div class="page-container">
    <div class="container">
        <div class="row mt-5">
            <div class="p-10 header mt-15"><h6> اتصل بنا </h6></div>
            <div class="col-xs-12">
                <?php
                    //$addpage = new ManagePage;
                    //$addpage->addpage();
                ?>
            </div>
            <div class="form-add p-10">
                <div id="result-contact"></div>
                <form action="index.php?page=Contact_us" method="post" enctype="multipart/form-data">
                    <div class="form-group pagename">
                        <label for="sender"><i class="fa fa-user"></i> اسم المرسل  : </label>
                        <span class="form-control"> <? echo $messages->getsender();?> </span>                    
                    </div>
                    <div class="form-group">
                        <label for="receiver"><i class="fa fa-user"></i> اسم المستقبل : </label>
                        <span class="form-control">مسؤول النظام</span>
                    </div>
                    <div class="form-group descreption">
                        <label for="descreption"><i class="fa fa-pencil-square-o"></i>   الرسالة : </label>
                        <textarea name="body_contact" class="form-control" id="body_contact" cols="20" rows="5" placeholder="تقاصيل الرسالة ..." required><? echo $_POST['descpage'];?></textarea>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-info" id="send_contact" data-id="<? echo validation($_SESSION['id'])?>"> ارسال <i class="fa fa-send"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Page Container -->