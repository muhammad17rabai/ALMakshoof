/************************************* reloading page ****************************************/
const refreshIndicator = document.getElementById('refresh-indicator');
const content = document.querySelector('.content');
let isDragging = false;
let startY = 0;
let currentY = 0;
let dragDistance = 0;

window.addEventListener('touchstart', (e) => {
  // Start dragging on touch
  if (window.scrollY === 0) { // Ensure drag only works when at the top of the page
    isDragging = true;
    startY = e.touches[0].clientY; // Store initial touch position
    dragDistance = 0;   // Reset drag distance
    content.classList.add('dragging'); // Add dragging class to move content
  }
});

window.addEventListener('touchmove', (e) => {
  if (!isDragging) return;

  // Get the current touch position
  currentY = e.touches[0].clientY;
  dragDistance = currentY - startY;

  if (dragDistance > 0) {
    // Only drag downwards
    refreshIndicator.style.display = 'block'; // Show the refresh indicator
    refreshIndicator.style.transform = `translateY(${dragDistance}px)`; // Move the indicator with the drag
  }
});

window.addEventListener('touchend', () => {
  if (!isDragging) return;

  isDragging = false;

  // If drag distance exceeds threshold, trigger refresh
  if (dragDistance > 100) {
    // Simulate the refresh by reloading the page (or any other custom action)
    setTimeout(() => {
      location.reload(); // Refresh the page
    }, 500); // Small delay before the refresh happens
  } else {
    // Reset the indicator and content position if threshold is not met
    refreshIndicator.style.transform = 'translateY(0)';
    setTimeout(() => {
      refreshIndicator.style.display = 'none'; // Hide the indicator after animation
    }, 300);
  }

  content.classList.remove('dragging'); // Reset content position
});

window.addEventListener('touchmove', (e) => {
  if (!isDragging) return;

  //e.preventDefault(); // Prevent page scrolling during the drag

  currentY = e.touches[0].clientY;
  dragDistance = currentY - startY;

  if (dragDistance > 0) {
    refreshIndicator.style.display = 'block'; 
    refreshIndicator.style.transform = `translateY(${dragDistance}px)`; 
  }
}, { passive: false }); // Disable passive event listener to allow preventDefault


/* Set the width of the side navigation to 250px */
function openNav_profile() {
  document.getElementById("mySidenav_p").style.width = "280px";
  document.getElementById("pageWrapper").classList.add("menu-overlay");
}

/* Set the width of the side navigation to 0 */
function closeNav_profile() {
  document.getElementById("mySidenav_p").style.width = "0";
  document.getElementById("pageWrapper").classList.remove("menu-overlay");
}

function openNav_notification() {
    document.getElementById("mySidenav_n").style.width = "280px";
    document.getElementById("pageWrapper").classList.add("menu-overlay");
  }
  
  /* Set the width of the side navigation to 0 */
  function closeNav_notification() {
    document.getElementById("mySidenav_n").style.width = "0";
    document.getElementById("pageWrapper").classList.remove("menu-overlay");
  }
/************************ end right slidbar ****************************/

/************************ Search ***************************************/
function search_item() {
    // Declare variables
    var input, filter, ul, a, i, txtValue, n;
    input = document.getElementById('search-input');
    filter = input.value.toUpperCase();
    ul = document.getElementById("myUL");
    li = ul.getElementsByTagName('main');
    //alert(li.length);
  
    // Loop through all list items, and hide those who don't match the search query
    for (i = 0; i < li.length; i++) {
      a = li[i].getElementsByTagName("div")[0];
      txtValue = a.textContent || a.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        li[i].style.display = "";
        document.getElementById("result-search").style.display = "none";
  
      } else {
        li[i].style.display = "none";
        document.getElementById("result-search").style.display = "block";
      }
    }
}
/************************ start dropdown menu **************************/

/********************* end dropdown menu ************************************/

