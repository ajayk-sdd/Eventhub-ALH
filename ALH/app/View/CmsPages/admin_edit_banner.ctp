<?php
//pr($bannerData);
if (!empty($bannerData["Banner"]["size"])) {
    $banner_size = explode("*", trim($bannerData["Banner"]["size"]));
    $width = $banner_size[0] . "px";
    $height = $banner_size[1] . "px";
} else {
    $width = 0;
    $height = 0;
}
?>
<style>
    .bnr_det{margin-bottom:10px;}
</style><!--Content Wrapper Starts-->
<section id="cont_wrapper">
    <!--Content Starts-->
    <section class="content">
        <!--Main Heading Starts-->
        <h1 class="main_heading"><?php echo $this->Html->link('List Banner', '/admin/CmsPages/listBanner'); ?></h1>
        <!--Main Heading Ends-->
        <!--Conetnt Info Starts Here-->
        <section class="content_info">
            <h1>Edit Banner</h1>
            <ul>
                <li><img id="imgprvw"/></li>

            </ul>

            <div class="note_for_banner">



            </div>
            <?php echo $this->Form->create("CmsPages", array("action" => "editBanner/" . base64_encode($bannerData['Banner']['id']), "id" => "EditBannerForm", 'enctype' => 'multipart/form-data')); ?>

            <ul class="form">
                <li id = "banner_image">
                    <?php
                    if ($width > 0) {
                        echo $this->Html->image($bannerData["Banner"]["image_name"], array("style" => "width:$width;height:$height"));
                    } else {
                        echo $this->Html->image($bannerData["Banner"]["image_name"]);
                    }
                    ?>
                    <?php //$size = getimagesize($bannerData["Banner"]["image_name"]); ?>
<!--                    <img class="" alt="" src="http:////<?php echo $_SERVER['HTTP_HOST']; ?>/users/thumbnail?file=<?php echo $bannerData["Banner"]["image_name"]; ?>&amp;width=100&amp;height=100&amp;maxw='<?php echo $size[0]; ?>'&amp;maxh='<?php echo $size[1]; ?>'">-->
                </li>
                <li>
                    <?php echo $this->Form->input("BannerImage.image_name", array("type" => "file", "label" => "Upload Image", "div" => false, "class" => "validate[required] form_input", "id" => "filUpload", "onchange" => "showimagepreview(this)")); ?>
                    <span style="margin-left:10px;"><img class="tag_img_cls" alt="" src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/users/thumbnail?file=http://<?php echo $_SERVER['HTTP_HOST'] . "/img/Banner/" . $oldImg; ?>&amp;width=80&amp;height=80&amp;maxw=80&amp;maxh=80"></span>
                </li>
                <li><a href="javascript:void(0)" id="getD" style="display:none;">Check Image Size</a></li>
                <li id = "background_image">
                    <?php
                    if ($width > 0) {
                        echo $this->Html->image($bannerData["Banner"]["background_image"], array("style" => "width:$width;height:$height"));
                    } else {
                        echo $this->Html->image($bannerData["Banner"]["background_image"]);
                    }
                    ?>

