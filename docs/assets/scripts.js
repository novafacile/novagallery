
$(document).ready(function() {
  $( "#show-license" ).click(function() {
    $(".license-toggle").toggle();
  });

  $( "#show-privacy" ).click(function() {
    $(".privacy-toggle").toggle();
  });

  $(window).scroll(function(){
    if ($(this).scrollTop() > 100) {
      $('.navbar-bg').addClass('scrolled');
    } else {
      $('.navbar-bg').removeClass('scrolled');
    }
  });

  // ScrollTo https://www.taniarascia.com/smooth-scroll-to-id-with-jquery/
  $('a[href*="#"]').on('click', function (e) {
    e.preventDefault()
    $('html, body').animate({
        scrollTop: $($(this).attr('href')).offset().top,
      },
      500,
      'linear'
    )
  });

});