(function($) {

    "use strict";

    $.fn.hasAttr = function(attr) {  
       if (typeof attr !== typeof undefined && attr !== false && attr !== undefined) {
            return true;
       }
       return false;
    };

    /*-------------------------------------
     Background Image Function
    -------------------------------------*/
    var background_image = function() {
        $("[data-bg-img]").each(function() {
            var attr = $(this).attr('data-bg-img');
            if (typeof attr !== typeof undefined && attr !== false && attr !== "") {
                $(this).css('background-image', 'url('+attr+')');
            }
        });  
    };

    /*-------------------------------------
     Background Color Function
    -------------------------------------*/
    var background_color = function() {
        $("[data-bg-color]").each(function() {
            var attr = $(this).attr('data-bg-color');
            if (typeof attr !== typeof undefined && attr !== false && attr !== "") {
                $(this).css('background-color', attr);
            }
        });  
    };

    var link_void = function() {
        $("a[data-prevent='default']").each(function() {
            $(this).on('click', function(e) {
                e.preventDefault();
            });
        });
    };

    /*-------------------------------------
     Preloader
    -------------------------------------*/
    var preloader = function() {
        if($('#preloader').length) {
            $('#preloader > *').fadeOut(); // will first fade out the loading animation
            $('#preloader').delay(150).fadeOut('slow'); // will fade out the white DIV that covers the website.
            $('body').delay(150).removeClass('preloader-active');
        }
    };

    /*-------------------------------------
     HTML attr direction
    -------------------------------------*/
    var html_direction = function() {
        var html_tag = $("html"),
            dir = html_tag.attr("dir"),
            directions = ['ltr', 'rtl'];
        if (html_tag.hasAttr('dir') && jQuery.inArray(dir, directions)) {
            html_tag.addClass(dir);
        } else {
            html_tag.attr("dir", directions[0]).addClass(directions[0]);
        }
    };
    

    /*-------------------------------------
     CSS fix for IE Mobile
    -------------------------------------*/
    var bugfix = function() {
        if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
          var msViewportStyle = document.createElement('style');
          msViewportStyle.appendChild(
            document.createTextNode(
              '@-ms-viewport{width:auto!important}'
            )
          );
          document.querySelector('head').appendChild(msViewportStyle);
        }
    };

    /*-------------------------------------
     Toggle Class function
    -------------------------------------*/
    var toogle_class = function() {
        $('[data-toggle-class]').each(function(){
            var current = $(this),
                toggle_event = current.data('toggle-event'),
                toggle_class = current.data('toggle-class');

            if (toggle_event == "hover") {
                current.on("mouseenter", function() {
                    if (current.hasClass(toggle_class) === false) {
                        $(this).addClass(toggle_class);
                    }
                });
                current.on("mouseleave", function() {
                    if (current.hasClass(toggle_class) === true) {
                        $(this).removeClass(toggle_class);
                    }
                });
            }
            current.on(toggle_event, function() {
                $(this).toggleClass(toggle_class);
            });
        });
    };


    /*-------------------------------------
     Back Top functions
    -------------------------------------*/
    var back_to_top = function() {
        var backTop = $('#backTop');
        if (backTop.length) {
            var scrollTrigger = 200,
                scrollTop = $(window).scrollTop();
            if (scrollTop > scrollTrigger) {
                backTop.addClass('show');
            } else {
                backTop.removeClass('show');
            }
        }
    };
    var click_back = function() {
        var backTop = $('#backTop');
        backTop.on('click', function(e) {
            $('html,body').animate({
                scrollTop: 0
            }, 700);
            e.preventDefault();
        });
    };

    /*-------------------------------------
     Navbar Functions
    -------------------------------------*/
    var navbar_js = function() {
        $('.dropdown-mega-menu > a, .nav-menu > li:has( > ul) > a').append("<span class=\"indicator\"><i class=\"fa fa-angle-down\"></i></span>");
        $('.nav-menu > li ul > li:has( > ul) > a').append("<span class=\"indicator\"><i class=\"fa fa-angle-left\"></i></span>");
        $(".dropdown-mega-menu, .nav-menu li:has( > ul)").on('mouseenter', function () {
            if ($(window).width() > 943) {
                $(this).children("ul, .mega-menu").fadeIn(100);
            }
        });
        $(".dropdown-mega-menu, .nav-menu li:has( > ul)").on('mouseleave', function () {
            if ($(window).width() > 943) {
                $(this).children("ul, .mega-menu").fadeOut(100);
            }
        });
        $(".dropdown-mega-menu > a, .nav-menu li:has( > ul) > a").on('click', function (e) {
            if ($(window).width() <= 943) {
                $(this).parent().addClass("active-mobile").children("ul, .mega-menu").slideToggle(150, function() {
                    
                });
                $(this).parent().siblings().removeClass("active-mobile").children("ul, .mega-menu").slideUp(150);
            }
            e.preventDefault();
        });
        $(".nav-toggle").on('click', function (e) {
            var toggleId = $(this).data("toggle");
            $(toggleId).slideToggle(150);
            e.preventDefault();
        });
    };
    var navbar_resize_load = function() {
        if ($(".nav-header").css("display") == "block") {
            $(".nav-bar").addClass('nav-mobile');
            $('.nav-menu').find("li.active").addClass("active-mobile");
        }
        else {
            $(".nav-bar").removeClass('nav-mobile');
        }

        if ($(window).width() >= 943) {
            $(".dropdown-mega-menu a, .nav-menu li:has( > ul) a").each(function () {
                $(this).parent().children("ul, .mega-menu").slideUp(0);
            });
            $($(".nav-toggle").data("toggle")).show();
            $('.nav-menu').find("li").removeClass("active-mobile");
        }
    };

    /*-------------------------------------
     Add Deal to Favorite
    -------------------------------------*/
    var add_favorite = function() {
        var like_btn = $('.actions .like-deal');
        like_btn.on('click',function(){
            $(this).toggleClass('favorite');
        });
    };

    /*-------------------------------------
     Carousel slider initiation
    -------------------------------------*/
    var owl_carousel = function() {
        $('.owl-slider').each(function () {
            var carousel = $(this),
                autoplay_hover_pause = carousel.data('autoplay-hover-pause'),
                loop = carousel.data('loop'),
                items_general = carousel.data('items'),
                margin = carousel.data('margin'),
                autoplay = carousel.data('autoplay'),
                autoplayTimeout = carousel.data('autoplay-timeout'),
                smartSpeed = carousel.data('smart-speed'),
                nav_general = carousel.data('nav'),
                navSpeed = carousel.data('nav-speed'),
                xxs_items = carousel.data('xxs-items'),
                xxs_nav = carousel.data('xxs-nav'),
                xs_items = carousel.data('xs-items'),
                xs_nav = carousel.data('xs-nav'),
                sm_items = carousel.data('sm-items'),
                sm_nav = carousel.data('sm-nav'),
                md_items = carousel.data('md-items'),
                md_nav = carousel.data('md-nav'),
                lg_items = carousel.data('lg-items'),
                lg_nav = carousel.data('lg-nav'),
                center = carousel.data('center'),
                dots_global = carousel.data('dots'),
                xxs_dots = carousel.data('xxs-dots'),
                xs_dots = carousel.data('xs-dots'),
                sm_dots = carousel.data('sm-dots'),
                md_dots = carousel.data('md-dots'),
                lg_dots = carousel.data('lg-dots');

            carousel.owlCarousel({
                rtl: true,
                autoplayHoverPause: autoplay_hover_pause,
                loop: (loop ? loop : false),
                items: (items_general ? items_general : 1),
                lazyLoad: true,
                margin: (margin ? margin : 0),
                autoplay: (autoplay ? autoplay : false),
                autoplayTimeout: (autoplayTimeout ? autoplayTimeout : 1000),
                smartSpeed: (smartSpeed ? smartSpeed : 250),
                dots: (dots_global ? dots_global : false),
                nav: (nav_general ? nav_general : false),
                navText: ["<i class='fa fa-angle-left' aria-hidden='true'></i>", "<i class='fa fa-angle-right' aria-hidden='true'></i>"],
                navSpeed: (navSpeed ? navSpeed : false),
                center: (center ? center : false),
                responsiveClass: true,
                responsive: {
                    0: {
                        items: ( xxs_items ? xxs_items : (items_general ? items_general : 1)),
                        nav: ( xxs_nav ? xxs_nav : (nav_general ? nav_general : false)),
                        dots: ( xxs_dots ? xxs_dots : (dots_global ? dots_global : false))
                    },
                    480: {
                        items: ( xs_items ? xs_items : (items_general ? items_general : 1)),
                        nav: ( xs_nav ? xs_nav : (nav_general ? nav_general : false)),
                        dots: ( xs_dots ? xs_dots : (dots_global ? dots_global : false))
                    },
                    768: {
                        items: ( sm_items ? sm_items : (items_general ? items_general : 1)),
                        nav: ( sm_nav ? sm_nav : (nav_general ? nav_general : false)),
                        dots: ( sm_dots ? sm_dots : (dots_global ? dots_global : false))
                    },
                    992: {
                        items: ( md_items ? md_items : (items_general ? items_general : 1)),
                        nav: ( md_nav ? md_nav : (nav_general ? nav_general : false)),
                        dots: ( md_dots ? md_dots : (dots_global ? dots_global : false))
                    },
                    1199: {
                        items: ( lg_items ? lg_items : (items_general ? items_general : 1)),
                        nav: ( lg_nav ? lg_nav : (nav_general ? nav_general : false)),
                        dots: ( lg_dots ? lg_dots : (dots_global ? dots_global : false))
                    }
                }
            });

        });
    };

    /*-------------------------------------
    /*-------------------------------------
     Stars Rating functions
    -------------------------------------*/
    var data_rating = function() {
        $('.rating').each(function () {
            var rating = $(this).find('.rating-stars').attr('data-rating'),
                rating_index = 5 - rating;
            $(this).find('.rating-stars > i').eq(rating_index).addClass('star-active');
        });
    };

    var do_rating = function() {
        var rating_stars_select = $('.rating .rating-stars.rate-allow');
        rating_stars_select.on('mouseenter', function () {
            $(this).find('i').removeClass('star-active');
        });
        rating_stars_select.on('mouseleave', function () {
            data_rating();
        });
        rating_stars_select.on('click', 'i', function () {
            var num_stars = $(this).siblings().length + 1,
                rating_index = $(this).index(),
                rating_count_select = $(this).parent().parent().find('.rating-count'),
                reviews_num = parseInt(rating_count_select.text(), 10),
                rate_value = num_stars - rating_index;
            reviews_num ++;

            $(this).parent().attr('data-rating', rate_value);
            data_rating();
            if ($(this).parent().attr('data-review')) {
                return false;
            }
            else {
                $(this).parent().attr('data-review', '1');
                rating_count_select.text(reviews_num);
            }
        });
    };

    /*-------------------------------------
     Deals Countdown function
    -------------------------------------*/
    var countdown = function(){
        var countdown_select = $("[data-countdown]");
        countdown_select.each(function(){
            $(this).countdown($(this).data('countdown'))
            .on('update.countdown', function(e){
                var format = '%H : %M : %S';
                if (e.offset.totalDays > 0) {
                    format = '%d يوم '+format;
                }
                if (e.offset.weeks > 0) {
                    format = '%w اسبوع '+format;
                }
                $(this).html(e.strftime(format));
            });
        }).on('finish.countdown', function(e){
            $(this).html('العرص اتاهىس').addClass('disabled');
        });
    };

    var buyTheme = function() {
        if (top.location!= self.location) {
           top.location = self.location.href;
        }
        if($('#buy_theme').length) {
            var buyBtn = $('#buy_theme');
            buyBtn.attr('href', window.location.href);
            buyBtn.on('click', function(){
                var affiliateLink = buyBtn.data('href');
                $("head").append('<meta http-equiv="refresh" content="1;url='+affiliateLink+'" />');
            });
        }
    };

    /* ================================
       When document is ready, do
    ================================= */
       
        $(document).on('ready', function() {
            buyTheme();
            preloader();
            $('[data-toggle="tooltip"]').tooltip();
            html_direction();
            background_color();
            background_image();
            link_void();
            click_back();
            bugfix();
            navbar_js();
            //share_social();
            add_favorite();
            owl_carousel();
            toogle_class();
            countdown();
            data_rating();
            do_rating();
            countdown();
            //cart_delete_item();
        });
        
    /* ================================
       When document is loading, do
    ================================= */
        
        $(window).on('load', function() {
            preloader();
            navbar_resize_load();
            //product_slider();
            /*$("body").animate({scrollTop: 1});*/
        }); 

    /* ================================
       When Window is resizing, do
    ================================= */
        
        $(window).on('resize', function() {
            navbar_resize_load();
        });

    /* ================================
       When document is Scrollig, do
    ================================= */
        
        $(window).on('scroll', function() {
            back_to_top();
        });

    
})(jQuery);
/********************************** Account Setting ********************************************************/
$(document).ready(function(){
    $(".btn-danger").click(function(){
        $(this).css("background-color","#dc3545")
        $(this).css("border-color","#dc3545")
    })
    $(".personal_info").click(function(){
      $("#personal-info,.title-info").show(250);
      $("#password-info,#address-info,#add-address,.title-pass,.title-address,.title-add-address").hide();
      $(this).css("background-color","#4de491b6")
      $(".pass_info,.address_info,.add_address_info").css("background-color","#fff")

    });

    $(".btn-edit-main-info").click(function(){
        $(".edit-info").show(250);
        $(".main-info").hide();
      });

    $(".pass_info").click(function(){
        $("#password-info,.title-pass").show(250);
        $("#personal-info,#address-info,#add-address,.title-info,.title-address,.title-add-address").hide();
        $(this).css("background-color","#4de491b6")
        $(".personal_info,.address_info,.add_address_info").css("background-color","#fff")
      });

      $(".address_info").click(function(){
        $("#address-info,#main-address-info,.title-address").show(250);
        $("#personal-info,#password-info,#add-address,.title-info,.title-pass,.title-add-address,#edit-address-info").hide();
        $(this).css("background-color","#4de491b6")
        $(".personal_info,.pass_info,.add_address_info").css("background-color","#fff")
      });

      $(".btn-edit-address").click(function(){
        $("#edit-address-info").show(250);
        $("#main-address-info").hide();
      });
      
      $(".add_address_info").click(function(){
        $("#address-info,#add-address,.title-add-address").show(250);
        $("#personal-info,#password-info,#main-address-info,.title-info,.title-pass,.title-address,#edit-address-info").hide();
        $(this).css("background-color","#4de491b6")
        $(".personal_info,.pass_info,.address_info").css("background-color","#fff")
      });
/******************************************* my profile ********************************************************/
/********************* avatar **********************/

$(document).ready(function() {
    var readURL = function(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.avatar').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    $(".box_add_avatar").on('change', function(){
        readURL(this);
        $(".btn-save-newavatar").show();
    });
});

$(".title-maininfo").click(function showbox(){
    $(".form-info").toggle();
    $(".edit-maininfo").hide();
    $(".edit-mainpass").hide();
    $(this).addClass("active")
    $('.title-editpass').removeClass("active");
})
$(".title-editpass").click(function showbox(){
    $(".form-info").hide();
    $(".edit-maininfo").hide();
    $(".edit-mainpass").show();
    $(this).addClass("active")
    $('.title-maininfo').removeClass("active");
})
$(".btn-edit-maininfo").click(function showbox(){
    $(".form-info").hide();
    $(".edit-maininfo").show();
    $(".edit-mainpass").hide();
})
/*********************************** list member ****************************************/
$('.btn_newsub').click(function(){
   var datasub = $(this).attr('data-sub');
   var data_sub = $(datasub);
   data_sub.toggle();
});

$('#choice_p').on('change', function(){
    var page = $(this).val();
    var user_id = $(this).attr('data-user_id');
    location.href = "index.php?page=Member_details&&user_id="+user_id+"&&p="+page;
})

$('#type_member_post').on('change', function(){
    var type = $(this).val();
    var user_id = $(this).attr('data-user_id');
    location.href = "index.php?page=Member_details&&user_id="+user_id+"&&p=member_posts&&type="+type;
})
$('#type_member').on('change', function(){
    var type = $(this).val();
    location.href = "index.php?page=Allmembers&&type="+type;
})
var count_m = $('.count_m').val();
$('#count_m').html(count_m);

/***************************  All page  ******************************************************/
$('.show-more').click(function(){
    $('.all-more-text').toggle();
    $(this).hide();
})
const allStar = document.querySelectorAll('.addrating .star')
const ratingValue = document.querySelector('.addrating input')

allStar.forEach((item, idx)=> {
	item.addEventListener('click', function () {
		let click = 1
		ratingValue.value = idx + 1

		allStar.forEach(i=> {
			i.classList.replace('bxs-star', 'bx-star')
			i.classList.remove('active')
		})
		for(let i=0; i<allStar.length; i++) {
			if(i <= idx) {
				allStar[i].classList.replace('bx-star', 'bxs-star')
				allStar[i].classList.add('active-star')
			} else {
				allStar[i].style.setProperty('--i', click)
				click++
			}
		}
        $('.star').click (function(){
            $('#rating').val($(this).attr('data-c'));
        });
        //alert(cc);
	})
})


$(document).ready(function() {
    var readURL = function(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                //$('.avatar').attr('src', e.target.result);
                var cc = input.files.length;
               for (let i = 0; i < cc; i++) {
                //var items = input.files.item(i).name;
                var div = document.getElementById('form-add-screenshot');
                var newdiv = document.createElement('div');
                newdiv.className = "col-xs-3 imgpages";
                var new_img = document.createElement('img');

                var sr =  e.target.result;
                new_img.src = sr;

                div.appendChild(newdiv);
                newdiv.appendChild(new_img);
                //$('.myImg_'+i).attr('src', e.target.result);
               }
            }
           
            reader.readAsDataURL(input.files[0]);
        }
    }
    $(".imgpage").on('change', function(){
        readURL(this);
        $('#c_img').val('1');
    });
});
$('#category').on('change', function(){
    var st = $(this).val();
    var tp = $(this).attr('data-type');
    location.href = "index.php?page=Allpage&&category="+st+"&&type="+tp;
})
$('#type').on('change', function(){
    var st = $(this).attr('data-category');
    var tp = $(this).val();
    location.href = "index.php?page=Allpage&&category="+st+"&&type="+tp;
})
$('#page_active').on('change', function(){
    var page_active = $(this).val();
    location.href = "index.php?page=Allpage&&page_active="+page_active;
})
var countpage = $('#countpage').val();
$('.count_category').html(countpage);
/************************************ Modal zoom image ************************************* */

