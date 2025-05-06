function swals(title) {
    Swal.fire({
        position: "top",
        icon: "success",
        title: title,
        showConfirmButton: false,
        timer: 1800
    })
}
//////////////////////////// password /////////////////////////////////
$('#btn_savenewpass').click(function(){
    var user_id = $(this).attr("data-id");
    var oldpass = $("#oldpass").val();
    var newpass = $("#newpass").val();
    var re_newpass = $("#re_newpass").val();
    $.ajax({
        url:"./functions/myprofile_func.php?f=editpass",
        type:'POST',
        cache:false,
        data:{user_id:user_id,oldpass:oldpass,newpass:newpass,re_newpass:re_newpass},
        dataType:"text",
        success:function(data){
            $("#result-pass").html(data);
            if($('#success').val() == "success"){
                swals(" تم تعديل كلمة السر بنجاح ")
                setInterval(function () {location.reload()}, 2000);
            }
        }
    })
})
$('#btn_savenewpass_member').click(function(){
    var user_id = $(this).attr("data-id");
    var newpass = $("#newpass").val();
    var re_newpass = $("#re_newpass").val();
    $.ajax({
        url:"./functions/manage_member_func.php?f=edit_memberpass",
        type:'POST',
        cache:false,
        data:{user_id:user_id,newpass:newpass,re_newpass:re_newpass},
        dataType:"text",
        success:function(data){
            $("#result-pass").html(data);
            if($('#success').val() == "success"){
                swals(" تم تعديل كلمة السر بنجاح ")
                setInterval(function () {location.reload()}, 2000);
            }
        }
    })
})

$('#save_newpass_recover').click(function(){
    var user_id = $(this).attr("data-id");
    var n = $(this).attr("data-n");
    var newpass = $("#newpass").val();
    var re_newpass = $("#re_newpass").val();
    $.ajax({
        url:"./functions/recover_password_func.php?f=recover_newpass",
        type:'POST',
        cache:false,
        data:{user_id:user_id,n:n,newpass:newpass,re_newpass:re_newpass},
        dataType:"text",
        success:function(data){
            $("#result_recoverpassword").html(data);
            if($('#success').val() == "success"){
                swals(" تم تعديل كلمة السر بنجاح ")
                setInterval(function () {location.href = "index.php?page=login"}, 2000);
            }
        }
    })
})
/////////////////////////// members ///////////////////////////////////
$('.save_newsub').click(function(){
    //$('#bb').html('mmmm');
    var periods = 0;
    periods = $("input[type='radio'][name='period']:checked").val();
    var user_id=$(this).attr('data-user');
    var st = $('#st').val();
    if(periods > 0){
        $('.loader').toggle();
        $.ajax({
            url:"./functions/allmembers_func.php",
            type:'POST',
            cache:false,
            data:{periods:periods,user_id:user_id,st:st},
            dataType:"text",
            success:function(data){
                $("#alertsub_"+user_id).show();
                $("#alertsub_"+user_id).html(data);
                $(".modal").animate({ scrollTop: 0 }, "fast");
                $("html, body").animate({ scrollTop: 0 }, "fast");
                setInterval(function () {location.reload()},2000);
            }
        });
    }
  });

