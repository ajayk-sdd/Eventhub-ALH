$(document).ready(function() {
    $("#addListForm").validationEngine();
    $("#addEmailForm").validationEngine();
    $("#add_more").click(function() {
        var clone = $("#tickets").last().clone();
        var img = $("<a align ='right' style='padding-left:102px;color:#D83F4A;' href='javascript:void(0);'><img src='/app/webroot/img/admin/delete.png' alt='delete' title='Remove this set'/></a>");
        $(clone).find('td').first().append(img);
        $(img).click(function() {
            $(img).parent('td').parent('tr').remove();
        });
        $($("#tickets")).after(clone);
    });


});
tinyMCE.init({
    theme: "advanced",
    mode: "textareas",
    editor_selector: "mceEditor",
    plugins: "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
    // Theme options
    theme_advanced_buttons1: "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
    theme_advanced_buttons2: "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
    theme_advanced_buttons3: "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
    theme_advanced_buttons4: "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
    theme_advanced_toolbar_location: "top",
    theme_advanced_toolbar_align: "left",
    theme_advanced_statusbar_location: "bottom",
    theme_advanced_resizing: true,
    theme_advanced_resizing_use_cookie: true,
    // Skin options
    skin: "o2k7",
    skin_variant: "silver",
    // Example content CSS (should be your site CSS)
    content_css: "css/example.css",
    // Drop lists for link/image/media/template dialogs
    template_external_list_url: "js/template_list.js",
    external_link_list_url: "js/link_list.js",
    external_image_list_url: "js/image_list.js",
    media_external_list_url: "js/media_list.js",
});
function edit_email(id, value) {
    $('#load_' + id).show();
    jQuery.ajax({
        url: '/admin/MyLists/editEmail/' + id + '/' + value,
        success: function(data) {
            $('#load_' + id).hide();
            if (data == 1) {

            } else {
                alert("Something went wrong, please try again");
            }
        }
    });
}