$('.myImg').click(function(){
    var modal = $(this).attr('data-img');
    var detail = $(this).attr('data-detail');
    $(modal).toggle();
    var img_src = $(this).attr('src');
    $('.img01').attr('src',img_src);
    $(detail).hide();
    $('.input-group').hide();

    $(".modal-img").animate({scrollTop:$(document).height()}, 1000);

    $('.close-img').click(function(){
        var details = $(this).attr('data-details');
        $(modal).hide(); 
        $(details).show();
        $('.input-group').show();
    })
})
})
/****************************** orders **************************************************/
$('#choice_type').on('change', function(){
    var table = $(this).val();
    var st = $(this).attr('data-st');
    location.href = "index.php?page=orders&&type="+table+"&&st="+st;
})
$('#choice_status').on('change', function(){
    var table = $(this).attr('data-table');
    var st = $(this).val();
    location.href = "index.php?page=orders&&type="+table+"&&st="+st;
})

/**************************** My Posts *************************************************/
$('#type_post').on('change', function(){
    var type = $(this).val();
    location.href = "index.php?page=Myposts&&type="+type;
})
/*************************** pendding result *******************************************/
$('.edit-pendding').click(function() {
    var show = $(this).attr('data-show');
    $('#'+show).toggle();

    var hide = $(this).attr('data-hide');
    $('.'+hide).toggle();
})