$('.save_editmember').click(function(){
    var user_id=$(this).attr('data-user');
    var name = $('#name_'+user_id).val();
    var email = $('#email_'+user_id).val();
    var phone = $('#phone_'+user_id).val();
    var city = $('#city_'+user_id).val();
    var address = $('#address_'+user_id).val();
    var gender = $('#gender_'+user_id).val();
    var active = $('#active_'+user_id).val();
    var f = "newedit";
    $.ajax({
        url:"./functions/manage_member_func.php",
        type:'POST',
        cache:false,
        data:{name:name,email:email,phone:phone,city:city,address:address,gender:gender,active:active,user_id:user_id,f:f},
        dataType:"text",
        success:function(data){
            if($('#error').val() == "error"){
                $('#loader_'+user_id).show();
            }
            $('#result_'+user_id).show();
            $('#result_'+user_id).html(data);
            $("html, body").animate({ scrollTop: 0 }, "fast");
            $(".modal").animate({ scrollTop: 0 }, "fast");
            if($('#success').val() == "success"){
                swals('تم تعديل البيانات بنجاح');
                setInterval(function () {location.reload()}, 2000);
            }
        }
    });
  });
  $('.delete_member').click(function(){
    var user_id=$(this).attr('data-user');
    var f = 'delete';
    $('#btn_deletemember').click(function(){
        $.ajax({
            url:"./functions/manage_member_func.php",
            type:'POST',
            cache:false,
            data:{user_id:user_id,f:f},
            dataType:"text",
            success:function(data){
                $('#result').html(data);
                if($('#success').val() == "success"){
                    swals("تم حذف العضو بنجاح")
                    setInterval(function () {location.reload()}, 2000);
                }
            }
        })
    })
})
/********************* pages ***************************/
$('.del_order').click(function(){
    var id = $(this).attr('data-id');
    var user_id = $(this).attr('data-userid');
    var table = $(this).attr('data-t');
    var img = $(this).attr('data-img');
    var routs = $(this).attr('data-routs');
    $.ajax({
        url:"./functions/actions_func.php?action=delorder",
        type:'POST',
        cache:false,
        data:{id:id,user_id:user_id,table:table,img:img},
        dataType:"text",
        success:function(data){
            $("#result").html(data);
            if($('#success').val() == "success"){
                swals("تم حذف الطلب بنجاح")
                setInterval(function () {location.href = "index.php?page="+routs}, 2000);
            }
        }
    })
})
/********************* orders ***************************/
$('.accept_order').click(function(){
    var id = $(this).attr('data-id');
    var user_id = $(this).attr('data-userid');
    var table = $(this).attr('data-t');
    var img = $(this).attr('data-img');
    var routs = $(this).attr('data-routs');
    $.ajax({
        url:"./functions/actions_func.php?action=acceptorder",
        type:'POST',
        cache:false,
        data:{id:id,user_id:user_id,table:table,img:img},
        dataType:"text",
        success:function(data){
            $("#result").html(data);
            if($('#success').val() == "success"){
                swals("تم قبول الطلب بنجاح")
                setInterval(function () {location.href = "index.php?page="+routs}, 2000);
            }
        }
    })
})
$('.del_order').click(function(){
    var id = $(this).attr('data-id');
    var user_id = $(this).attr('data-userid');
    var table = $(this).attr('data-t');
    var img = $(this).attr('data-img');
    var routs = $(this).attr('data-routs');
    $.ajax({
        url:"./functions/actions_func.php?action=delorder",
        type:'POST',
        cache:false,
        data:{id:id,user_id:user_id,table:table,img:img},
        dataType:"text",
        success:function(data){
            $("#result").html(data);
            if($('#success').val() == "success"){
                swals("تم حذف الطلب بنجاح")
                setInterval(function () {location.href = "index.php?page="+routs}, 2000);
            }
        }
    })
})
$('.pendding_order').click(function(){
    var id = $(this).attr('data-id');
    var user_id = $(this).attr('data-userid');
    var table = $(this).attr('data-t');
    var notes = $('#pendding_result'+id).val();
    var routs = $(this).attr('data-routs');
    $.ajax({
        url:"./functions/actions_func.php?action=pendding",
        type:'POST',
        cache:false,
        data:{id:id,user_id:user_id,table:table,notes:notes},
        dataType:"text",
        success:function(data){
            $("#result-pendding").html(data);
            if($('#success').val() == "success"){
                swals(" تم تعليق الطلب ")
                setInterval(function () {location.href = "index.php?page="+routs}, 2000);
            }
        }
    })
})
$('.save_edit_pendding').click(function(){
    var id = $(this).attr('data-id');
    var note = $(this).attr('data-note');
    var notes = $('#'+note).val();
    var routs = $(this).attr('data-routs');
    var table = $(this).attr('data-t');
    $.ajax({
        url:"./functions/actions_func.php?action=editpendding",
        type:'POST',
        cache:false,
        data:{id:id,notes:notes,table:table},
        dataType:"text",
        success:function(data){
            $("#result-pendding"+id).html(data);
            if($('#success').val() == "success"){
                swals(" تم التعديل بنجاح ")
                setInterval(function () {location.href = "index.php?page="+routs}, 2000);
            }
        }
    })
})
$('.del_pendding').click(function(){
    var id = $(this).attr('data-id');
    var routs = $(this).attr('data-routs');
    $.ajax({
        url:"./functions/actions_func.php?action=delpendding",
        type:'POST',
        cache:false,
        data:{id:id},
        dataType:"text",
        success:function(data){
            $("#result-pendding"+id).html(data);
            if($('#success').val() == "success"){
                swals(" تم حذف التعليق بنجاح ")
                setInterval(function () {location.href = "index.php?page="+routs}, 2000);
            }
        }
    })
})
$('.reject_order').click(function(){
    var id = $(this).attr('data-id');
    var user_id = $(this).attr('data-userid');
    var table = $(this).attr('data-t');
    var notes = $('#reject_result'+id).val();
    var routs = $(this).attr('data-routs');
    $.ajax({
        url:"./functions/actions_func.php?action=reject",
        type:'POST',
        cache:false,
        data:{id:id,user_id:user_id,table:table,notes:notes},
        dataType:"text",
        success:function(data){
            $("#result-reject").html(data);
            if($('#success').val() == "success"){
                swals(" تم رفض الطلب ")
                setInterval(function () {location.href = "index.php?page="+routs}, 2000);
            }
        }
    })
})
$('.save_edit_reject').click(function(){
    var id = $(this).attr('data-id');
    var notes = $('#reject_edit'+id).val();
    var routs = $(this).attr('data-routs');
    var table = $(this).attr('data-t');
    $.ajax({
        url:"./functions/actions_func.php?action=editreject",
        type:'POST',
        cache:false,
        data:{id:id,notes:notes,table:table},
        dataType:"text",
        success:function(data){
            $("#result-reject").html(data);
            if($('#success').val() == "success"){
                swals(" تم التعديل بنجاح ")
                setInterval(function () {location.href = "index.php?page="+routs}, 2000);
            }
        }
    })
})
$('.del_reject').click(function(){
    var id = $(this).attr('data-id');
    var routs = $(this).attr('data-routs');
    $.ajax({
        url:"./functions/actions_func.php?action=delreject",
        type:'POST',
        cache:false,
        data:{id:id},
        dataType:"text",
        success:function(data){
            $("#result-reject"+id).html(data);
            if($('#success').val() == "success"){
                swals(" تم حذف سبب الرفض بنجاح ")
                setInterval(function () {location.href = "index.php?page="+routs}, 2000);
            }
        }
    })
})
/***************************************** Rating *************************************/
$('.save_edit_rate').click(function(){
    var id = $(this).attr('data-id');
    var rate = $('#rating').val();
    var notes = $('#comment_rate').val();
    //var routs = $(this).attr('data-routs');
    $.ajax({
        url:"./functions/rating_func.php?f=editrating",
        type:'POST',
        cache:false,
        data:{id:id,rate:rate,notes:notes},
        dataType:"text",
        success:function(data){
            $("#result-edit").html(data);
            if($('#success').val() == "success"){
                swals(" تم التعديل بنجاح ")
                setInterval(function () {location.reload()}, 2000);
            }
        }
    })
})
$('.delete_rate').click(function(){
    var id = $(this).attr('data-id');
    $.ajax({
        url:"./functions/rating_func.php?f=delrating",
        type:'POST',
        cache:false,
        data:{id:id},
        dataType:"text",
        success:function(data){
            $("#result-edit").html(data);
            if($('#success').val() == "success"){
                swals(" تم حذف التقييم بنجاح ")
                setInterval(function () {location.reload()}, 2000);
            }
        }
    })
})
/*************************************** Whish list ************************************/
$('.favorate').click(function(){
    var user_id = $(this).attr('data-userid');
    var item_id = $(this).attr('data-itemid');
    var id = $(this).attr('data-id');
    var type = $(this).attr('data-type');
    var i = $(this).children('i');
    var u = $(this);
    var uu = $(this).attr('data-u');
    var v = $(this).attr('data-p'); 
    if (uu == 0) {
        $.ajax({
            url:"./functions/favorate_func.php?f=fav",
            type:'POST',
            cache:false,
            data:{user_id:user_id,item_id:item_id,type:type},
            dataType:"text",
            success:function(data){
                $("#result-fav").html(data);
                if($('#success').val() == "success"){
                    swals(" تم الاضافة الى قائمة المفضلة بنجاح ");
                    i.addClass('text-danger');
                    u.attr('data-u','1');
                    setInterval(function () {location.reload()}, 2000);
                }
            }
        })   
    }else{
        $.ajax({
            url:"./functions/favorate_func.php?f=removefav",
            type:'POST',
            cache:false,
            data:{id:id},
            dataType:"text",
            success:function(data){
                $("#result-fav").html(data);
                if($('#success').val() == "success"){
                    swals(" تم الازالة من قائمة المفضلة بنجاح ");
                    i.removeClass('text-danger');
                    u.attr('data-u','0');
                    if (v == "fav") {
                        setInterval(function () {location.reload()}, 2000);
                    }
                }
            }
        }) 
    }
})
/*************************************** Notifications **************************************/
$('.close-n').click(function(){
    var id = $(this).attr('data-id');
        $.ajax({
            url:"./functions/notifications_func.php?f=del",
            type:'POST',
            cache:false,
            data:{id:id},
            dataType:"text",
            success:function(data){
                console.log(data);
            }
        })
    })
