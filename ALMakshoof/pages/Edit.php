<?php
if(!isset($_SESSION['id'])){
    header("location:index.php?page=login");
}
require_once './functions/edit_func.php';
$edit = new EDIT;
?>
<div class="form-add mt-10 m-5">
    <div class="btn-info p-5">
        <h5> تعديل الطلب </h5>
    </div>
    <?
        if (validation($_GET['S'] == 'edit_page')) {
            $edit->edit_pagereq();
            $edit->showinfo_page();
        }elseif(validation($_GET['S'] == 'edit_product')){
            $edit->edit_product_req();
            $edit->showinfo_product();
        }
        
    ?>
</div>