$('.delete-pendding').click(function() {
    var show = $(this).attr('data-show');
    $('#'+show).toggle();

    var hide = $(this).attr('data-hide');
    $('.'+hide).toggle();
})
/*************************** reject result *******************************************/
$('.edit-reject').click(function() {
    var show = $(this).attr('data-show');
    $('#'+show).toggle();

    var hide = $(this).attr('data-hide');
    $('.'+hide).toggle();
})
$('.delete-reject').click(function() {
    var show = $(this).attr('data-show');
    $('#'+show).toggle();

    var hide = $(this).attr('data-hide');
    $('.'+hide).toggle();
})
/**************************** product details ******************************************/
$('.small_img').click(function() {
    var src = $(this).attr('src');
    $('#big_img').attr('src',src);
})
/****************************** Subscribe  ****************************************/
$('.btn_subscribe').click(function (){
    $('#new_subscribe').toggle();
})
$('#sub_period').on('change',function(){
    var period = $(this).val();
    if (period == 3) {
        $('.sub_price').html('50 شيكل')
        $('#total').val('50');
    }else if(period == 6){
        $('.sub_price').html('100 شيكل')
        $('#total').val('100');
    }else if(period == 1){
        $('.sub_price').html('200 شيكل')
        $('#total').val('200');
    }else{
        $('.sub_price').html('0 شيكل')
    }
})
$('#choice_status_sub').on('change', function(){
    var st = $(this).val();
    location.href = "index.php?page=Allsubscribe&&st="+st;
})

