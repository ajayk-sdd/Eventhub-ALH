$(function(){
  // vars for testimonials carousel
  var $txtcarousel = $('#recomEvent-list');
  var txtcount = $txtcarousel.children().length;
  var wrapwidth = (txtcount * 415) + 415; // 400px width for each testimonial item
  $txtcarousel.css('width',wrapwidth);
  var animtime = 750; // milliseconds for clients carousel
 
  // prev & next btns for testimonials
  $('#prv-testimonial').on('click', function(){
    var $last = $('#recomEvent-list li:last');
    $last.remove().css({ 'margin-left': '-515px' });
    $('#recomEvent-list li:first').before($last);
    $last.animate({ 'margin-left': '0px' }, animtime); 
  });
  
  $('#nxt-testimonial').on('click', function(){
    var $first = $('#recomEvent-list li:first');
    $first.animate({ 'margin-left': '-515px' }, animtime, function() {
      $first.remove().css({ 'margin-left': '0px' });
      $('#recomEvent-list li:last').after($first);
    });  
  });


  // vars for clients list carousel
  // http://stackoverflow.com/questions/6759494/jquery-function-definition-in-a-carousel-script
  var $clientcarousel = $('#recomEvent-list');
  var clients = $clientcarousel.children().length;
  var clientwidth = (clients * 600); // 140px width for each client item
  //var clientwidth = "1200";
  $clientcarousel.css('width',clientwidth);
  
  var rotating = true;
  var clientspeed = 1800;
  var seeclients = setInterval(rotateClients, clientspeed);
  
  $(document).on({
    mouseenter: function(){
      rotating = false; // turn off rotation when hovering
    },
    mouseleave: function(){
      rotating = true;
    }
  }, '#clients');
  
  function rotateClients() {
    if(rotating != false) {
      var $first = $('#recomEvent-list li:first');
      $first.animate({ 'margin-left': '-600px' }, 2000, function() {
        $first.remove().css({ 'margin-left': '0px' });
        $('#recomEvent-list li:last').after($first);
      });
    }
  }
});