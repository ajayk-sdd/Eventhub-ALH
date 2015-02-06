<!--Content Wrapper Starts-->
<section id="cont_wrapper">
    <!--Content Starts-->
    <section class="content">
        <!--Main Heading Starts-->
        <h1 class="main_heading"> <?php echo "Campaign Details";  ?></h1>
        <!--Main Heading Ends-->
        <!--Conetnt Info Starts Here-->
        <section class="content_info"  style="float:left">
        <div class="em-sec">
            
            <div class="mo-sec-inner previewcampaign-whole">

                <div class="admin-camp-detail-top">
              
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
                    
                   
                </div>

            </div>
            <br><br>
            <?php if(isset($event) && !empty($event)) { ?>
            
            <div class="admin-camp-detail-top">

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
          
            </div>
            <br><br>
            <?php } ?>
            
            <div class="admin-camp-detail-top">
                <h3>Preview Email</h3>
                <?php echo $campaign["Campaign"]["html"] ?>
               
            </div>
<?php if($campaign["Campaign"]["send_mode"]==1) { 
$text = "Scheduled to send";
 }
else
{
$text = "Sending Now";
}
 ?><br><br>
            <div class="admin-camp-detail-top">
                <h3><?php echo $text.": ".date("l, F d, Y h:i A",strtotime($campaign["Campaign"]["date_to_send"])); ?></h3>


                
            </div>


            <div class="clear"></div>
           <input type="reset" id="CampaignReset" class="blu_btn_rt" onclick="javascript:history.back();" value="Go Back">
           
        </div>
    </div>
</section>
</section></section>
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

<!--Content Wrapper Ends-->
<?php echo $this->Html->script('/js/admin/Campaigns/admin_add'); ?>