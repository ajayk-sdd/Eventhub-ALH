<link href="/css/evol.colorpicker.css" rel="stylesheet" />
<script src="/js/ckeditor.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js" type="text/javascript"></script>
<script src="/js/evol.colorpicker.js" type="text/javascript"></script>

<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>-->
<script type="text/javascript" src="/js/jquery.fancybox-1.3.4.js"></script>
<link rel="stylesheet" type="text/css" href="/css/jquery.fancybox-1.3.4.css" media="screen" />

<link href="/css/sample.css" rel="stylesheet">
<style>

    .ui-widget-content {
        background-color: #F8F8F8;
        border: 1px solid #DDDDDD;
        color: #333333;
    }


</style>

<script  type="text/javascript">
    $(document).ready(function()
    {
        $(".imageOpen").fancybox({
            scrolling: false,
            width: '80%',
            onStart: function() {
                var html = $("#container").html();
                $("#myPopup").html(html);
            }
        });
        $('.cpBoth').colorpicker();

        $('body').click(function() {
            $(".my_focus").removeClass("my_focus");
            $(".cke_focus").addClass("my_focus");

            // set background color default
            var cke = $(".cke_focus");
            //console.log(cke);
            if (cke.length != 0) {
                var backgroundcolor = $(".cke_focus").css("background-color");
                //alert(backgroundcolor);
                if (backgroundcolor.trim() != "transparent") {
                    $(".BackgroundColor").val(rgb2hex(backgroundcolor));
                    $(".BackgroundColor").next().css("background-color", backgroundcolor);
                }
            }


            //set border color default
            var borderColor = $('.cke_focus').css("borderTopColor");
            if (backgroundcolor.trim() != "transparent") {
                $(".borderColor").val(rgb2hex(borderColor));
                $(".borderColor").next().css("background-color", borderColor);
            }

            // set text color
            var color = $('.cke_focus').css("color");
            if (backgroundcolor.trim() != "transparent") {
                $(".textColor").val(rgb2hex(color));
                $(".textColor").next().css("background-color", color);
            }

        });

    });
    $(document).ready(function()
    {
        // set default background color

        var variable = $(".fulckeditor").last();
        var fullBackgroundColor = variable.css("background-color");
        if (fullBackgroundColor.trim() != "transparent") {
            $(".fullBackgroundColor").val(rgb2hex(fullBackgroundColor));
            $(".fullBackgroundColor").next().css("background-color", fullBackgroundColor);
        }
        //alert(color);
        // now defalt border color
        var fullborderColor = variable.css("borderTopColor");
        if (fullborderColor.trim() != "transparent") {
            $(".fullBorderColor").val(rgb2hex(fullborderColor));
            //$(".fulckeditor").last().next().css("background-color", color);
            $(".fullBorderColor").next().css("background-color", fullborderColor);
        }
        //alert(bordercolor);


        $('.cpBoth').colorpicker();
    });


    function backgroundColor(color) {
        $(".my_focus").css("background-color", color);
        $(".my_focus").removeClass("my_focus");

    }
    function borderColor(color) {
        $(".my_focus").css("border", "1px solid " + color);
        $(".my_focus").removeClass("my_focus");
    }
    function textColor(color) {
        $(".my_focus").css("color", color);
        $(".my_focus").removeClass("my_focus");
    }
    function fullBackgroundColor(color) {
        $(".fulckeditor").css("background-color", color);
        $(".my_focus").removeClass("my_focus");
    }
    function fullBorderColor(color) {
        $(".fulckeditor").css("border", "1px solid " + color);
        $(".my_focus").removeClass("my_focus");
    }
    function fullTextColor(color) {
        $(".fulckeditor").css("color", color);
        $(".my_focus").removeClass("my_focus");
    }
    function rgb2hex(rgb) {
        var hexDigits = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b", "c", "d", "e", "f"];
        rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
        function hex(x) {
            return isNaN(x) ? "00" : hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];
        }
        return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
    }

    function save() {
        var ttlcamp = $("#titlecamp").val();
        if (!ttlcamp)
        {
            alert("Please Enter Your Campaign Title.");
            $("#titlecamp").focus();
            return false;
        }
        $(window).unbind('beforeunload');
        var html = $("#container").html();
        $("#EventTemplateHtml").val(html);
        document.myForm.submit();
    }

</script>

<div style="display: none;">
    <div id="myPopup" style="overflow-y:scroll;overflow-x:scroll;width: 800px; height: 400px;"></div>
</div>


