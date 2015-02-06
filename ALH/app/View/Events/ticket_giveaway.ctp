<section class="inner-content">
    <div class="center-block">
        <div class="fti-sec">
            <h1><?php echo $event["Event"]["title"]; ?> Ticket Giveaway</h1>
            <?php echo $this->Session->flash(); ?>
            <h2>Win Tickets to <br><strong><?php echo $event["Event"]["title"]; ?><br> <?php echo date("l, F jS, Y h:i A", strtotime($event["Event"]["start_date"])); ?> <br><?php echo $event["Event"]["cant_find_city"]; ?></strong></h2>
            <?php if(!AuthComponent::user("id")){
             $dec_eventId = base64_encode($event["Event"]["id"]);
                ?>
            <a onclick="javascript:facebookLogin('/Events/ticketGiveaway/<?php echo $dec_eventId; ?>');" href="javascript:void(0);" class="btn-enterNow">Enter Now for Free Tickets with FaceBook</a>
            <?php }?>
            <?php
            echo $this->Form->create("Event", array("action" => "ticketGiveaway", "id" => "addEvent", 'enctype' => 'multipart/form-data'));
            echo $this->Form->input("TicketGiveaway.user_id", array("type" => "hidden", "value" => AuthComponent::User("id")));
            echo $this->Form->input("TicketGiveaway.event_id", array("type" => "hidden", "value" => $event["Event"]["id"]));
            ?>
            <?php if(!AuthComponent::user("id")){ ?>
            <fieldset>
                <label>Email Address<sub>*</sub></label>
                <?php echo $this->Form->input("TicketGiveaway.email", array("type" => "text", "label" => FALSE, "div" => false, "class" => "validate[required,custom[email]] form_input", "id" => "email", "value" => AuthComponent::user("email"))); ?>
            </fieldset>
            
             <?php }
             else
             {
                echo $this->Form->input("TicketGiveaway.email", array("type" => "hidden", "label" => FALSE, "div" => false, "class" => "validate[required,custom[email]] form_input", "id" => "email", "value" => AuthComponent::user("email")));
             }
             ?>
            <input type="submit" class="btn-enterNow" value="Enter Now for Free Tickets!">
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</section>
<?php echo $this->Html->script('/js/Front/Events/createEvent'); ?>