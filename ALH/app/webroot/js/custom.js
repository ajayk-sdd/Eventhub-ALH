$(document).ready(function() {
    $('body').delegate(".statusImg", 'click', function() {

        var id = $(this).attr("id");
        $('#load_' + id).show();
        var model = $(this).attr("dir");
        var rel = $(this).attr("rel");
        var status = $(this).attr("value");

        jQuery.ajax({
            url: '/admin/Commons/changestatus/' + rel + '/' + status + '/' + model,
            success: function(data) {
                $('#load_' + id).hide();
                if (data == 1) {
                    $("#" + id).attr({'src': '/img/admin/active.png', 'class': 'statusImg', 'id': id, 'rel': rel, 'value': 1, 'model': model});
                } else {
                    $("#" + id).attr({'src': '/img/admin/inactive.png', 'class': 'statusImg', 'id': id, 'rel': rel, 'value': 0, 'model': model});
                }
            }
        });

    });


    $(function() {

        // add multiple select / deselect functionality
        $("#selectall").click(function() {
            $('.case').prop('checked', this.checked);
        });

        // if all checkbox are selected, check the selectall checkbox
        // and viceversa
        $(".case").click(function() {

            if ($(".case").length == $(".case:checked").length) {
                $("#selectall").prop("checked", "checked");
            } else {
                $("#selectall").removeAttr("checked");
            }

        });
    });

    // for alert before leaving
//    $("input").on("click", function() {
//        $(window).bind('beforeunload', function() {
//            return 'Are you sure you want to leave? the changes you have made will not be saved';
//        });
//    });
//    // for alert before leaving
//    $("textarea").on("click", function() {
//        $(window).bind('beforeunload', function() {
//            return 'Are you sure you want to leave? the changes you have made will not be saved';
//        });
//    });
//    // for unbind while saving
//    $("input[type=submit]").on("click", function() {
//        $(window).unbind('beforeunload');
//    });
//    $("input[type=file]").on("click", function() {
//        $(window).unbind('beforeunload');
//    });
});

function atleastOneChecked(message) {
    if (document.getElementsByName('IDs[]')) {
        var objCheckBoxes = document.getElementsByName('IDs[]');
        var count = 0;
        for (i = 0; i < objCheckBoxes.length; i++) {
            var e = objCheckBoxes[i];
            if (e.checked) {
                count++;
            }
        }
        if (count <= 0) {
            alert("Please select at least one checkbox.");
            return false;
        } else {
            return confirm(message);
        }
    }
    return true;
}