var date = $('#end_date').val();
var countDownDate = new Date(date).getTime();
//var d = new Date(date).getDate();
var m = new Date(date).getMonth();
var y = new Date(date).getFullYear();
var mm = new Date().getMonth();
var yy = new Date().getFullYear();
// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();

  // Find the distance between now and the count down date
  var distance = countDownDate - now;

  // Time calculations for days, hours, minutes and seconds
  var months = Math.floor(distance / (1000 * 60 * 60 * 24 * 31));
  var days = Math.floor((distance % (1000 * 60 * 60 * 24 * 31)) / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

  // Display the result in the element with id="demo"
  $("#remain_sub").html(months + ' شهر و '  + days + ' يوم و ' + hours + ' ساعة و ' + minutes + ' دقيقة و ' + seconds + ' ثانية ');
  $("#remain_sub").addClass('alert-success');
  if ((days == 1 || days == 2 || days == 3) && (m+1 == mm+1 && y == yy)) {
    $("#remain_mainsub").html(days + ' يوم و ' + hours + ' ساعة و ' + minutes + ' دقيقة و ' + seconds + ' ثانية ');
    $("#remain_mainsub").addClass('alert-success');  
    $(".remain_mainsub").show();  
  }else if(days == 0){
    if ((hours > 0 || minutes > 0 || seconds > 0) && (m+1 == mm+1 && y == yy)) {
        $("#remain_mainsub").html(days + ' يوم و ' + hours + ' ساعة و ' + minutes + ' دقيقة و ' + seconds + ' ثانية ');
        $("#remain_mainsub").addClass('alert-success');  
        $(".remain_mainsub").show(); 
    }
  }

  // If the count down is finished, write some text
  if (distance < 0) {
    clearInterval(x);
    $("#remain_sub").html("انتهى الاشتراك");
    $("#remain_sub").addClass('alert-danger');
  }
}, 1000);