<!--                    <img class="" alt="" src="http:////<?php echo $_SERVER['HTTP_HOST']; ?>/users/thumbnail?file=<?php echo $bannerData["Banner"]["background_image"]; ?>&amp;width=100&amp;height=100&amp;maxw='<?php echo $size[0]; ?>'&amp;maxh='<?php echo $size[1]; ?>'">-->
                </li>
                <ul>
                    <li><img id="imgprvwb"/></li>

                </ul>
                <div style="clear:both"></div><br>
                <li>
                    <?php echo $this->Form->input("BannerImage.background_image", array("type" => "file", "label" => "Upload Image", "div" => false, "class" => "validate[required] form_input", "id" => "filUpload2", "onchange" => "showBackGroundImagePreview(this)")); ?>
                    <span style="margin-left:10px;"><img class="tag_img_cls" alt="" src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/users/thumbnail?file=http://<?php echo $_SERVER['HTTP_HOST'] . "/img/Banner/" . $oldBImg; ?>&amp;width=80&amp;height=80&amp;maxw=80&amp;maxh=80"></span>

                </li>
                <li><a href="javascript:void(0)" id="getP" style="display:none;">Check Image Size</a></li>
                <li>
                    <?php
                    $options = array("970*90" => "970*90", "728*90" => "728*90", "790*90" => "790*90", "768*60" => "768*60", "234*60" => "234*60", "180*150" => "180*150", "120*600" => "120*600", "336*280" => "336*280", "300*250" => "300*250", "1200*250" => "1200*250");
                    echo $this->Form->input("Banner.size", array("type" => "select", "div" => false, "label" => "Size :*", "class" => "validate[required] form_input", "id" => "size", "options" => $options, "empty" => "Select Size", "value" => $bannerData['Banner']['size']));
                    ?>
                </li>
                <li class="bnr_det">
                    <?php echo $this->Form->input("Banner.from_brand", array("type" => "select", "div" => false, "label" => "From (User/Brand) :*", "class" => "validate[required] form_input", "id" => "type", "options" => $users, "value" => $bannerData['Banner']['from_brand'])); ?>
                </li>
                <li class="bnr_det">
                    <?php echo $this->Form->input("Banner.type", array("type" => "select", "div" => false, "label" => "Type :*", "class" => "validate[required] form_input type", "options" => array("ALH Site", "Wordpress Plugin"), "value" => $bannerData['Banner']['type'])); ?>
                </li>
                <li class="bnr_det" style="display:none;">
                    <?php echo $this->Form->input("Banner.event_id", array("type" => "select", "div" => false, "label" => "Event :*", "class" => "validate[required] form_input", "id" => "event_id", "options" => $events, "empty" => "Select Event", "value" => $bannerData['Banner']['event_id'])); ?>
                </li>
                <?php
                $cnt = 0;
                foreach ($bannerData['BannerImage'] as $data):
                    ?>
                    <table>

                        <tr class="tickets" id="ticketsId">
                            <td>  
                                <fieldset style="padding:20px;">
                                    <legend>Details</legend>



                                    <li class="bnr_det">
                                        <?php echo $this->Form->input("BannerImage.to_brand.", array("type" => "select", "div" => false, "label" => "To (User/Brand) :*", "class" => "validate[required] form_input", "id" => "type", "options" => $users, "value" => $data['to_brand'])); ?>
                                    </li>

                                    <li class="bnr_det">
                                        <?php
                                        $options = array("Home Page" => "Home Page", "Home Page(Bottom)" => "Home Page(Bottom)");
                                        echo $this->Form->input("BannerImage.location.", array("type" => "select", "div" => false, "label" => "Location :*", "class" => "validate[required] form_input", "id" => "type", "options" => $options, "value" => $data['location']));
                                        ?>
                                    </li>

                                    <li class="bnr_det">
                                        <?php echo $this->Form->input("BannerImage.url.", array("type" => "text", "div" => false, "label" => "URL For Link :", "class" => "form_input", "id" => "url", "value" => $data['url'])); ?>
                                    </li>

                                    <?php /* ?><li id = "event">
                                      <?php echo $this->Form->input("Banner.event_id", array("type" => "select", "div" => false, "label" => "Event :*", "class" => "validate[required] form_input", "id" => "event_id", "options" => $events)); ?>
                                      </li><?php */ ?>

                                    <li class="bnr_det">
                                        <label>Show Banner</label>
                                        <?php if ($cnt == 0) { ?>
                                            <input type="radio" name="data[BannerImage][is_show]" <?php if ($data['is_show'] == 0) { ?>checked="checked"<?php } ?> value="0" class="is_show"> Permanently<br>
                                            <input type="radio" name="data[BannerImage][is_show]" <?php if ($data['is_show'] == 1) { ?>checked="checked;"<?php } ?>value="1" class="is_show"> Selected Date Range<br>


                                        <li class = "showCal" style="display:none;" class="bnr_det">
                                            <label></label>
                                            <?php echo $this->Form->input("BannerImage.start_date.", array("type" => "text", "div" => false, "label" => FALSE, "class" => "validate[required] form_input", "id" => "start_date", "placeholder" => "Start Date", "class" => "start_date form_input", "value" => $data['start_date'])); ?>
                                            <?php echo $this->Form->input("BannerImage.end_date.", array("type" => "text", "div" => false, "label" => FALSE, "class" => "validate[required] form_input", "id" => "end_date", "placeholder" => "End Date", "class" => "end_date form_input", "value" => $data['end_date'])); ?>
                                        </li>
                                    <?php } else { ?>
                                        <input type="radio" name="data[BannerImage][is_show_<?php echo $cnt; ?>]" <?php if ($data['is_show_' . $cnt] == 0) { ?>checked="checked"<?php } ?> value="0" class="is_show"> Permanently<br>
                                        <input type="radio" name="data[BannerImage][is_show_<?php echo $cnt; ?>]" <?php if ($data['is_show_' . $cnt] == 1) { ?>checked="checked"<?php } ?>value="1" class="is_show"> Selected Date Range<br>

                                        <li class = "showCal" style="display:none;" class="bnr_det">
                                            <label></label>
                                            <?php echo $this->Form->input("BannerImage.start_date.", array("type" => "text", "div" => false, "label" => FALSE, "class" => "validate[required] form_input", "id" => "start_date_" . $cnt, "placeholder" => "Start Date", "class" => "start_date form_input", "value" => $data['start_date'])); ?>
                                            <?php echo $this->Form->input("BannerImage.end_date.", array("type" => "text", "div" => false, "label" => FALSE, "class" => "validate[required] form_input", "id" => "end_date_" . $cnt, "placeholder" => "End Date", "class" => "end_date form_input", "value" => $data['end_date'])); ?>
                                        </li>
                                    <?php } ?>

                                </fieldset>
                                <?php if ($cnt > 0) { ?>
                                    <a align ='right' style='color:#D83F4A;' href='javascript:void(0);' class="delImg"><img src='/app/webroot/img/admin/delete.png' alt='delete' title='Remove this set'/></a>
                                <?php } ?>
                            </td>
                        </tr>
                    </table>



                    <?php
                    $cnt = $cnt + 1;
                endforeach;
                ?>        
                <section class="login_btn" style="width: 29%;">
                    <?php echo $this->Html->link("Add New Placement + ", "javascript:void(0);", array('escape' => false, 'id' => 'add_more')); ?>
                    <span class="blu_btn_lt">
                        <?php echo $this->Form->input("Reset", array("type" => "reset", "label" => false, "div" => false, "class" => "blu_btn_rt")); ?>
                    </span>
                    <span class="blu_btn_lt">

                        <?php echo $this->Form->input("Submit", array("type" => "submit", "label" => false, "div" => false, "class" => "blu_btn_rt")); ?>
                    </span>
                </section>

            </ul>

            <?php echo $this->Form->end(); ?>
            <section class="clr_bth"></section>
        </section>
        <!--Conetnt Info Ends Here-->
    </section>
    <!--Content Ends--> 
