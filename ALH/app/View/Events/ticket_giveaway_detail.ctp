<?php //echo "--".$zip;?>
<section class="inner-content">
    <div class="center-block">
        <div class="fti-sec" style="text-align:inherit;">
            
            <h1 align="center"><?php echo $giveaway["Event"]["title"]; ?> Ticket Giveaway</h1>
          <?php echo $this->Session->flash(); ?>
            <p class="main-para" align="center">What name and phone number would you like assigned to your tickets?</p>
            <?php
            echo $this->Form->create("Event", array("action" => "ticketGiveaway", "id" => "addEvent", 'enctype' => 'multipart/form-data'));
           if(isset($giveaway["TicketGiveaway"]["id"]))
           {
            echo $this->Form->input("TicketGiveaway.id", array("type" => "hidden", "value" => $giveaway["TicketGiveaway"]["id"]));
           }
           if(AuthComponent::user("id")){
               echo $this->Form->input("TicketGiveaway.status",array("type"=>"hidden","value"=>1));
           }
         
           ?>
            <fieldset class="ft-submit">
                <label>First Name<sub>*</sub></label>
<?php echo $this->Form->input("TicketGiveaway.first_name", array("class" => "validate[required]", "type" => "text", "label" => FALSE, "div" => false, "placeholder" => "First Name", "maxlength" => 30, "value" => AuthComponent::user("first_name")));
?>
            <input type="hidden" name="step2" value="step2">
           
                <div class="clear"></div>
                <label>Last Name<sub>*</sub></label>
<?php echo $this->Form->input("TicketGiveaway.last_name", array("class" => "validate[required]", "type" => "text", "label" => FALSE, "div" => false, "placeholder" => "Last Name", "maxlength" => 30, "value" => AuthComponent::user("last_name"))); ?>
                <div class="clear"></div>
                <!--label>Zip Code<sub>*</sub></label-->
<?php echo $this->Form->input("TicketGiveaway.zip", array("class" => "validate[custom[integer]]", "type" => "hidden", "label" => FALSE, "div" => false,  "maxlength" => 6 , "value" => $zip)); ?>
                <div class="clear"></div>
                <label>Phone<sub>*</sub></label>
<?php echo $this->Form->input("TicketGiveaway.phone", array("class" => "validate[required] phone_no", "type" => "text", "label" => FALSE, "div" => false, "placeholder" => "Phone", "value" => AuthComponent::user("phone_no"))); ?>
                <div class="clear"></div>
                <p>We need your phone number so we can confirm tickets with you over the phone.  <?php echo $branduser['Brand']['name']; ?> will never give your phone number to any third parties. It's just between you and us.</p>
                <label class="full-width">Why would you like to attend this event?</label>
                <div class="clear"></div>
                <br>
                <p>This is optional, but it might increase your chances of winning!  And yes, we really do read these.</p>
                <?php echo $this->Form->input("TicketGiveaway.reason",array("type"=>"textarea","label"=>FALSE,"div"=>false)); ?>
                <p>Fields marked with an * are required to enter our free ticket giveaways</p>
                <input type="submit" class="btn-enterNow" value="Enter Now for Free Tickets!">
            </fieldset>
<?php echo $this->Form->end(); ?>
        </div>
    </div>
</section>
<?php echo $this->Html->script('/js/Front/Events/createEvent'); ?>