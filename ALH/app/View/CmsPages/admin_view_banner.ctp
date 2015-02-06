<?php
if(!empty($banner["Banner"]["size"])){
    $banner_size = explode("*", trim($banner["Banner"]["size"]));
    $width = $banner_size[0]."px";
    $height = $banner_size[1]."px";
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
            <h1>View Banner</h1>
            <ul>
                <li><img id="imgprvw"/></li>

            </ul>

            <div class="note_for_banner">


            </div>
            <?php //echo $this->Form->create("CmsPages", array("action" => "editBanner/".base64_encode($bannerData['Banner']['id']), "id" => "EditBannerForm", 'enctype' => 'multipart/form-data')); ?>

            <ul class="form">
                <li>
                    <label>Banner Image</label>
                    <?php 
                    if($width>0){
                    echo $this->html->image($banner["Banner"]["image_name"], array("style"=>"width:$width;height:$height"));
                    } else {
                        echo $this->html->image($banner["Banner"]["image_name"]);
                    }
                    ?>
                </li>
                
                <li>
                    <label>Background Image</label>
                    <?php 
                    if($width>0){
                    echo $this->html->image($banner["Banner"]["background_image"], array("style"=>"width:$width;height:$height;"));
                    } else {
                        echo $this->html->image($banner["Banner"]["background_image"]);
                    }
                    ?>
                </li>

                <li>
                    <label>Size</label>
                    <?php echo $banner["Banner"]["size"]; ?>
                </li>
                <li>
                    <label>From Brand</label>
                    <?php echo $banner["Brand"]["name"]; ?>
                </li>
                <li>
                    <label>Type</label>
                    <?php if ($banner["Banner"]["type"] == 0) echo "ALH site";
                    else echo "Wordpress plugin"; ?>
                </li>
<?php if (!empty($banner["Event"]["title"])) { ?>
                    <li>
                        <label>Event</label>
                    <?php echo $banner["Event"]["title"]; ?>
                    </li>
                <?php } ?>
                <?php
                $cnt = 0;
                foreach ($banner['BannerImage'] as $data):
                    ?>

                    <table>
                        <tr class="tickets" id="ticketsId">
                            <td>  
                                <fieldset style="padding:20px;">
                                    <legend>Details</legend>



                                    <li class="bnr_det">
                                        <label>To Brand</label>
                                        <?php if (isset($data["Brand"]["name"]))
                                            echo $data["Brand"]["name"];
                                        else
                                            echo "N/A";
                                        ?>
                                    </li>

                                    <li class="bnr_det">
                                        <label>Location</label>
                                        <?php echo $data["location"]; ?>
                                    </li>

                                    <li class="bnr_det">
                                        <label>URL</label>
                                    <?php echo $this->html->link($data["url"], "http://" . $data["url"]); ?>
                                    </li>

    <?php /* ?><li id = "event">
      <?php echo $this->Form->input("Banner.event_id", array("type" => "select", "div" => false, "label" => "Event :*", "class" => "validate[required] form_input", "id" => "event_id", "options" => $events)); ?>
      </li><?php */ ?>

                                    <li class="bnr_det">
                                        <label>Show Banner</label>
                                        <?php
                                        if ($data["is_show"] == 0) {
                                            echo "Permanently";
                                        } else {
                                            echo "Selected Date Range";
                                        }
                                        ?>
                                    </li>
                                        <?php if ($data["is_show"] == 1) { ?>
                                        <li class = "showCal" style="display:block;" class="bnr_det">
                                            <label>Date Range</label>

                                        <?php echo date("m/d/Y", strtotime($data["start_date"])); ?>
                                        <?php echo " to " . date("m/d/Y", strtotime($data["end_date"])); ?>

                                        </li>

    <?php } ?>




                                </fieldset>
                            </td>
                        </tr>
                    </table>
                        <?php
                        $cnt = $cnt + 1;
                    endforeach;
                    ?>       
                <section class="login_btn" style="width: 29%;">
                    <span class="blu_btn_lt" style="float:left;">
                        <input type = "button" value="Go Back" class="blu_btn" onclick="javascript:history.back();">
                    </span>
                </section>

            </ul>

<?php //echo $this->Form->end();       ?>
            <section class="clr_bth"></section>
        </section>
        <!--Conetnt Info Ends Here-->
    </section>
    <!--Content Ends--> 
</section>
<?php echo $this->Html->script('/js/admin/CMS/admin_add_banner'); ?>