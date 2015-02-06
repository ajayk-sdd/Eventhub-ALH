$(document).ready(function() {
    /*var check = $("#type").val();
    if(check == 0){
        $("#event").show();
    } else {
        $("#event").hide();
    }*/
    
    /*if ($('#is_show1').is(':checked')) {
        $('#showCal').show();
    } else {
        $('#showCal').hide();
    }*/
    $("#AddBannerForm").validationEngine();
    $('#AddBanner').watermark('Enter Name');
    
 $("body").delegate(".start_date","click",function(){
    var id = $(this).attr("id");
    $("#"+id).datetimepicker({
        minDate:'0',
        timepicker: false,
        format: 'm/d/Y'
    });
 });    
     
 $("body").delegate(".end_date","click",function(){
    var id = $(this).attr("id");
    $("#"+id).datetimepicker({
        minDate:'0',
        timepicker: false,
        format: 'm/d/Y'
    });
});
$("body").delegate(".is_show","click",function(){
    if($(this).val()==0){
        $(this).parent().next().find('input').val("");
        $(this).parent().next().hide();
             
    }else{
        $(this).parent().next().show();
    }
});

});