</section>
<div id="dialog" title="Image Description">

</div>
<?php echo $this->Html->script('/js/admin/CMS/admin_add_banner'); ?>
<script type="text/javascript">
    function showimagepreview(input) {
        $('.formError').hide();
        $('#banner_image').hide();
        if (input.files && input.files[0]) {
            var filerdr = new FileReader();
            filerdr.onload = function(e) {
                $('#imgprvw').attr('src', e.target.result);
            }
            filerdr.readAsDataURL(input.files[0]);
        }

        $("#getD").show();
    }

    function showBackGroundImagePreview(input) {
        $('.formError').hide();
        $('#background_image').hide();
        if (input.files && input.files[0]) {
            var filerdr = new FileReader();
            filerdr.onload = function(e) {
                $('#imgprvwb').attr('src', e.target.result);
            }
            filerdr.readAsDataURL(input.files[0]);
        }
        $("#getP").show();
    }

    $(document).ready(function() {
        $("#getD").click(function() {
            var height = $("#imgprvw").height();
            var width = $("#imgprvw").width();
            $("#dialog").html("<p style='font-size:13px;'>Below is the current image description : <b/><br/><br/><b> Width : </b>" + width + " px <br/> <b>Height : </b>" + height + " px </br ></br >NOTE * : Please don't select the size more than the current size for better resolution.</p>");
            $("#dialog").dialog({
                show: {
                    effect: "slide",
                    duration: 1000
                },
                hide: {
                    effect: "explode",
                    duration: 1000
                },
                buttons: {
                    OK: function() {
                        $(this).dialog("close");
                    }
                }
            });
        });

        $("#getP").click(function() {
            var height = $("#imgprvwb").height();
            var width = $("#imgprvwb").width();
            $("#dialog").html("<p style='font-size:13px;'>Below is the current image description : <b/><br/><br/><b> Width : </b>" + width + " px <br/> <b>Height : </b>" + height + " px </br ></br >NOTE * : Please don't select the size more than the current size for better resolution.</p>");
            $("#dialog").dialog({
                show: {
                    effect: "slide",
                    duration: 1000
                },
                hide: {
                    effect: "explode",
                    duration: 1000
                },
                buttons: {
                    OK: function() {
                        $(this).dialog("close");
                    }
                }
            });
        });

        $(".delImg").click(function() {
            $(this).prev().remove();
            $(this).remove();

        });
        $(".type").change(function() {
            var typeVal = $(this).val();
            if (typeVal == 1) {
                $(this).parent().next().show();
                $("#add_more").hide();
            } else {
                $(this).parent().next().hide();
                $("#add_more").show();
            }
        });


        $("#EditBannerForm input[type='radio']:checked").parent().next().show();
        var type = $(".type").val();
        if (type == 1) {
            $(".type").parent().next().show();
            $("#add_more").hide();
        } else {
            $(".type").parent().next().hide();
        }
        $("#add_more").click(function() {
            var counter = $(".tickets").length;
            var clone = $("#ticketsId").last().clone();
            var img = $("<a align ='right' style='color:#D83F4A;' href='javascript:void(0);'><img src='/app/webroot/img/admin/delete.png' alt='delete' title='Remove this set'/></a>");
            $(clone).find('td').first().append(img);
            // $(".delImg").last().children().remove();
            $(clone).find(".is_show").attr("name", "data[BannerImage][is_show_" + counter + "]");
            $(clone).find('.tickets').attr("id", "ticketsId_" + counter);

            $(clone).find('.start_date').attr("id", "start_date_" + counter);
            $(clone).find('.end_date').attr("id", "end_date_" + counter);

            $(img).click(function() {
                $(img).parent('td').parent('tr').remove();
            });
            $(".tickets").last().after(clone);
        });
    });
</script>