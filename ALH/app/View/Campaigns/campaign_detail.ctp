<?php //echo base64_decode($packageID); die;                  ?>                                      
<section class="inner-content">
    <div class="center-block">
        <div class="em-sec">
            <h1>Create New Campaign</h1>
            <?php echo $this->Session->flash(); ?>
            <div class="breadcrumb">
                <ul>
                    <li >Step 1: Template</li>
                    <li>Step 2: Design</li>
                    <li class="active">Step 3: Recipients</li>
                    <li>Step 4: Preview</li>
                </ul>
            </div>
            <div class="clear"></div>

            <div class="em-sec-inner" style="width: 100%;">
                <?php
                echo $this->Form->create("Campaign", array("action" => "campaignDetail", "class" => "event-form", "id" => "payment_form"));
                echo $this->Form->input("Campaign.id", array("type" => "hidden"));
                ?>
                <div class="emsi-part em-pa-lt">

                    <label><strong>Email Subject Line</strong></label>
                    <?php echo $this->Form->input("Campaign.subject_line", array("class" => "txtbx-bg validate[required]", "label" => FALSE, "div" => FALSE, "placeholder" => "Email Subject Line", "value" => $campTitle)); ?>
                    <br> <br>

                    <label><strong>Reply Email Address</strong></label>
                    <?php echo $this->Form->input("Campaign.reply_email", array("class" => "txtbx-bg validate[required,custom[email]]", "label" => FALSE, "div" => FALSE, "placeholder" => "Reply Email Address", "value" => $campReplyEmail)); ?>
                    <br> <br>
                    <label><strong>Select List</strong></label>

                    <?php
                    $ListSelected1 = array("segmentid"=>"Segment","mylistdiv"=>"My List");
                    $ListAttributes1 = array('choosetype' => false, 'label' => 'asd', 'id' => 'radiochoose', 'class' => 'validate[required]',"onclick"=>"javascript:radiochoose();", "value" => $listMode);
                    echo $this->Form->radio("emailListklk", $ListSelected1, $ListAttributes1);
                    
                    ?>

                    <div style="border: 1px solid #DFDFDF; height: 300px;padding: 30px;">
                        <div id="mylistdiv" <?php if($listMode!="mylistdiv"){ echo 'style="display: none;"'; } ?>>
                            <?php
                            if (!empty($this->data["CampaignList"])) {
                                foreach ($this->data["CampaignList"] as $cl) {
                                    $emaillist[] = $cl["my_list_id"];
                                }
                            } else {
                                $emaillist = array();
                            }
                            /* foreach ($lists as $list) {
                              if (in_array($list["MyList"]["id"], $emaillist)) {
                              echo $this->Form->input("email", array("type" => "checkbox", "name" => "data[Campaign][email][]","checked"=>"checked", "value" => $list["MyList"]["id"], "label" => $list["MyList"]["list_name"] . "(" . $list["MyList"]["count"] . ")")) . "<br>";
                              } else {
                              echo $this->Form->input("email", array("type" => "checkbox", "name" => "data[Campaign][email][]", "value" => $list["MyList"]["id"], "label" => $list["MyList"]["list_name"] . "(" . $list["MyList"]["count"] . ")")) . "<br>";
                              }
                              } */
                            $Listoptions = array();
                            foreach ($lists as $list) {
                                $listId = $list["MyList"]["id"];
                                $Listoptions[$listId] = $list["MyList"]["list_name"] . "(" . $list["MyList"]["count"] . ")";
                            }

                            // echo $this->data['CampaignList'][0]['my_list_id'];
                            if (isset($this->data['CampaignList'][0]['my_list_id'])) {
                                $ListSelected = $this->data['CampaignList'][0]['my_list_id'];
                            } else {
                                $ListSelected = '';
                            }
                            
                            $ListAttributes = array('legend' => false, 'label' => 'asd', 'id' => 'radio', 'class' => 'validate[required]', "value" => $listIdMail);
                            echo $this->Form->radio("emailList", $Listoptions, $ListAttributes);
                            ?>
                        </div>
                        <div id="segmentid" <?php if($listMode!="segmentid"){ echo 'style="display: none;"'; } ?>>
                            <?php
                            if (isset($segments) && !empty($segments)) {
                                $Listoptions = array();
                                foreach ($segments as $segment) {
                                    $segmentid = $segment["Segment"]["id"];
                                    $Listoptions[$segmentid] = $segment["Segment"]["name"] . "(" . $segment["Segment"]["count"] . ")";
                                }
                                $ListAttributes = array('legend' => false, 'label' => 'asd', 'id' => 'radios', 'class' => 'validate[required]', "value" => $segmntId);
                                echo $this->Form->radio("emailListSegment", $Listoptions, $ListAttributes);
                            }
                            ?>
                        </div>
                    </div>

                    <br> <br>
                    <label><strong>Send / Schedule Campaign</strong></label>
                    <label><?php
                        $options = array('0' => 'Send Now' . "", '1' => 'Shedule Campaign');
                        $attributes = array('legend' => false, 'label' => 'send_camp', 'id' => 'radio', 'class' => 'send_comm validate[required]');
                        echo $this->Form->radio('send_mode', $options, $attributes);
                        ?></label>


                    <div <?php if ($this->data['Campaign']['send_mode']!="1") { echo 'style="display:none"'; } ?> id="showShed" class="showShed">
                        <label>Date To Send</label>

                        <?php
