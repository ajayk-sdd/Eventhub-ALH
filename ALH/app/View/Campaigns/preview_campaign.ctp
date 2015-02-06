<section class="inner-content">
    <div class="center-block">
        <div class="em-sec">
            <h1>Preview Campaign</h1>
            <div class="breadcrumb">
                               <ul>

                    
                   <?php if ($this->Session->check("CampaignEvent")) { 
                  if($this->Session->check("CampaignType") && $this->Session->read("CampaignType")=="single")
                  {
                    $urlTab1 = "/Campaigns/chooseEventSingle";
                  }
                  else
                  {
                     $urlTab1 = "/Campaigns/chooseEventMultiple";
                  }
                        ?><a href="<?php echo $urlTab1; ?>"><li>Step 1: Select Event</li></a><?php
                    } else {
                        ?><a href="/Campaigns/chooseTemplate"><li>Step 1: Template</li></a><?php }
                    ?>

                    <a href="/Campaigns/designTemplate/<?php echo base64_encode($campaign["Campaign"]["id"]); ?>"><li>Step 2: Design</li></a>
                    <a href="/Campaigns/campaignDetail/<?php echo base64_encode($campaign["Campaign"]["id"]); ?>"><li>Step 3: Recipients</li></a>
                    <li class="active">Step 4: Preview</li>
                </ul>
            </div>
            <div class="clear"></div>
            <div class="mo-sec-inner previewcampaign-whole">

                <div class="previewcampgn-inner">
                    <h3>Please review the details of your campaign before sending:</h3>
  
                    <div style="float: left; width: 50%;" width="50%">
                    <label><strong>Email Subject Line: </strong></label>
                    <label><?php echo $campaign["Campaign"]["subject_line"]; ?></label><br>
                    </div>
 
                    <div style="float: left; width: 50%;">
                    <label><strong>Email From Name: </strong></label>
                    <label><?php echo $campaign["Campaign"]["from_name"]; ?></label><br>
                    </div>
 
                    <div style="float: left; width: 50%;">
                    <label><strong>Email From Email: </strong></label>
                    <label><?php echo $campaign["Campaign"]["from_email"]; ?></label><br>
                    </div>
                    
                    <div style="float: left; width: 50%;">
                    <label><strong>Email Reply Email: </strong></label>
                    <label><?php echo $campaign["Campaign"]["reply_email"]; ?></label><br>
                    </div>
                    
                    <div style="width: 100%; float: left; border: 1px solid rgb(204, 204, 204); padding: 10px; margin-top: 15px;">
                    
                    <div style="float: left; width: 50%;">
                    <?php if (!empty($campaignLists)) { ?>
                        <label><strong>Lists Email: </strong></label>
                        <label><?php
                        foreach ($campaignLists as $cl) {
                            echo $cl." (".$count.") "."<br>";
                        }
                        echo '</label>';
                    }
                    ?>
                    </div>
                    
                     <div style="float: left; width: 50%;">
                    <label><strong>Custom Email: </strong></label>
                    <label><?php echo $campaign["Campaign"]["custom_email"]; ?></label><br>
                    </div>
                     </div>
                    
                    <div class="previewinner-sbmt-btn">
                        <?php echo $this->Html->link("Change", array("controller" => "Campaigns", "action" => "campaignDetail", base64_encode($campaign["Campaign"]["id"])), array("class" => "violet_button")); ?>
                    </div>
                </div>

            </div>
            
            <?php if(isset($event) && !empty($event)) { ?>
            
            <div class="mo-sec-inner previewcampaign-whole">

                <div class="previewcampgn-inner">
                  <h4>Events Chosen:</h4>
                  <table style="width:100%">
                    <?php foreach($event as $eventList) { ?>
                    
                     <?php
                            $viewDetails = BASE_URL . '/Events/viewEvent/' . base64_encode($eventList["Event"]["id"]);
                            if ($eventList["Event"]["event_from"] == "eventful") {
                            
                                $viewDetails = BASE_URL . '/Events/viewEventfulEvent/' . $eventList["Event"]["ef_event_id"];
                            }
                            ?>
                    <tr>
                        <td>
                              <a href="<?php echo $viewDetails; ?>" style="text-decoration: underline">
                              <?php
                                        if (strlen($eventList["Event"]["title"]) > 40) {
                                            echo substr($eventList["Event"]["title"], 0, 40) . "..";
                                        } else {
                                            echo $eventList["Event"]["title"];
                                        }
                               ?>
                            </a>
                        </td>
                         <td>
                         <?php
                                    $n = 1;

                                    sort($eventList["EventDate"]);

                                    $now = strtotime(date('Y-m-d'));
                                    foreach ($eventList["EventDate"] as $ed) {
                                        if ($now <= strtotime($ed["date"])) {
                                            if ($n == 1) {
                                                echo date('l, F d, Y', strtotime($ed["date"]));
                                                if (!empty($ed["start_time"])) {
                                                    echo "  " . date('h:i A', strtotime($ed['start_time']));
                                                    echo " - " . date('h:i A', strtotime($ed['end_time']));
                                                }
                                                echo "<br>";
                                            }
                                            $n++;
                                        }
                                    }
                                    ?>
                        </td>
                          
                    </tr>
                    <?php } ?>
                  </table>
                </div>
            <div class="previewinner-sbmt-btn">
                        <?php echo $this->Html->link("Change", $urlTab1, array("class" => "violet_button")); ?>
                    </div>
            </div>
            
            <?php } ?>
            
            <div class="offer-ld previewcampaign-img">
                <h3>Preview Email</h3>
                <?php echo $campaign["Campaign"]["html"] ?>
                <div class="previewinner-sbmt-btn">
                    <?php echo $this->Html->link("Change", array("controller" => "Campaigns", "action" => "designTemplate", base64_encode($campaign["Campaign"]["id"])), array("class" => "violet_button")); ?>
                </div>
            </div>
<?php if($campaign["Campaign"]["send_mode"]==1) { ?>
            <div class="previewcampgn-inner">
                <h3>Scheduled to send : <?php echo date("l, F d, Y h:i A",strtotime($campaign["Campaign"]["date_to_send"])); ?></h3>


                <div class="previewinner-sbmt-btn">
                    <?php echo $this->Html->link("Change", array("controller" => "Campaigns", "action" => "campaignDetail", base64_encode($campaign["Campaign"]["id"])), array("class" => "violet_button")); ?>
                </div>
            </div>
<?php } ?>

            <div class="clear"></div>
            <?php echo $this->Form->create("Campaign", array("action" => "previewCampaign", "class" => "event-form", "id" => "payment_form"));
                echo $this->Form->input("Campaign.id", array("type" => "hidden","value" => $campaign_id)); ?>
            <div class="previewinner-sbmt-btn">
                 <?php
                    echo $this->Form->input("Confirm", array("type" => "submit", "label" => false, "class" => "violet_button"));
                    ?>
               
            </div>
            <?php echo $this->Form->end(); ?>
            <div class="breadcrumb">
                <!--                <ul>
                                    <li>Step 1: Design</li>
                                    <li>Step 2: Set Up</li>
                                    <li class="active">Step 3: Preview</li>
                                    <li>Step 4: Recipients</li>
                                </ul>-->
            </div>
        </div>
    </div>
</section>
<!--Bottom Details Section Ends-->
<script>
    $("div").attr("contenteditable","false");
    function remove_event(id) {
        jQuery.ajax({
            url: '/Campaigns/removeEvent/' + id,
            success: function(data) {
                if (data == 1) {
                    $("#addedEvent_" + id).remove();
                } else {
                    alert(data);
                }


            }
        });
    }
</script>
