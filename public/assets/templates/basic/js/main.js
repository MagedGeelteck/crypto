(function ($) {
  "user strict";


  $.each($('.select2'), function () {
    $(this)
      .wrap(`<div class="position-relative"></div>`)
      .select2({
        dropdownParent: $(this).parent()
      });
  });

  $.each($('.select2-auto-tokenize'), function () {
    $(this)
      .wrap(`<div class="position-relative"></div>`)
      .select2({
        tags: true,
        tokenSeparators: [','],
        dropdownParent: $(this).parent()
      });
  });

  // preloader
  $(".preloader").delay(800).animate({
    "opacity": "0"
  }, 800, function () {
    $(".preloader").css("display", "none");
  });


  // wow
  if ($('.wow').length) {
    var wow = new WOW({
      boxClass: 'wow',
      // animated element css class (default is wow)
      animateClass: 'animated',
      // animation css class (default is animated)
      offset: 0,
      // distance to the element when triggering the animation (default is 0)
      mobile: false,
      // trigger animations on mobile devices (default is true)
      live: true // act on asynchronously loaded content (default is true)
    });
    wow.init();
  }

  //Create Background Image
  (function background() {
    let img = $('.bg_img');
    img.css('background-image', function () {
      var bg = ('url(' + $(this).data('background') + ')');
      return bg;
    });
  })();



  // navbar-click
  $(".navbar li a").on("click", function () {
    var element = $(this).parent("li");
    if (element.hasClass("show")) {
      element.removeClass("show");
      element.find("li").removeClass("show");
    }
    else {
      element.addClass("show");
      element.siblings("li").removeClass("show");
      element.siblings("li").find("li").removeClass("show");
    }
  });

  // scroll-to-top
  var ScrollTop = $(".scrollToTop");
  $(window).on('scroll', function () {
    if ($(this).scrollTop() < 500) {
      ScrollTop.removeClass("active");
    } else {
      ScrollTop.addClass("active");
    }
  });

  //Odometer
  if ($(".statistics-item").length) {
    $(".statistics-item").each(function () {
      $(this).isInViewport(function (status) {
        if (status === "entered") {
          for (var i = 0; i < document.querySelectorAll(".odometer").length; i++) {
            var el = document.querySelectorAll('.odometer')[i];
            el.innerHTML = el.getAttribute("data-odometer-final");
          }
        }
      });
    });
  }



  // product Tab
  var tabWrapper = $('.product-tab'),
    tabMnu = tabWrapper.find('.tab-menu li'),
    tabContent = tabWrapper.find('.tab-cont > .tab-item');
  tabContent.not(':nth-child(1)').fadeOut(10);
  tabMnu.each(function (i) {
    $(this).attr('data-tab', 'tab' + i);
  });
  tabContent.each(function (i) {
    $(this).attr('data-tab', 'tab' + i);
  });
  tabMnu.on('click', function () {
    var tabData = $(this).data('tab');
    tabWrapper.find(tabContent).fadeOut(10);
    tabWrapper.find(tabContent).filter('[data-tab=' + tabData + ']').fadeIn(10);
  });
  $('.tab-menu > li').on('click', function () {
    var before = $('.tab-menu li.active');
    before.removeClass('active');
    $(this).addClass('active');
  });

  // overview Tab
  var tabWrapper2 = $('.product-single-tab'),
    tabMnu2 = tabWrapper2.find('.tab-menu li'),
    tabContent2 = tabWrapper2.find('.tab-cont > .tab-item');
  tabContent2.not(':nth-child(1)').fadeOut(10);
  tabMnu2.each(function (i) {
    $(this).attr('data-tab', 'tab' + i);
  });
  tabContent2.each(function (i) {
    $(this).attr('data-tab', 'tab' + i);
  });
  tabMnu2.on('click', function () {
    var tabData2 = $(this).data('tab');
    tabWrapper2.find(tabContent2).fadeOut(10);
    tabWrapper2.find(tabContent2).filter('[data-tab=' + tabData2 + ']').fadeIn(10);
  });
  $('.tab-menu > li').on('click', function () {
    var before = $('.tab-menu li.active');
    before.removeClass('active');
    $(this).addClass('active');
  });

  // sidebar
  $(".has-sub > a").on("click", function () {
    var element = $(this).parent("li");
    if (element.hasClass("active")) {
      element.removeClass("active");
      element.children("ul").slideUp(500);
    }
    else {
      element.siblings("li").removeClass('active');
      element.addClass("active");
      element.siblings("li").find("ul").slideUp(500);
      element.children('ul').slideDown(500);
    }
  });

  //sidebar Menu
  $(document).on('click', '.mobile-menu-toggler', function () {
    $('.page-wrapper').toggleClass('show');
  });



  $('.body-header-right').on('click', function (event) {
    event.stopPropagation(); // Prevent the click event from propagating to the body
    $('.body-header-right .dropdown-menu').toggleClass('show');
  });
  $('body').on('click', function () {
    $('.body-header-right .dropdown-menu').removeClass('show');
  })

  //Cross Menu
  $('.mobile-menu-close').on('click', function () {
    $('.page-wrapper').removeClass('show');
  });

  //sidebar Menu
  $(document).on('click', '.sidebar-toggle', function () {
    $('.main-section').toggleClass('open');
  });

  //Cross Menu
  $('.mobile-menu-close').on('click', function () {
    $('.page-wrapper').removeClass('show');
  });

  //Close Menu
  $(document).on('click', '.mfp-close', function () {
    $('.top-notice').toggleClass('remove');
  });

  //Search Menu
  $(document).on('click', '.search-toggle', function () {
    $('.header-search-wrapper').toggleClass('active');
  });

  $('.icon--style').on('click', function (event) {
    event.stopPropagation();
    $('.dropdown-menu.cart-wrapper').toggleClass('show');
  });
  $('body').on('click', function () {
    $('.dropdown-menu.cart-wrapper').removeClass('show');
  })

  // product + - start here
  $(function () {
    var CartPlusMinus = $('.product-plus-minus');
    CartPlusMinus.prepend('<div class="dec qtybutton">-</div>');
    CartPlusMinus.append('<div class="inc qtybutton">+</div>');
    $(".qtybutton").on("click", function () {
      var $button = $(this);
      var oldValue = $button.parent().find("input").val();
      if ($button.text() === "+") {
        var newVal = parseInt(oldValue) + 1;
      } else {
        // Don't allow decrementing below zero
        if (oldValue > 1) {
          var newVal = parseInt(oldValue) - 1;
        } else {
          newVal = 1;
        }
      }
      $button.parent().find("input").val(newVal);
    });
  });

  // account
  $('.account-open-btn, .move-signup-btn').on('click', function () {
    $('.account-section').addClass('active');
  });

  $('.account-close, .switch-login-page-btn').on('click', function () {
    $('.account-section').addClass('duration');
    setTimeout(signupRemoveClass, 200);
    setTimeout(signupRemoveClass2, 200);
  });

  function signupRemoveClass() {
    $('.account-section').removeClass("active");
  }
  function signupRemoveClass2() {
    $('.account-section').removeClass("duration");
  }

  //Form Slider
  $('.account-control-button').on('click', function () {
    $('.account-area').toggleClass('change-form');
  })

  // Panel options
  $('.panel-options').on('click', function () {
    $('.panel-body').slideToggle();
  });

  // Panel options
  $('.panel-options-form').on('click', function () {
    $('.panel-form-area').slideToggle();
  });

  // img-up
  $('.imgUp').click(function () {
    upload();
  });
  function upload() {
    $(".upload").change(function () {
      readURL(this);
    });
  }
  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader(); reader.onload = function (e) {
        var preview = $(input).parents('.profile-thumb-area').find('.image-preview');
        $(preview).css('background-image', 'url(' + e.target.result + ')');
        $(preview).hide();
        $(preview).fadeIn(650);
      }
      reader.readAsDataURL(input.files[0]);
    }
  }



  /*==================== custom dropdown select js ====================*/
  $('.custom--dropdown > .custom--dropdown__selected').on('click', function () {
    $(this).parent().toggleClass('open');
  });
  $('.custom--dropdown > .dropdown-list > .dropdown-list__item').on('click', function () {
    $('.custom--dropdown > .dropdown-list > .dropdown-list__item').removeClass('selected');
    $(this).addClass('selected').parent().parent().removeClass('open').children('.custom--dropdown__selected').html($(this).html());
  });
  $(document).on('keyup', function (evt) {
    if ((evt.keyCode || evt.which) === 27) {
      $('.custom--dropdown').removeClass('open');
    }
  });
  $(document).on('click', function (evt) {
    if ($(evt.target).closest(".custom--dropdown > .custom--dropdown__selected").length === 0) {
      $('.custom--dropdown').removeClass('open');
    }
  });

  /*=============== custom dropdown select js end =================*/


  function proPicURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        var preview = $(input).closest('.image-upload-wrapper').find('.image-upload-preview');
        $(preview).css('background-image', 'url(' + e.target.result + ')');
        $(preview).addClass('has-image');
        $(preview).hide();
        $(preview).fadeIn(650);
      }
      reader.readAsDataURL(input.files[0]);
    }
  }
  $(".image-upload-input").on('change', function () {
    proPicURL(this);
  });


})(jQuery);