<!--Content Wrapper Starts-->
<section id="cont_wrapper">
    <!--Content Starts-->
    <section class="content">
        <!--Main Heading Starts-->
        <h1 class="main_heading"> <?php echo $this->Html->link('Events', '/admin/Events/add'); ?></h1>
        <!--Main Heading Ends-->
        <!--Conetnt Info Starts Here-->
        <section class="content_info">
            <?php
            echo $this->Session->flash();
             ?>
            <ul class="form">
                <li>
                    <label>Title :</label><label><?php echo $event['Event']['title']; ?></label>
                </li>
                <li>
                    <label>Sub-title :</label><label><?php echo $event['Event']['sub_title']; ?></label>
                </li>
                <li>
                    <label>Is this the event public or private ? :</label><label><?php
                        if ($event['Event']['event_type'] == 1)
                            echo 'Private';
                        else
                            echo 'Public';
                        ?></label>
                </li>
<?php if ($event['Event']['event_type'] == 0) { ?>
                    <li>
                        <label>Show on a list hub calendar ? :</label><label><?php
                            if ($event['Event']['option_to_show'] == 1)
                                echo 'Private';
                            else
                                echo 'Public';
                            ?></label>
                    </li>
                    <li>
                        <label>Show on a wordpress calendar ? :</label><label><?php
                            if ($event['Event']['option_to_show'] == 1)
                                echo 'Private';
                            else
                                echo 'Public';
                            ?></label>
                    </li>
<?php } ?>
                <li>
                    <label>location :</label><label><?php echo $event['Event']['event_location']; ?></label>
                </li>
                <li>
                    <label>Specify location :</label><label><?php echo $event['Event']['specify']; ?></label>
                </li>
                <li>
                    <label>Address :</label><label><?php echo $event['Event']['event_address']; ?></label>
                </li>
                <li>
                    <label>City :</label><label><?php echo $event['Event']['cant_find_city']; ?></label>
                </li> <li>
                    <label>State :</label><label><?php echo $event['Event']['cant_find_state']; ?></label>
                </li> <li>
                    <label>Zip :</label><label><?php echo $event['Event']['cant_find_zip_code']; ?></label>
                </li> <li>
                    <label>Start Date :</label><label><?php echo $event['Event']['start_date']; ?></label>
                </li> <li>
                    <label>End Date :</label><label><?php echo $event['Event']['end_date']; ?></label>
                </li> <li>
                    <label>Ticket Vendor Url :</label><label><?php echo $event['Event']['ticket_vendor_url']; ?></label>
                </li> <li>
                    <label>Website URL :</label><label><?php echo $event['Event']['website_url']; ?></label>
                </li> <li>
                    <label>Facebook URL :</label><label><?php echo $event['Event']['facebook_url']; ?></label>
                </li> <li>
                    <label>Video :</label><label><?php echo $event['Event']['video']; ?></label>
                </li> 
                <li>
                    <label>Flyer Image :</label><label><?php echo $this->html->image("flyerImage/small/".$event['Event']['flyer_image']); ?></label>
                </li> 
                 <li>
                    <label>Event Images :</label>
                    <?php foreach ($event["EventImages"] as $eventImage){?>
                    <label><?php echo $this->html->image("EventImages/small/".$eventImage['image_name']); ?></label>
                    <?php }?>
                </li>
                <li>
                    <label>Main Contact Info for your Event  :</label></li> <li>
                    <label>Name :</label><label><?php echo $event['Event']['main_info_name']; ?></label>
                </li> <li>
                    <label>Email :</label><label><?php echo $event['Event']['main_info_email']; ?></label>
                </li><li>
                    <label>Phone Number :</label><label><?php echo $event['Event']['main_info_phone_no']; ?></label>
                </li> <li>
                    <label>Allow User to edit :</label><label><?php if($event['Event']['allow_users_to_edit'] = 1) echo "Yes"; else echo "No"; ?></label>
                </li><li>
                    <label>Short Description :</label><label><?php echo $event['Event']['short_description']; ?></label>
                </li> <li>
                    <label>Long Description :</label><label><?php echo $event['Event']['description']; ?></label>
                </li><li>
                    <label>Event URL For Share :</label><label><?php echo $event['Event']['event_url_for_share']; ?></label>
                </li>
                <li>
                    <label>Categories :</label>
                    <?php foreach ($event["EventCategory"] as $cate){?>
                    <label><?php echo $cate["Category"]["name"].","; ?></label>
                    <?php }?>
                </li>
                <li>
                    <label>Vibes :</label>
                    <?php foreach ($event["EventVibe"] as $vibe){?>
                    <label><?php echo $vibe["Vibe"]["name"].","; ?></label>
                    <?php }?>
                </li>
                <li>
                    <label>Status :</label><label><?php
                        if ($event['Event']['status'] == 1)
                            echo 'Active';
                        else
                            echo 'Inactive';
                        ?></label>
                </li>
                <li>
                    <span class="blu_btn_lt">
                        <input type="reset" id="EventReset" class="blu_btn_rt" onclick="javascript:history.back();" value="Go Back"></span>
                </li>
            </ul>


            <section class="clr_bth"></section>
        </section>
        <!--Conetnt Info Ends Here-->
    </section>
    <!--Content Ends-->
</section>
<!--Content Wrapper Ends-->
<?php echo $this->Html->script('/js/admin/Events/admin_add'); ?>