function getmapdata(lati, longi) {

    myCenter = new google.maps.LatLng(lati, longi);
    var mapProp = {
        center: myCenter,
        zoom: 10
                //mapTypeId:google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
    marker = new google.maps.Marker({
        position: myCenter
    });
    marker.setMap(map);
}

function sendmapdata(zip) {
    alert(zip);
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({'address': "'"+zip+"'"},
    function(results_array, status) {
        //alert(status);
        //console.log(results_array[0].geometry.location.lat());
        // Check status and do whatever you want with what you get back
        // in the results_array variable if it is OK.
         var lat = results_array[0].geometry.location.lat();
         var lng = results_array[0].geometry.location.lng();
         getmapdata(lat, lng);
    });
}

function add_to_mycalender(event_id,img) {
    $('#load_' + event_id).show();
    jQuery.ajax({
        url: '/Events/addToMyCalendar/' + event_id,
        success: function(data) {
            $('#load_' + event_id).hide();
            if (img==1) {
                if (data == 1) {
                $("#" + event_id).html('<img class="cal-minus" title="Add to My Calendar" alt="Add to My Calendar" src="/img/plus.png">');
            } else if (data == 2) {
                $("#" + event_id).html('<img class="cal-minus" title="Remove From My Calendar" alt="Remove From My Calendar" src="/img/minus.png">');
            } else {
                alert("A problem occured");
            }
            }
            else
            {
                if (data == 1) {
               
                $("#" + event_id).html("+Add to My Calendar");
            } else if (data == 2) {
                $("#" + event_id).html("-Remove from My Calendar");
            } else {
                alert("A problem occured");
            }
            }
            
        }
    });
}
function add_to_mycalender_remove(event_id) {
    $('#load_' + event_id).show();
    jQuery.ajax({
        url: '/Events/addToMyCalendar/' + event_id,
        success: function(data) {
            $('#load_' + event_id).hide();
            if (data == 1) {
                $("#event_box_" + event_id).remove();
                $("#" + event_id).html("+Add to My Calendar");
            } else if (data == 2) {
                $("#" + event_id).html("-Remove from My Calendar");
            } else {
                alert("A problem occured");
            }
        }
    });
}

function add_to_mywpplugin(event_id) {

    $('#load_' + event_id).show();
    jQuery.ajax({
        url: '/Events/addToMyWpplugin/' + event_id,
        success: function(data) {
            $('#load_' + event_id).hide();
            if (data == 1) {
                $("#wp" + event_id).html("Add To Wp-Plugin");
            } else if (data == 2) {
                $("#wp" + event_id).html("Remove From Wp-Plugin");
            } else {
                alert("A problem occured");
            }
        }
    });
}

function add_to_mycalender_ef(event_id) {

    $('#load_' + event_id).show();
    jQuery.ajax({
        url: '/Events/addToMyCalendarEf/' + event_id,
        success: function(data) {
            $('#load_' + event_id).hide();
            if (data == 1) {
                $("#" + event_id).html("+Add to My Calendar");
            } else if (data == 2) {
                $("#" + event_id).html("-Remove from My Calendar");
            } else {
                alert("A problem occured");
            }
        }
    });
}

function add_to_mywpplugin_ef(event_id) {

    $('#load_' + event_id).show();
    jQuery.ajax({
        url: '/Events/addToMyWppluginEf/' + event_id,
        success: function(data) {
            $('#load_' + event_id).hide();
            if (data == 1) {
                $("#wp" + event_id).html("+Add to Wp-Plugin");
            } else if (data == 2) {
                $("#wp" + event_id).html("-Remove from Wp-Plugin");
            } else {
                alert("A problem occured");
            }
        }
    });
}

function add_to_alh_ef(event_id) {

    $('#load_' + event_id).show();
    jQuery.ajax({
        url: '/Events/eventfultoalh/' + event_id,
        success: function(data) {
            $('#load_' + event_id).hide();
             $("#alhmsg").show();
            if (data == 1) {
                $("#alhmsg").html("Successfuly Saved.");
            }else if (data == 2) {
                $("#alhmsg").html("Already Added to ALH");
            } else {
                $("#alhmsg").html("Faild to Save.");
            } 
        }
    });
}

function suscribe_to_brand(brand_id, brand_name) {
    $('#load_' + brand_id).show();
    jQuery.ajax({
        url: '/Brands/suscribe/' + brand_id,
        success: function(data) {
            $('#load_' + brand_id).hide();
            if (data == 1) {
                $("#" + brand_id).html("Subscribe to " + brand_name + " Newsletter");
            } else if (data == 2) {
                $("#" + brand_id).html("Unsubscribe to " + brand_name + " Newsletter");
            } else {
                alert("A problem occured");
            }
        }
    });
}

function newsletter() {
    $('#load_newsletter').show();
    var email = $('#newsletter_email').val();
    email = email.trim();
    if (email == "") {
        $('#load_newsletter').hide();
        $("#newsletter_message").html("<b style='color:#F8F900;'>Please enter an email address.</b>");
    } else if (!validateEmail(email)) {
        $('#load_newsletter').hide();
        $("#newsletter_message").html("<b style='color:#F8F900;'>Please enter correct email address.</b>");
    } else {
        jQuery.ajax({
            url: '/newsletters/subscribe/' + email,
            success: function(data) {
                $('#load_newsletter').hide();
                if (data == 1) {
                    $("#newsletter_message").html("<b style='color:#58B5E5;'>Successfully subscribed to the ALH Newsletter.</b>");
                } else if (data == 2) {
                    $("#newsletter_message").html("<b style='color:#F8F900;'>Unable to subscribe for newsletter.</b>");
                }
                else if (data == 3) {
                    $("#newsletter_message").html("<b style='color:#F8F900;'>" + email + " is already subscribed for the newsletter.</b>");
                } else if (data == 4) {
                    $("#newsletter_message").html("<b style='color:#F8F900;'>Please enter an email address.</b>");
                } else {
                    $("#newsletter_message").html("<b style='color:#F8F900;'>A problem occure, please try again.</b>");
                }
            }
        });
    }
}

function newsletterTopnav() {
    $('#load_newsletter_top').show();
    var email = $('#newsletter_email_top').val();
    email = email.trim();
    if (email == "") {
        $('#load_newsletter_top').hide();
        alert("Please Enter an Email Address.");
     } else if (!validateEmail(email)) {
        $('#load_newsletter_top').hide();
        alert("Please Enter Correct Email Address.");
        
    } else {
        jQuery.ajax({
            url: '/newsletters/subscribe/' + email,
            success: function(data) {
                $('#load_newsletter_top').hide();
                if (data == 1) {
                      alert("Thank you for your subscription.");
               
                } else if (data == 2) {
                      alert("Unable to Subscribe for Newsletter.");
                 
                }
                else if (data == 3) {
                      alert(email + "is already Subscribed for the Newsletter.");
                } else if (data == 4) {
                      alert("Please Enter an Email Address.");
                 } else {
                      alert("A problem occure, Please try again!");
                }
            }
        });
    }
}

function validateEmail(email) { //alert(email);
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    if (!emailReg.test(email)) {
        return false;
    } else {
        return true;
    }
}

function checklocation() {

    var specify = $('#specify').val();
    var user_id = $('#user_id').val();
    if (specify == "") {
        $('#specifyerror').text("Please specify where your event will happen");
        $('#my_address').val("");
    } else {

        jQuery.ajax({
            url: '/events/getspecifyaddress/' + specify + '/' + user_id,
            success: function(data) {
                var obj = $.parseJSON(data);
                if (obj == '') {
                    $('#specifyerror').text("Please specify where your event will happen");
                    $('#specify').focus();
                    $('#my_address').val("");
                } else {
                    $('#specifyerror').text("");
                    $("#cant_find_address").show();
                    $('#EventEventAddress1').val(obj.address1);
                    $('#EventEventAddress2').val(obj.address2);
                    $('#cant_find_city').val(obj.city);
                    $('#EventCantFindState').val(obj.state);
                    $('#cant_find_zip_code').val(obj.zip);
                    $('#my_address').val(specify);

                }
            }
        });

    }
}
//$(window).bind('beforeunload', function() {
//    return 'Are you sure you want to leave?';
//});

function feature(event_id) {
    $('#load_' + event_id).show();

    jQuery.ajax({
        url: '/Commons/feature/' + event_id,
        success: function(data) {
            $('#load_' + event_id).hide();
            if (data == 1) {
                $("#feature_icon_" + event_id).attr("src", "/img/not_feature.png");
                $("#event_box_" + event_id).addClass("feature_event");
            } else if (data == 2) {
                $("#feature_icon_" + event_id).attr("src", "/img/feature.png");
                $("#event_box_" + event_id).removeClass("feature_event");
            }
            else {
                alert("An error occurred, please try again");
            }
        }
    });
}
function remove_event(event_id) {
    $('#load_' + event_id).show();
    check = confirm("Are you sure you want to remove this Event");
    if (check == true) {
        jQuery.ajax({
            url: '/Commons/removeEvent/' + event_id,
            success: function(data) {
                $('#load_' + event_id).hide();
                if (data == 1) {
                    $("#event_box_" + event_id).remove();
                }
                else {
                    alert("An error occurred, please try again");
                }
            }
        });
    } else {
        $('#load_' + event_id).hide();
    }
}
function getPointPrice(buyPoint_id) {
    $('#load_buyPoint').show();
    if (buyPoint_id != "") {
        jQuery.ajax({
            url: '/Points/getPointPrice/' + buyPoint_id,
            success: function(data) {
                $('#load_buyPoint').hide();
                if (data > 0) {
                    $("#buy_now_price").html("$" + data);
                }
                else {
                    $("#error_message").html("An error occurred, please try again");
                }
            }
        });
    } else {
        $("#error_message").html("Please select points");
    }
}