// pr($this->data);
                        if (isset($this->data['Campaign']['date_to_send']) && !empty($this->data['Campaign']['date_to_send']) && $this->data['Campaign']['date_to_send'] != "0000-00-00 00:00:00") {
                            $tod = $this->data['Campaign']['date_to_send'];
                        } else {
                            $tod = date("Y-m-d h:i:s");
                        }

                        echo $this->Form->input("Campaign.date_to_send", array("type" => "text", "class" => "startdate txtbx-bg validate[required]", "label" => FALSE, "div" => FALSE, "value" => $tod));
                        ?>
                    </div>
        
         <?php
                    echo $this->Form->input("Submit", array("type" => "submit", "label" => false));
                    ?>
                </div>


                <div class="emsi-part em-pa-rt">

                    <label><strong>From Name</strong></label>
                    <?php echo $this->Form->input("Campaign.from_name", array("class" => "txtbx-bg validate[required]", "label" => FALSE, "div" => FALSE, "placeholder" => "From Name", "value" => $fromName)); ?>
                    <br> <br>

                    <label><strong>From Email Address</strong></label>
                    <?php echo $this->Form->input("Campaign.from_email", array("class" => "txtbx-bg validate[required,custom[email]]", "label" => FALSE, "div" => FALSE, "placeholder" => "From Email Address", "value" => $campFromEmail)); ?>
                    <br> <br>
                    <label><strong>Enter Email address(separated by "," )</strong></label>
                    <?php
                    echo $this->Form->input("Campaign.custom_email", array("type" => "textarea", "class" => "txtbx-bg", "style" => "border: 1px solid #DFDFDF; height: 300px;", "label" => FALSE, "div" => FALSE, "placeholder" => "Enter Each Email Address"));
                    ?>


                   
                </div>
                <?php echo $this->Form->end(); ?>
                <div class="clear"></div>
            </div></div></section>
            <?php
            echo $this->Html->script('/js/ui-timepicker.js');
            ?>
            
		
<script>
    $(document).ready(function() {
        $("#payment_form").validationEngine();
        $(".startdate").datetimepicker({
            dateFormat: 'yy-mm-dd',
            minDate: new Date(),
            controlType: 'select',
           
        });


        $(".send_comm").click(function() {
            if ($('#radio1').is(':checked')) {
                $('#showShed').show();
            } else {
                $('#showShed').hide();
            }
        });
        
        
    

    });
    function radiochoose(){
            
           if ($('#radiochooseSegmentid').is(':checked')) {
                $("#segmentid").show();
                $("#mylistdiv").hide();
            } else {
                $("#segmentid").hide();
                $("#mylistdiv").show();
            }
        }
</script>