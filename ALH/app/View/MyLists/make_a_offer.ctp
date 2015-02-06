<?php //pr($data);       ?>

    <div class="center-block makeanoffer-whole">
        <div class="mo-sec profile-whole">
            <h1><label>Make An Offer</label>
            	<span><?php echo $data["MyList"]["list_name"]; ?></span>
            </h1>
            <div class="clear"></div>
            <div class="mo-sec-inner">
            	<div class="bmd">
                	<div class="img-bmd">
                	<?php echo $this->html->image("brand/large/" . $data["User"]["Brand"]["logo"]); ?>
                    </div>
                    <div class="bmd-inner">
                    	<h3><?php echo $data["User"]["Brand"]["name"]; ?></h3>
                        <blockquote>
                            <b>Category : </b>
                            <?php
                               foreach ($data["ListCategory"] as $categories) {
                                   echo $categories["Category"]["name"] . ",";
                                   $catArray[] = $categories["Category"]["name"];
                               }
                               echo implode(", ",$catArray);
                           ?>
                        </blockquote>
                        
                        <blockquote>
                            <b>Vibe : </b>
                            <?php
                                foreach ($data["ListVibe"] as $vibes) {
                                   
                                    $vibeArray[] = $vibes["Vibe"]["name"];
                                }
                                echo implode(", ",$vibeArray);
                            ?>
                        </blockquote>
       
                        <p><?php echo $data["User"]["Brand"]["description"]; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="offer-ld">
                	<div class="offer-ld-lt">
                    	<h4>List Details</h4>
                        <div class="clear"></div>
                        <blockquote>
                        <b>Vibe : </b><?php
                            foreach ($data["ListVibe"] as $vibes) {
                              
                                $vibesArray[] = $vibes["Vibe"]["name"];
                            }
                            echo implode(", ",$vibesArray);
                            ?>
                        <br>
                        <b>Category : </b><?php
                        foreach ($data["ListCategory"] as $categories) {
                            
                            $catsArray[] = $categories["Category"]["name"];
                        }
                        echo implode(", ",$catsArray);
                        ?>
                   
                        </blockquote>
                        
                        
                        <!--h4>4,000 Active Subscribers</h4-->
                        <?php
                             
                                 $RateDetail =   $this->Common->listrate($data["OpenRate"]);
                               
                                    
                                    ?>
       
                        <div class="clear"></div>
                        <dl>
                        	<dt>Open Rate:</dt>
                            <dd><?php echo $RateDetail["OpenRate"] ;?></dd>
                            
                            <dt>Click-through rate:</dt>
                            <dd><?php echo $RateDetail["ClickRate"]; ?></dd>
                            
                            <dt>Region Breakdown:</dt>
                            
                            <dd>
                            <?php echo $RateDetail["RegionPer1"] ;?> Northeast US <br>
                            <?php echo $RateDetail["RegionPer2"] ;?> Midwest US <br>
                            <?php echo $RateDetail["RegionPer3"] ;?> South US <br>
                            <?php echo $RateDetail["RegionPer4"] ;?> West US <br>
                            <?php echo $RateDetail["RegionPer5"] ;?> Others
                            </dd>
                            <div class="clear"></div>
                        </dl>
                        
                        <p class="lasttxt"><strong><span><?php echo $data["MyList"]["dedicated_send_points"]; ?></span> points for dedicated Send</strong></p>
                        <p class="lasttxt"><strong><span><?php echo $data["MyList"]["multi_event_points"]; ?></span> points for inclusion in multi-event email</strong></p>
                        <div class="clear"></div>
                    </div>
                    <div class="offer-ld-rt">
                         <span class="loader" id="load" style="display: none;"><?php echo $this->html->image('/img/admin/loader.gif'); ?></span>
        <input type="hidden" value="0" class = "dedicated_email_to_send">
        <input type="hidden" value="0" class = "multi_event_to_send">
        <input type="hidden" value="0" class = "ticket_offered_for_trade">

        <?php
        $numeric = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
        $my_list_id = $data["MyList"]["id"];
        echo $this->Form->create("MyList", array("action" => "makeOffer"));
        echo $this->Form->hidden("MakeOffer.my_list_id", array("value" => $data["MyList"]["id"]));
        echo $this->Form->hidden("MakeOffer.adjusted_price", array("value" => 0, "id" => "adjusted_price"));
       echo "<ul><li>";
        echo $this->Form->input("MakeOffer.dedicated_email_to_send", array("onchange" => "adjust_price($my_list_id,'dedicated_email_to_send',this.value);", "type" => "select", "label" => "Dedicated Emails to Send : ", "div" => FALSE, "options" => $numeric, "id" => "dedicate"));
        ?>
        
        <span class="loader" id="load_dedicated_email_to_send" style="display: none;"><?php echo $this->html->image('/img/admin/loader.gif'); ?></span>
        </li><li>
        <?php
        echo "<br/>" . $this->Form->input("MakeOffer.multi_event_to_send", array("onchange" => "adjust_price($my_list_id,'multi_event_to_send',this.value);", "type" => "select", "label" => "Multi - Event Emails to Send : ", "div" => FALSE, "options" => $numeric, "id" => "multi"));
        ?>
        <span class="loader" id="load_multi_event_to_send" style="display: none;"><?php echo $this->html->image('/img/admin/loader.gif'); ?></span>
        </li><li><?php
        echo "<br/>" . $this->Form->input("MakeOffer.ticket_offered_for_trade", array("onchange" => "adjust_price($my_list_id,'ticket_offered_for_trade',this.value);", "type" => "select", "label" => "Tickets Offered for Trade : ", "div" => FALSE, "options" => $numeric, "id" => "ticket"));
        ?>
        <span class="loader" id="load_ticket_offered_for_trade" style="display: none;"><?php echo $this->html->image('/img/admin/loader.gif'); ?></span>
        </li><li><?php
        echo "<br/>" . $this->Form->input("MakeOffer.ticket_value", array("type" => "text", "label" => "Ticket Value : ", "div" => FALSE));
        echo "</li><li>";
        echo "<br/>" . "<label>Adjusted price:</label> <p><span id = 'price'>0</span><br><br></p>";
        echo "</li><li>";
        echo "</li><li>";
        echo "<br/>" . $this->Form->input("MakeOffer.note_to_list_owner", array("type" => "textarea", "label" => "Note to List Owner : ", "div" => FALSE));
       
        echo "<br/>" . $this->Form->submit("Make an offer",array("id"=>"make_an_offer","class"=>"submit-offer violet_button"));
        echo "</li></ul>";
        echo $this->Form->end();
        ?>
                     <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                
            <div class="clear"></div>
        </div>
    </div>







<script>
    function adjust_price(my_list_id, parameter, qty) {
        $("#load_" + parameter).show();
        jQuery.ajax({
            url: '/MyLists/checkPrice/' + my_list_id + '/' + parameter + '/' + qty,
            success: function(data) {
                if (parseInt(data) == 0) {
                    $("#price").html("<b style = 'color:red'>This list does't have price yet.</b>");
                    $("#make_an_offer").attr("disabled","disabled");
                    $("#load_" + parameter).hide();
                } else {
                    $("#load_" + parameter).hide();
                    $("." + parameter).val(parseInt(data));
                    var final_price = parseInt($(".dedicated_email_to_send").val()) + parseInt($(".multi_event_to_send").val()) + parseInt($(".ticket_offered_for_trade").val());
                    $("#price").html("<b>" + final_price + "</b>");
                    $("#adjusted_price").val(final_price);
                }

            }
        });
    }
</script>

