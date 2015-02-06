$(document).ready(function(){
      var arr = [];
      $("body").delegate('.addSer','click',function(){
        
       var serviceName = $(this).attr('alt');
       var price = $(this).attr('price');
       var id = $(this).attr('dir');
       
       if ($.inArray(id, arr) < 0) {
              $( ".services" ).last().after('<div class="services" id="">'+serviceName+'<a href="javascript:void(0);" class="remove" price='+price+' dir='+id+'>Remove</a></div><input type="hidden" name="data[CustomPackage][service_id][]" value="'+id+'"><input type="hidden" name="data[Payment][amount][]" value="'+price+'">');
              var total = $('#total').text(parseInt($('#total').text()) + parseInt(price));
              
        }
       else{
              alert("This service is already added, Please choose another one !");
              return false;      
       }
       arr.push( $( this ).attr("dir"));
       });
     
      $("body").delegate('.remove','click',function(){   
              var price = $(this).attr('price');
              $(".addSer").attr("price");
              $('#total').text(parseInt($('#total').text()) - parseInt(price));
              $(this).parent().remove();
              arr.splice($.inArray($(this).attr("dir"), arr),1);
      });
      $("#cstmPkgesPayment").click(function(){
          var total = $("#total").text();
          if(total>0){
              $("#custom").submit();
          } else {
              alert("Please select atleast one service");
          }
              //$("#custom").submit();
      });
       
});