<section class="inner-content">
    <div class="center-block">
        <div class="em-sec profile-whole">
            <h1>Create New Campaign</h1>
            <div class="breadcrumb">
                <ul>


                    <?php
                    if ($this->Session->check("CampaignEvent")) {
                        if ($this->Session->check("CampaignType") && $this->Session->read("CampaignType") == "single") {
                            $urlTab1 = "/Campaigns/chooseEventSingle";
                        } else {
                            $urlTab1 = "/Campaigns/chooseEventMultiple";
                        }
                        ?><a href="<?php echo $urlTab1; ?>"><li>Step 1: Select Event</li></a><?php
                    } else {
                        ?><a href="/Campaigns/chooseTemplate"><li>Step 1: Template</li></a><?php }
                    ?>

                    <li class="active">Step 2: Design</li>
                    <li >Step 3: Recipients</li>
                    <li>Step 4: Preview</li>
                </ul>
            </div>
            <div class="clear"></div>

            <?php
            echo $this->Form->create("Campaign", array("action" => "createCampaign", "id" => "myForm", "name" => "myForm"));
            echo $this->Form->input("Campaign.title", array("type" => "text", "label" => "Title of Your Campaign:", "div" => false, "id" => "titlecamp", "style" => "width: 295px; height: 30px;"));
            echo $this->Form->input("Campaign.id", array("type" => "hidden", "value" => $data["Campaign"]["id"]));
            echo $this->Form->input("Campaign.html", array("type" => "hidden", "value" => $data["Campaign"]["html"], "id" => "EventTemplateHtml"));
            ?>
            <section style="float:right; margin-right: 10%;">
                <span>
                    <a class="imageOpen smlpink_button" href='#myPopup'>Preview</a>
                </span>
                <span>
                    <a class="smlpink_button" style="cursor:pointer;" onclick="javascript:window.back();">Go Back</a>
                </span>
                <span>
                    <a class="smlpink_button" style="cursor:pointer;" onclick="javascript:save();">Save Changes</a>
                </span>
            </section>
            <?php
            echo $this->Form->end();
            ?>

            <br><hr><br>
            <div class='left-panel-box' style="background-color:#FFF !important;">
                <h2>Background Color</h2>
                <input class="cpBoth BackgroundColor" value="#FFFFFF" onchange="javascript:backgroundColor(this.value);"/>
                <br>
                <h2>Border Color</h2>
                <input class="cpBoth borderColor" value="#FFFFFF" onchange="javascript:borderColor(this.value);"/>
                <br>
                <h2>Text Color</h2>
                <input class="cpBoth textColor" value="#FFFFFF" onchange="javascript:textColor(this.value);"/>
                <br>
                <h2>Full Background Color</h2>
                <input class="cpBoth fullBackgroundColor" value="#FFFFFF" onchange="javascript:fullBackgroundColor(this.value);"/>
                <br>
                <h2>Full Border Color</h2>
                <input class="cpBoth fullBorderColor" value="#FFFFFF" onchange="javascript:fullBorderColor(this.value);"/>
                <br>
                <!--                <h2>Full Text Color</h2>
                                <input class="cpBoth" value="#31859b" onchange="javascript:fullTextColor(this.value);"/>-->
                <div style="clear:both;"></div>
                <div id="ImageHere" style="background-color:white;"></div>
            </div>


            <div id="container" class="template-design">
                <!--------------------------------- full editor ---------------------->

                <div class="fulckeditor">
                    <?php echo $data["Campaign"]["html"]; ?>
                </div>



                <!------------------------- full editor------------------------------------------------->

            </div>



        </div>
    </div>
    <div class="clear"></div>
</section>
<!-- Load widget code -->
<script type="text/javascript" src="http://feather.aviary.com/js/feather.js"></script>
<script type="text/javascript">

                    var featherEditor = new Aviary.Feather({
                        apiKey: '75a7351be279e84e',
                        apiVersion: 3,
                        tools: ['draw', 'stickers'],
                        onSave: function(imageID, newURL) {
                            var img = document.getElementById(imageID);
                            img.src = newURL;
                        },
                        postUrl: '/tests/test'
                    });

                    function launchEditor(id, src) {
                        featherEditor.launch({
                            image: id,
                            url: src
                        });
                        return false;
                    }

</script>                   


<script type="text/javascript" src="http://feather.aviary.com/js/feather.js"></script>
<script type="text/javascript">

                    var featherEditor = new Aviary.Feather({
                        apiKey: '75a7351be279e84e',
                        apiVersion: 3,
                        noCloseButton: false,
                        //tools: ['draw', 'stickers', 'crop', 'enhance', 'effects', 'orientation', 'focus', 'resize', 'warmth', 'brightness', 'contrast', 'saturation', 'sharpness', 'colorsplash', 'text', 'redeye', 'whiten', 'blemish'],
                        onSave: function(imageID, newURL) {
                            // save image to our server
                            saveMyImage(newURL, imageID);

                            //img.src = img_name;
                            featherEditor.close();
                        }

//                            onSaveButtonClicked: '/tests/test'
                    });

                    function launchEditor(id, src) {
                        featherEditor.launch({
                            image: id,
                            url: src
                        });
                        return false;
                    }

                    function saveMyImage(imageURL, imageID) {
                        //alert(imageURL);

                        $("#load_" + imageID).show();
                        jQuery.ajax({
                            url: '/Templates/saveTemplateImage/',
                            type: "POST",
                            data: {image: imageURL},
                            success: function(data) {
                                $("#" + imageID).attr("src", data);
                                $(".img_" + imageID).attr("src", data);
                                $(".img_" + imageID).attr("onclick", 'launchEditor("' + imageID + '","' + data + '");');
                                $("#load_" + imageID).hide();
                            }
                        });
                    }

                    $(document).ready(function() {
                        $(".launchEditor").each(function() {
                            // here will be all stuff
                            var src = $(this).attr("src");
                            var id = $(this).attr("id");
                            var onclick = 'launchEditor("' + id + '","' + src + '");';
                            //alert(src+"--"+id+"--"+onclick);

                            var imageURL = "<br/><img src = '" + src + "' onclick = '" + onclick + "' title='Edit' class='img_" + id + "' style='width:120px;height120px; cursor:pointer;'><span style='display: none;' id='load_" + id + "' class='loader'><img alt='' src='/img/admin/loader.gif' title='Loading'></span><br/>";
                            //alert(imageURL);
                            $("#ImageHere").append(imageURL);
                        });
                    });

</script>