$(document).ready(function() {
    $(".green").show().effect("bounce", 300);
    $(".red").show().effect("shake", 300);
    setTimeout(function() {
        $(".red,.green").slideUp();
    }, 5000);
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

    $('body').delegate(".giveawayIsGive", 'click', function() {

        var id = $(this).attr("event_id");
        $('#isGive_load_' + id).show();
        var model = $(this).attr("dir");
        var rel = $(this).attr("rel");
        var status = $(this).attr("value");
        jQuery.ajax({
            url: '/admin/Events/isGive/' + rel + '/' + status + '/' + model,
            success: function(data) {
                $('#isGive_load_' + id).hide();
                if (data == 1) {
                    $("#isGive_" + id).attr({'src': '/img/admin/winner.png', 'class': 'giveawayIsGive', 'id': 'isGive_' + id, 'rel': rel, 'value': 1, 'model': model, 'event_id': id});
                } else {
                    $("#isGive_" + id).attr({'src': '/img/admin/notwinner.png', 'class': 'giveawayIsGive', 'id': 'isGive_' + id, 'rel': rel, 'value': 0, 'model': model, 'event_id': id});
                }
            }
        });

    });


    $(window).resize(function() {
        $('#login_wrapper').css({
            position: 'absolute',
            left: ($(window).width() - $('#login_wrapper').outerWidth()) / 2,
            top: ($(window).height() - $('#login_wrapper').outerHeight()) / 2
        });
    });
    $(window).resize();
    $(window).resize(function() {
        $('#forgot_wrapper').css({
            position: 'absolute',
            left: ($(window).width() - $('#forgot_wrapper').outerWidth()) / 2,
            top: ($(window).height() - $('#forgot_wrapper').outerHeight()) / 2
        });
    });
    $(window).resize();
    $('#paswrd').click(function() {
        $('#login_wrapper').fadeOut(100);
        $('#forgot_wrapper').fadeIn(500);
    });
    $('#back').click(function() {
        $('#forgot_wrapper').fadeOut(100);
        $('#login_wrapper').fadeIn(500);
    });
    $('.account_logout li:first-child').css({"background": "none", "paddingRight": "0"});
    $('.nav_links li:last-child').css('background', 'none');
    //$('.nav_links li:first').find('a:eq(0)').addClass('active');
    //$('.nav_links li:first-child').find('.sublinks').show();


    $('.tabulardata tr th:first-child').css('border-top-left-radius', '5px');
    $('.tabulardata tr th:last-child').css('border-top-right-radius', '5px');
    $('.tabulardata tr:last-child td:first-child').css('border-bottom-left-radius', '5px');
    $('.tabulardata tr:last-child td:last-child').css('border-bottom-right-radius', '5px');
    $('.red a').click(function() {
        $('.red').fadeOut(500);
    });
    $('.green a').click(function() {
        $('.green').fadeOut(500);
    });
    $('.pagination li:first-child a').addClass('current');
    $('.pagination li').click(function() {
        $('.pagination li a').removeClass('current');
        $(this).find('a').addClass('current');
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
function changeOrder(order, id, type, old_order) {
    jQuery.ajax({
        url: '/CmsPages/changeOrder/' + order + '/' + id + '/' + type + '/' + old_order,
        success: function(data) {

            if (data == 1) {
                location.reload();
                //$("#order_"+order).attr("selected",old_order);
            } else if (data == 2) {
                alert("Unable to update, Please try again");
            } else {
                alert("A problem occurred");
            }
        }
    });
}