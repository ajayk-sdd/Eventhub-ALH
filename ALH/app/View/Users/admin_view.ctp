<!--Content Wrapper Starts-->
<section id="cont_wrapper">
    <!--Content Starts-->
    <section class="content">
        <!--Main Heading Starts-->
        <h1 class="main_heading"> <?php echo $this->Html->link('Users', '/admin/Users/add'); ?></h1>
        <!--Main Heading Ends-->
        <!--Conetnt Info Starts Here-->
        <section class="content_info">
            <?php
            //pr($suscribeBrand);die;
            echo $this->Session->flash();
            echo $this->Form->create("User", array("action" => "view", "id" => "addUserForm"));
            echo $this->Form->input("User.id", array("type" => "hidden"));
            ?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabulardata user_detail">
                <tr>
                    <td>User ID # : <?php echo $this->data["User"]["id"]; ?> &nbsp; &nbsp; Date Joined : <?php echo date("m/d/y", strtotime($this->data["User"]["id"])); ?></td>
                    <td><?php echo $this->Form->input("User.role_id", array("type" => "select", "empty" => "Select Role", "options" => $roles, "class" => "validate[required]", "div" => false, "label" => "Type of User :*")); ?></td>
                    <td><?php echo $this->Form->input("User.ALH_point", array("type" => "text", "label" => "ALH Points :*", "div" => false, "class" => "validate[required] form_input", "id" => "ALH_point")); ?></td>
                </tr>
                <tr>
                    <td><?php echo $this->Form->input("User.email", array("type" => "text", "label" => "Email :*", "div" => false, "class" => "validate[required] form_input", "id" => "email")); ?></td>
                    <td><?php echo $this->Form->input("User.first_name", array("type" => "text", "label" => "First Name :*", "div" => false, "class" => "validate[required] form_input", "id" => "first_name")); ?></td>
                    <td><?php echo $this->Form->input("User.phone_no", array("type" => "text", "label" => "Phone # :*", "div" => false, "class" => "validate[required] form_input", "id" => "phone_no")); ?></td>
                </tr>
                <tr>
                    <td><?php echo $this->Form->input("User.password", array("type" => "password", "label" => "Password :*", "div" => false, "class" => "validate[required,maxSize[20],minSize[6]] form_input", "id" => "password", "value" => "")); ?></td>
                    <td><?php echo $this->Form->input("User.last_name", array("type" => "text", "label" => "Last Name :*", "div" => false, "class" => "validate[required] form_input", "id" => "last_name")); ?></td>
                    <td><?php echo $this->Form->input("User.key", array("type" => "text", "label" => "Wordpress key :*", "div" => false, "class" => "validate[required] form_input", "id" => "key")); ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td><?php echo $this->Form->input("User.role_id", array("type" => "select", "empty" => "Select Role", "options" => array("Dummy1", "Dummy2", "Dummy3", "Dummy4", "Dummy5", "Dummy6"), "class" => "validate[required]", "div" => false, "label" => "Email Template :*")); ?></td>
                    <td><?php echo $this->Form->input("User.is_pre_approved", array("type" => "checkbox", "label" => "User Events Are Pre-Approved", "div" => false, "class" => "validate[required] form_input", "id" => "is_pre_approved")); ?></td>
                </tr>
                <tr>
                    <td style="border: none;"><?php echo $this->Form->input("select", array("type" => "select", "div" => FALSE, "label" => "View: ", "empty" => "select", "options" => array("event" => "Events", "save_event" => "Saved Events", "suscribeBrand" => "Brand Subscriptions", "myList" => "Lists","login_info"=>"Login Informations","point_info"=>"Points Management","search"=>"Search Criteria"), "onchange" => "javascript:view(this.value);")); ?></td>
                    <td style="border: none;"><?php echo $this->Form->input("Submit", array("type" => "submit", "label" => false, "div" => false, "class" => "blu_btn_rt flt-left")); //echo $this->html->link("View Brand", "javascript:void(0);", array("class" => "blu_btn_rt")) . "&nbsp;&nbsp;" . $this->html->link("LOGIN as Username", "javascript:void(0);", array("class" => "blu_btn_rt")); ?></td>
                    <td style="border: none;"><?php //"&nbsp;&nbsp;" . $this->html->link("Delete User", "javascript:void(0);", array("class" => "blu_btn_rt")); ?></td>
                </tr>
            </table>
            <?php echo $this->Form->end(); ?>
            <table id="event" class="display dataTable">
                <thead>
                    <tr>
                        <th style="text-align: left;">Title</th>
                        <th style="text-align: left;">Sub Title</th>
                        <th style="text-align: left;">Location</th>
                        <th style="text-align: left;">Zip</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event) { ?>
                        <tr>
                            <td><?php echo $event["Event"]["title"]; ?></td>
                            <td><?php echo $event["Event"]["sub_title"]; ?></td>
                            <td><?php echo $event["Event"]["event_location"]; ?></td>
                            <td><?php echo $event["Event"]["cant_find_zip_code"]; ?></td>
                            
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <table id="save_event" class="display dataTable">
                <thead>
                    <tr>
                        <th style="text-align: left;">Title</th>
                        <th style="text-align: left;">Sub Title</th>
                        <th style="text-align: left;">Location</th>
                        <th style="text-align: left;">Zip</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($myEvents as $myEvent) { ?>
                        <tr>
                            <td><?php echo $myEvent["Event"]["title"]; ?></td>
                            <td><?php echo $myEvent["Event"]["sub_title"]; ?></td>
                            <td><?php echo $myEvent["Event"]["event_location"]; ?></td>
                            <td><?php echo $myEvent["Event"]["cant_find_zip_code"]; ?></td>
                           
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <table id="suscribeBrand" class="display dataTable">
                <thead>
                    <tr>
                        <th style="text-align: left;">Brand Name</th>
                        <th style="text-align: left;">Logo</th>
                        <th style="text-align: left;">Description</th>
                        <th style="text-align: left;">Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($suscribeBrands as $suscribeBrand) { ?>
                        <tr>
                            <td><?php echo $suscribeBrand["Brand"]["name"]; ?></td>
                            <td><?php echo $this->html->image("brand/small/" . $suscribeBrand["Brand"]["logo"]); ?></td>
                            <td><?php echo $suscribeBrand["Brand"]["description"]; ?></td>
                            <td><?php echo date("j F y", strtotime($suscribeBrand["Brand"]["created"])); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <table id="myList" class="display dataTable">
                <thead>
                    <tr>
                        <th style="text-align: left;">List Name</th>
                        <th style="text-align: left;">List Status</th>
                        <th style="text-align: left;">Dedicated Send Point</th>
                        <th style="text-align: left;">Muti Event point</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($myLists as $myList) { ?>
                        <tr>
                            <td><?php echo $myList["MyList"]["list_name"]; ?></td>
                            <td><?php echo $myList["MyList"]["list_status"]; ?></td>
                            <td><?php echo $myList["MyList"]["dedicated_send_points"]; ?></td>
                            <td><?php echo $myList["MyList"]["multi_event_points"]; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <table id="login_info" class="display dataTable">
                <thead>
                    <tr>
                        <th style="text-align: left;">Login</th>
                        <th style="text-align: left;">Ip</th>
                        <th style="text-align: left;">Zip</th>
                        <th style="text-align: left;">City</th>
                        <th style="text-align: left;">State</th>
                        <th style="text-align: left;">Country</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($information as $info) { ?>
                        <tr>
                            <td><?php echo $info["Information"]["last_login"]; ?></td>
                            <td><?php echo $info["Information"]["ip"]; ?></td>
                            <td><?php echo $info["Information"]["zip"]; ?></td>
                            <td><?php echo $info["Information"]["city"]; ?></td>
                            <td><?php echo $info["Information"]["state"]; ?></td>
                            <td><?php echo $info["Information"]["country"]; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            
            <table id="point_info" class="display dataTable">
                <thead>
                    <tr>
                        <th style="text-align: left;">Points</th>
                        <th style="text-align: left;">Price</th>
                        <th style="text-align: left;">Mode</th>
                        <th style="text-align: left;">Date</th>
                        <th style="text-align: left;">Status</th>
                        <th style="text-align: left;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cashoutpoint as $point_info) {
                        if(isset($point_info["CashOutPoint"])) {
                        ?>
                        <tr>
                            <td><?php echo $point_info["CashOutPoint"]["point"].' Points'; ?></td>
                            <td><?php echo '$'.$point_info["CashOutPoint"]["price"]; ?></td>
                            <td><?php echo 'Cashout Points'; ?></td>
                            <td><?php echo $point_info["CashOutPoint"]["date"]; ?></td>
                            <td><?php echo $point_info["CashOutPoint"]["status"]; ?></td>
                            <td><?php echo $this->Html->link('View Detail', '/admin/Users/pointDetail/cashout/'.base64_encode($point_info["CashOutPoint"]["id"])); ?></td>
                        </tr>
                    <?php }
                    elseif(isset($point_info["Payment"])){
                        ?>
                          <tr>
                            <td><?php echo $point_info["Package"]["name"]; ?></td>
                            <td><?php echo '$'.$point_info["Payment"]["amount"]; ?></td>
                            <td><?php echo 'Buy Points'; ?></td>
                            <td><?php echo date("Y-m-d", strtotime($point_info["Payment"]["created"])); ?></td>
                            <td><?php if($point_info["Payment"]["status"]==1)
                            {
                                echo 'Completed';
                            }
                            else
                            {
                                echo 'In Completed';
                            }?></td>
                          <td><?php echo $this->Html->link('View Detail', '/admin/Users/pointDetail/buy/'.base64_encode($point_info["Payment"]["id"])); ?></td>
                        </tr>
                   <?php     
                    }
                    } ?>
                </tbody>
            </table>
            
            
            <table id="search" class="display dataTable">
                <thead>
                    <tr>
                        <th style="text-align: left;">Title</th>
                        <th style="text-align: left;">Feature</th>
                        <th style="text-align: left;">Zip</th>
                        <th style="text-align: left;">Giveaway</th>
                        <th style="text-align: left;">Category</th>
                        <th style="text-align: left;">Vibe</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($search)) { ?>
                        <tr>
                            <td><?php echo $search["Search"]["title"]; ?></td>
                            <td><?php
                                if (!empty($search["Search"]["is_feature"]))
                                    echo "Yes";
                                else
                                    echo "No";
                                ?></td>
                            <td><?php echo $search["Search"]["zip"]; ?></td>
                            <td><?php
                                if (!empty($search["Search"]["giveaway"]))
                                    echo "Yes";
                                else
                                    echo "No";
                                ?></td>
                            <td><?php if(isset($category)) echo $category; else echo "N/A"; ?></td>
                            <td><?php if(isset($vibe)) echo $vibe; else echo "N/A"; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <section class="clr_bth"></section>
        </section>
        <!--Conetnt Info Ends Here-->
    </section>
    <!--Content Ends-->
</section>
<!--Content Wrapper Ends-->
<?php echo $this->Html->script('/js/admin/Users/admin_add'); ?>
<script>
    $(document).ready(function() {
        $(".dataTable").hide(600);

    });
    function view(to_show) {
        $(".dataTable").hide();
        $("#event_wrapper").hide();
        $("#save_event_wrapper").hide();
        $("#suscribeBrand_wrapper").hide();
        $("#myList_wrapper").hide();
        $("#login_info_wrapper").hide();
        $("#point_info_wrapper").hide();
        $("#search_wrapper").hide();

        $("#" + to_show).show(200);
        $("#" + to_show + "_wrapper").show(200);
        $("#" + to_show).DataTable();
    }
</script>
