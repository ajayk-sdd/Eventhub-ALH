
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
            <h1>Add Banner</h1>
            <ul class="form">
                <li><img id="imgprvw"/></li>

            </ul>


            <div class="note_for_banner">



            </div>
            <?php echo $this->Form->create("CmsPages", array("action" => "addBanner", "id" => "AddBannerForm", 'enctype' => 'multipart/form-data')); ?>
            <?php
            echo $this->Form->input("Banner.id", array("type" => "hidden"));
            //echo $this->Form->input("Banner.type", array("type" => "hidden", "value" => 1));
            ?>
            <ul class="form">
                <li>
                    <?php echo $this->Form->input("BannerImage.image_name", array("type" => "file", "label" => "Upload Image:*", "div" => false, "class" => "validate[required,checkFileType[jpg|jpeg|gif|JPG|png|PNG],checkFileSize[3000]] form_input", "id" => "filUpload", "onchange" => "showimagepreview(this)")); ?>

                </li>
                <li><a href="javascript:void(0)" id="getD" style="display:none;">Check Image Size</a></li>

                <ul>
                    <li><img id="imgprvwb"/></li>

                </ul>
                <div style="clear:both"></div><br>
                <li>
                    <?php echo $this->Form->input("BannerImage.background_image", array("type" => "file", "label" => "Upload Image:*", "div" => false, "class" => "validate[required,checkFileType[jpg|jpeg|gif|JPG|png|PNG],checkFileSize[3000]] form_input", "id" => "filUpload2", "onchange" => "showBackGroundImagePreview(this)")); ?>

                </li>
                <li><a href="javascript:void(0)" id="getP" style="display:none;">Check Image Size</a></li>

                <li>
                    <?php

   			  $options = array("970*90" => "970*90", "728*90" => "728*90", "790*90" => "790*90", "768*60" => "768*60", "234*60" => "234*60", "180*150" => "180*150", "120*600" => "120*600", "336*280" => "336*280", "300*250" => "300*250", "1200*250" => "1200*250");
                
                   
                    echo $this->Form->input("Banner.size", array("type" => "select", "div" => false, "label" => "Size:*", "class" => "validate[required] form_input", "id" => "size", "options" => $options, "empty" => "Select Size"));
                    ?>
                </li>
                <li class="bnr_det">
                    <?php echo $this->Form->input("Banner.from_brand", array("type" => "select", "div" => false, "label" => "From (User/Brand):*", "class" => "validate[required] form_input", "id" => "type", "options" => $users)); ?>
                </li>

                <li class="bnr_det">
                    <?php echo $this->Form->input("Banner.type", array("type" => "select", "div" => false, "label" => "Type:*", "class" => "validate[required] form_input type", "options" => array("ALH Site", "Wordpress Plugin"), "empty" => "Select Type")); ?>
                </li>

                <li class="bnr_det" style="display:none;">
                    <?php echo $this->Form->input("Banner.event_id", array("type" => "select", "div" => false, "label" => "Event:*", "class" => "validate[required] form_input", "id" => "event_id", "options" => $events, "empty" => "Select Event")); ?>
                </li>

                <table>

                    <tr class="tickets" id="ticketsId">
                        <td>  
                            <fieldset style="padding:20px;">
                                <legend>Details</legend>



                                <li class="bnr_det">
                                    <?php echo $this->Form->input("BannerImage.to_brand.", array("type" => "select", "div" => false, "label" => "To (User/Brand):*", "class" => "validate[required] form_input", "id" => "type", "options" => $users)); ?>
                                </li>

                                <li class="bnr_det">
                                    <?php
                                    $options = array("Home Page" => "Home Page", "Home Page(Bottom)" => "Home Page(Bottom)");
                                    echo $this->Form->input("BannerImage.location.", array("type" => "select", "div" => false, "label" => "Location:*", "class" => "validate[required] form_input", "id" => "type", "options" => $options));
                                    ?>
                                </li>

                                <li class="bnr_det">
                                    <?php echo $this->Form->input("BannerImage.url.", array("type" => "text", "div" => false, "label" => "URL For Link:", "class" => "form_input", "id" => "url")); ?>
                                </li>



                                <li class="bnr_det">
                                    <label>Show Banner:</label>
                                    <?php echo $this->Form->input('BannerImage.is_show.', array('id' => 'is_show', "class" => "is_show", 'generated' => false, 'type' => 'radio', 'legend' => false, 'div' => false, 'label' => false, 'options' => array('0' => '&nbsp;&nbsp;Permanently' . "&nbsp;&nbsp;", '1' => '&nbsp;&nbsp;Selected Date Range'))); ?>
                                </li>

                                <li class = "showCal" style="display:none;" class="bnr_det">
                                    <label></label>
                                    <?php echo $this->Form->input("BannerImage.start_date.", array("type" => "text", "div" => false, "label" => FALSE, "class" => "validate[required] form_input", "id" => "start_date", "placeholder" => "Start Date", "class" => "start_date form_input", "readonly" => "readonly", "onchange" => "checkValidDate(this.value);")); ?>
                                    <?php echo $this->Form->input("BannerImage.end_date.", array("type" => "text", "div" => false, "label" => FALSE, "class" => "validate[required] form_input", "id" => "end_date", "placeholder" => "End Date", "class" => "end_date form_input", "readonly" => "readonly", "onchange" => "checkValidDateEnd(this.value);")); ?>
                                </li>


                            </fieldset>
                        </td>
                    </tr>
                </table>

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
        //$('.formError').hide();
	var size = input.files[0].size;
	var imgkbytes = Math.round(parseInt(size)/1024);
	   
	if (imgkbytes <= 2000) {
        if (input.files && input.files[0]) {
            var filerdr = new FileReader();
            filerdr.onload = function(e) {
                $('#imgprvw').attr('src', e.target.result);
		$('#imgprvw').css({'max-height':'200px'});
            }
            filerdr.readAsDataURL(input.files[0]);
        }
        $("#getD").show();
	}
    }

    function showBackGroundImagePreview(input) {
        //$('.formError').hide();
	var size = input.files[0].size;
	var imgkbytes = Math.round(parseInt(size)/1024);
	   
	if (imgkbytes <= 2000) {
        if (input.files && input.files[0]) {
            var filerdr = new FileReader();
            filerdr.onload = function(e) {
                $('#imgprvwb').attr('src', e.target.result);
		$('#imgprvwb').css({'max-height':'200px'});
            }
            filerdr.readAsDataURL(input.files[0]);
        }
        $("#getP").show();
	}
    }
    
    function checkValidDate(date) {
	
	var sd = Math.round(+new Date(date)/1000);
	var end = document.getElementById("end_date").value;
	if (end!='') {
	    var ed = Math.round(+new Date(end)/1000);
	  
	    if (ed<sd) {
		alert("Start Date should not be greater then End Date.");
		document.getElementById("start_date").value='';
		return false;
	    }
	    else
	    {
		return true;
	    }
	}
	
    }
    
    function checkValidDateEnd(date) {
	
	var ed = Math.round(+new Date(date)/1000);
	var start = document.getElementById("start_date").value;
	if (start!='') {
	    var sd = Math.round(+new Date(start)/1000);
	   
	    if (ed<sd) {
		alert("End Date should not be lesser then Start Date.");
		document.getElementById("end_date").value='';
	    return false;
	    }
	    else
	    {
		return true;
	    }
	}
	
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


        $("#add_more").click(function() {
            var counter = $(".tickets").length;
            var clone = $("#ticketsId").last().clone();
            var img = $("<a align ='right' style='color:#D83F4A;' href='javascript:void(0);'><img src='/app/webroot/img/admin/delete.png' alt='delete' title='Remove this set'/></a>");
            $(clone).find('td').first().append(img);
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