/************************ brokers **************************/
$('.broker_info').click(function(){
    $('a',this).addClass('active');
    $('li.broker_rating>a').removeClass('active');
    $('#broker_info').show(300);
    $('#broker_rating').hide();
    $('#title_status_broker').show();
});
$('.broker_rating').click(function(){
    $('a',this).addClass('active');
    $('li.broker_info>a').removeClass('active');
    $('#broker_info').hide();
    $('#broker_rating').show();
    $('#title_status_broker').hide();
});
/********************************** Finger browser *********************************/
function createFingerprint(string) {
    var hash = 0, i, chr;
    if (string.length === 0) return hash;
    for (i = 0; i < string.length; i++) {
        chr = string.charCodeAt(i);
        hash = ((hash << 5) - hash) + chr;
        hash |= 0;
    }
    return hash;
};

function generateAndStoreFingerprint() {
    // Check if a fingerprint already exists in localStorage
    var existingFingerprint = localStorage.getItem('device_fingerprint');
    
    if (existingFingerprint) {
        return existingFingerprint;  // Use existing fingerprint from storage
    } else {
        var canvas = document.createElement('canvas');
        var gl = canvas.getContext('webgl') || canvas.getContext('experimental-webgl');
        var graphics_card = gl ? gl.getParameter(gl.RENDERER) : "unknown";

        var fingerprint_string = [
            window.screen.availWidth + "x" + window.screen.availHeight,
            window.screen.colorDepth,
            navigator.userAgent,
            navigator.platform,
            navigator.language,
            navigator.hardwareConcurrency,
            navigator.cookieEnabled,
            graphics_card
        ].join("_");

        var finger = createFingerprint(fingerprint_string);

        // Store the generated fingerprint in localStorage
        localStorage.setItem('device_fingerprint', finger);

        return finger;  // Return the newly generated fingerprint
    }
}

// Get the fingerprint and store it in a hidden form input or use it as needed
var finger = generateAndStoreFingerprint();
$('#finger').val(finger); // jQuery example, use `document.getElementById('finger').value = finger;` for vanilla JS

