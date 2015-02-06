<?php
//pr($datas);
?>


<div class="center-block">
    <div class="em-sec">
        <h1>Event Marketing</h1>
        <?php echo $this->Session->flash(); ?>
        <?php if ($this->Session->check("event_id")) { ?>
            <div class="breadcrumb">
                <ul>
                    <li>Step 1: Event Details</li>
                    <li>Step 2: Confirm Details</li>
                    <li class="active">Step 3: Event Marketing</li>
                    <li>Step 4: Share Your Event</li>
                </ul>
            </div>
        
        <h1 class="event-title" style="color: #7900A0;"><?php echo $event_name;?></h1>
        <p class="cong-msg">You've created an event!  Now promote it to be sure as many people as as possible come to your event. That might sound a bit overwhelming, but we've got you covered.  ALH offers a wide range of services, and making it easy to promote your event.  See the options below.</p>
       <?php } ?> <div class="clear"></div>
        <?php
        foreach ($packageData as $data) {
            ?>
            <div class="col-3">
                <div class="package-box">
                    <h3><?php echo $data["Package"]["name"]; ?></h3>
                    <div>
                        <p class="package-desc"><?php echo $data["Package"]["description"]; ?></p>
                        <ul>
                            <?php
                            foreach ($data["Service"] as $service):
                                echo "<li>" . $service["name"] . "</li>";
                                ?>
                            <?php endforeach; ?>
                        </ul>

                        <div class="clear"></div>
                        <input type="text" readonly class="rate-box" value="$ <?php echo $data["Package"]["price"]; ?>">
                        <?php echo $this->html->link("Pay Now", array("controller" => "Payments", "action" => "payForPackage", base64_encode($data["Package"]["id"])), array("class" => "btn-addToCart")); ?>

                    </div>
                </div>
            </div>
            <?php
        }
        ?>

        <div class="col-3">
            <form name="event-detail" class="event-form" action="#" method="post">
                <p class="para-1">Not seeing anything here that works for what you need?  Build your own promotional package by choosing from our wide range of services on our</p>

                <?php echo $this->html->link("Promotional Material Ala Carte Page", array("controller" => "Services", "action" => "alacartePromotionalService"), array("class" => "proMed")); ?>
                <?php echo $this->html->link("Skip this & Continue", array("controller" => "Events", "action" => "shareEvent"), array("class" => "anc_link")); ?>
                <?php echo $this->html->link("Get Started", array("controller" => "Services", "action" => "alacartePromotionalService"), array("class" => "anc_link")); ?>

            </form>
        </div>
        <div class="clear"></div>
        <br> <br> <br>
        <?php if ($this->Session->check("event_id")) { ?>
            <div class="breadcrumb">
                <ul>
                    <li>Step 1: Event Details</li>
                    <li>Step 2: Confirm Details</li>
                    <li class="active">Step 3: Event Marketing</li>
                    <li>Step 4: Share Your Event</li>
                </ul>
            </div>
        <?php } ?>
    </div>
</div>