/***************************************** Sites *************************************/
$('#delete_sites').click(function(){
    var id = $(this).attr('data-id');
        $.ajax({
            url:"./functions/sites_func.php?f=del",
            type:'POST',
            cache:false,
            data:{id:id},
            dataType:"text",
            success:function(data){
                $('#result_del').html(data);
                if($('#success').val() == "success"){
                    swals(" تم حذف المتجر بنجاح ");
                    setInterval(function () {location.href = "index.php?page=Allsites"}, 2000);
                }
            }
        })
    })
    $('#save_broker').click(function(){
        var user_id = $(this).attr('data-userid')
        var count = $('.sites_check:checked').length;
        var sites = new Array();
        $(".sites_check:checked").each(function() {
           sites.push($(this).val());
        });
        var code = $('#code').val();
        var phone = $('#phone').val();
        var period = $('#period').val();
        var commission = $('#commission').val();
        var counts = $('.social').filter(function() {
            return this.value.trim() != '';
        }).length;
        var full_social = $('.social').filter(function() {
            return this.value.trim() != '';
        });
        var social = new Array();
        $(full_social).each(function() {
            social.push($(this).val());
         });
         var type_social = new Array();
        $(full_social).each(function() {
            type_social.push($(this).attr('data-type'));
         });
        $.ajax({
            url:"./functions/brokers_func.php?f=save",
            type:'POST',
            cache:false,
            data:{user_id:user_id,count:count,counts:counts,code:code,phone:phone,sites:sites,period:period,commission:commission,social:social,type_social:type_social},
            dataType:"text",
            success:function(data){
                $('#result').html(data);
                $(".modal").animate({ scrollTop: 0 }, "fast");
                $("html, body").animate({ scrollTop: 0 }, "fast");
                if($('#success').val() == "success"){
                    swals(" تم تقديم الطلب بنجاح ");
                    setInterval(function () {location.reload()}, 2000);
                }
            }
        })
    })
    $('#save_editbroker').click(function(){
        var user_id = $(this).attr('data-userid');
        var broker_id = $('#broker_id').val();
        var sites = new Array();
        var r_sites = new Array();
        $('.new_sites_check').each(function() {
        if ($(this).is(':checked')) {
            sites.push($(this).val());
        }else{
            r_sites.push($(this).val());
        }
        })
        var count_site = sites.length;
        var count_r_site = r_sites.length;

        var site_id = new Array();
        $(".sites_check:checked").each(function() {
            site_id.push($(this).attr('data-site_id'));
        });

        var code = $('#codee').val();
        var phone = $('#new_phone').val();
        var period = $('#periods').val();
        var commission = $('#comission').val();
        var active = $('#active').val();

        var counts = $('.social').filter(function() {
            return this.value.trim() != '';
        }).length;
        var full_social = $('.social').filter(function() {
            return this.value.trim() != '';
        });
        var social_id = new Array();
            $(full_social).each(function() {
                social_id.push($(this).attr('data-social_id'));
            });
        var social_val = new Array();
            $(full_social).each(function() {
            social_val.push($(this).val());
        });
        var social_type = new Array();
            $(full_social).each(function() {
                social_type.push($(this).attr('data-type'));
            });
        var social = [
            {
                id : social_id,
                val : social_val,
                type : social_type, 
            },
        ]
        var social_remove = $('.social').filter(function() {
            return this.value == '';
        });
        var social_remove_id = new Array();
            $(social_remove).each(function() {
                var remove_id = $(this).attr('data-social_id');
                if (remove_id != null) {
                    social_remove_id.push(remove_id);  
                }
            });
        $.ajax({
            url:"./functions/brokers_func.php?f=save_edit",
            type:'POST',
            cache:false,
            data:{user_id:user_id,broker_id:broker_id,count_site:count_site,count_r_site:count_r_site,counts:counts,code:code,phone:phone,sites:sites,r_sites:r_sites,site_id:site_id,
                    period:period,commission:commission,active:active,social:social,social_remove_id:social_remove_id},
            dataType:"text",
            success:function(data){
                $('#result_edit').html(data);
                $(".modal").animate({ scrollTop: 0 }, "fast");
                $("html, body").animate({ scrollTop: 0 }, "fast");
                if($('#success').val() == "success"){
                    swals(" تم تعديل البيانات بنجاح ");
                    setInterval(function () {location.reload()}, 2000);
                }
            }
        })
    })
