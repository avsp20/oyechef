$(document).ready(function(){
  $('.section-page-content .mobile-sidebar .filter-tab .sidebar-title').on('click', function(){
    $('.section-page-content .mobile-sidebar .homestore-tab .sidebar-title').addClass('close');
    $('.section-page-content .mobile-sidebar .filter-tab .sidebar-title').toggleClass('close');
  });
  $('.section-page-content .mobile-sidebar .homestore-tab .sidebar-title').on('click', function(){
    $('.section-page-content .mobile-sidebar .homestore-tab .sidebar-title').toggleClass('close');
    $('.section-page-content .mobile-sidebar .filter-tab .sidebar-title').addClass('close');
  });
 
  // $('.section-page-content .mobile-sidebar .sidebar-title').on('click', function(){
  //   $(this).toggleClass('close');
  // });
// add food 
// $(".addmenu-btn").click(function(){
//   $(".addmenu-body").addClass("open");
// });
// $(".addmenu-body .food-item-delete").click(function(){
//   $(".addmenu-body").removeClass("open");
// });
// $(".addmenu-btn").click(function(){
 
//   $('#homestoreBtn').hide();
//   $(".addmenu-body").slideToggle();


// });
$(".addmenu-body .food-item-delete").click(function(){
  $(".addmenu-body").slideToggle();
});
// edit food
// $(".icon-edit").click(function(){
//   $(".tr-edit").slideToggle();
// });
// $(".catmenulist-tr .food-item-delete").click(function(){
//   $(".catmenulist-tr.tr-edit").slideToggle();
// });

});

// Hide header on scroll down
var last_pos= 0;

$(window).scroll(function () {
    var current_pos= $(this).scrollTop();
    if (current_pos > last_pos) {
        $('header').removeClass('nav-down').addClass('nav-up');
    } else {
        $('header').removeClass('nav-up').addClass('nav-down');
    }
    last_pos = current_pos;
});

$('.mobilemenu-icon').click(function(){
  $('body').toggleClass('active');
})