<section class="inner-content">
    <div class="center-block">
        <div class="em-sec">
            <h1>Choose Email Lists</h1>
            <div class="breadcrumb">
                <ul>
                    <li>Step 1: Design</li>
                    <li class="active">Step 2: Set Up</li>
                    <li>Step 3: Preview</li>
                    <li>Step 4: Recipients</li>
                </ul>
            </div>
            <div class="clear"></div>
            <div class="em-sec-inner campaign_outer">
                <?php
                //echo $this->Session->flash();
                echo $this->Form->create("Campaign", array('class' => 'event-form', "action" => "setUp", "id" => "addCamp"));
                //echo $this->Form->hidden("Event.id");
                echo $this->Form->hidden("Campaign.id", array("value" => $campaign_id));
                echo $this->Form->hidden("Campaign.status", array("value" => 1));
                echo $this->Form->hidden("Campaign.campaign_status",array("value"=>$campaign_status));
                ?>
                <div class="emsi-part em-pa-lt">
                    <label><strong>Title of Your Campaign:</strong> <span class="red-star">*</span></label>
                    <?php echo $this->Form->input("title", array("type" => "text", "label" => FALSE, "div" => false, "class" => "txtbx-bg validate[required] ", "id" => "title", 'placeholder' => 'The Title of your campaign')); ?>
                    <br> <br>
                    <label><strong>Email Subject Line:</strong> <span class="red-star">*</span></label>
                    <?php echo $this->Form->input("subject_line", array("type" => "text", "label" => FALSE, "div" => false, "class" => "validate[required] txtbx-bg", "id" => "sub_title", 'placeholder' => 'Enter Subject Line')); ?>

                    <label><strong>Reply Email Address:</strong><span class="red-star">*</span> </label>
                    <?php echo $this->Form->input("reply_email", array("type" => "text", "label" => FALSE, "div" => false, "class" => "validate[required] txtbx-bg", 'placeholder' => 'A Subtitle for your event (optional)')); ?>
                    <br> <br>
                    <label><strong>Choose the Date you would like to send your email campaign:</strong><span class="red-star">*</span></label>
                    <?php echo $this->Form->input("date_to_send", array("type" => "text", "label" => FALSE, "div" => false, "class" => "txtbx-bg validate[required]", "id" => "dateCampaign")); ?>

                    <div class="clear"></div>

                </div>
                <div class="emsi-part em-pa-rt">
                    <label><strong>From Name:</strong><span class="red-star">*</span></label>
                    <?php echo $this->Form->input("from_name", array("type" => "text", "label" => FALSE, "div" => false, "class" => "txtbx-bg validate[required] ")); ?>
                    <label><strong>From Email Address:</strong><span class="red-star">*</span></label>
                    <?php echo $this->Form->input("from_email", array("type" => "text", "label" => FALSE, "div" => false, "class" => "txtbx-bg validate[required] ")); ?>


                </div>
                <div class="clear"></div>
                <br>
                <?php
                echo $this->Form->input("Continue", array("type" => "submit", "label" => false, "name" => "save"));
                echo $this->Form->end();
                ?>                   
            </div>
            <div class="clear"></div>
            <div class="breadcrumb">
                <ul>
                    <li>Step 1: Design</li>
                    <li class="active">Step 2: Set Up</li>
                    <li>Step 3: Preview</li>
                    <li>Step 4: Recipients</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!--Bottom Details Section Ends-->    
<?php
echo $this->Html->css('jquery-ui');
echo $this->Html->script('/js/jquery-ui-1.10.4.min');
?>
<script type="text/javascript">
    $(function() {
        $("#dateCampaign").datepicker({
		       dateFormat: 'yy-mm-dd'    
		   });
    });
    $("#addCamp").validationEngine();
</script>