/****************************** MESSAGES *******************************************/
$('#send_contact').click(function(){
    var sender_id = $(this).attr('data-id');
    var body = $('#body_contact').val();
    $.ajax({
        url:"./functions/messages_func.php?f=send_contact",
        type:'POST',
        cache:false,
        data:{sender_id:sender_id,body:body},
        dataType:"text",
        success:function(data){
            $('#result-contact').html(data);
            if($('#contact').val() == "success"){
                Swal.fire({
                    position: "top",
                    icon: "success",
                    title: " تم ارسال رسالتك بنجاح , سيتم الرد عليك في أقرب وقت ممكن ",
                    showConfirmButton: false,
                    timer: 2800
                })
                setInterval(function () {location.href = "index.php?page=Messages"}, 3000);
            }
        }
    })
})
function get_chat(message_id) {
    var user_id = $('#user_id').val();
    var r = $('#r').val();
    $.ajax({
        url:"./functions/messages_func.php?f=get_chat",
        type:'POST',
        cache:false,
        data:{user_id:user_id,r:r,message_id:message_id},
        dataType:"text",
        success:function(data){
            var scrollTop = $(window).scrollTop();
            var scrollBottom = $(document).height() - $(window).height() - scrollTop;
            $(window).animate({ scrollBottom: 0 }, "fast");
            $('#result-chat').html(data);
        }
    })
}
const url = window.location.search;
const urlParams = new URLSearchParams(url);
var page = urlParams.get('page');
var message_id = urlParams.get('message_id');
var s = 0;
if (page == 'Chat') {
    window.scrollTo(0, document.documentElement.scrollHeight || document.body.scrollHeight);
    get_chat(message_id);
    
    setInterval(function () {get_chat(message_id)}, 2000);
    window.addEventListener('load', function() {
        window.scrollTo(0, document.body.scrollHeight);
    });
    }

$('#send_chat').click(function(){
    var message_id = $(this).attr('data-message_id');
    var user_id = $('#user_id').val();
    var body = $('#body_chat').val();
    $.ajax({
        url:"./functions/messages_func.php?f=send_chat",
        type:'POST',
        cache:false,
        data:{user_id:user_id,message_id:message_id,body:body,page:page},
        dataType:"text",
        success:function(data){
            $('#dd').html(data);
            var scrollTop = $(window).scrollTop();
            var scrollBottom = $(document).height() - $(window).height() - scrollTop;
            $(window).animate({ scrollBottom: 0 }, "fast");
            $('#body_chat').val(" ");
            get_chat(message_id);
        }
    })
})
/************************************************** Devices ***********************************************/
$('.save_edit_device').click(function(){
    var id = $(this).attr('data-id');
    var status = $('#device_status').val();
    $.ajax({
        url:"./functions/devices_func.php?f=edit_status",
        type:'POST',
        cache:false,
        data:{id:id,status:status},
        dataType:"text",
        success:function(data){
            $('#result').html(data);
            if($('#success').val() == "success"){
                swals(" تم تعديل الحالة بنجاح ");
                setInterval(function () {location.reload()}, 2000);
            }
        }
    })
})
$('.del_device').click(function(){
    var id = $(this).attr('data-id');
    $.ajax({
        url:"./functions/devices_func.php?f=del_device",
        type:'POST',
        cache:false,
        data:{id:id},
        dataType:"text",
        success:function(data){
            $('#result').html(data);
            if($('#success').val() == "success"){
                swals(" تم حذف الجهاز بنجاح ");
                setInterval(function () {location.reload()}, 2000);
            }
        }
    })
})