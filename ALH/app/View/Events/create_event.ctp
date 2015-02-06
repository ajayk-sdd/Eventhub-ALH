<?php
$alh = 0;
$wrdpres = 0;
if (isset($this->data['Event']['option_to_show'])) {
    if ($this->data['Event']['option_to_show'] == 3) {
        $alh = 1;
        $wrdpres = 1;
    } else if ($this->data['Event']['option_to_show'] == 2) {
        $alh = 0;
        $wrdpres = 1;
    } else if ($this->data['Event']['option_to_show'] == 1) {
        $alh = 1;
        $wrdpres = 0;
    }
}
if (isset($this->data["Event"]["id"]) && !empty($this->data["Event"]["id"])) {
    $search_fields = "hide";
    $final_result = "show";
    //echo $this->data["Event"]["event_address"].$this->data["Event"]["cant_find_city"].$this->data["Event"]["cant_find_state"];
} else {
    $search_fields = "show";
    $final_result = "hide";
    //echo "bye";
}
//pr($this->data);die;
?>
<section class="inner-content">
    <div class="center-block">
        <div class="em-sec">
            <h1>Add an Event</h1>
            <div class="breadcrumb">
                <ul>
                    <li class="active">Step 1: Event Details</li>
                    <li>Step 2: Confirm Details</li>
                    <li>Step 3: Event Marketing</li>
                    <li>Step 4: Share Your Event</li>
                </ul>
            </div>
            <div class="clear"></div>
            <div class="em-sec-inner">
                <?php
                //echo $this->Session->flash();
                echo $this->Form->create("Event", array('class' => 'event-form', "action" => "createEvent", "id" => "addEvent", "name" => "addEvent", 'enctype' => 'multipart/form-data'));
                echo $this->Form->hidden("Event.id");
                if (isset($this->data["Event"]["user_id"]) && !empty($this->data["Event"]["user_id"])) {
                    $user_id = $this->data["Event"]["user_id"];
                } else {
                    $user_id = AuthComponent::user("id");
                }
                echo $this->Form->hidden("Test.user_id", array("id" => "user_id", "value" => $user_id));
                ?>
                <div class="emsi-part em-pa-lt">
                    <label><strong>Title</strong> <span>(40 characters or less)</span><span class="red-star">*</span></label>
                    <?php echo $this->Form->input("title", array("type" => "text", "label" => FALSE, "div" => false, "class" => "txtbx-bg validate[required] ", "id" => "title", 'placeholder' => 'The Title of your event', 'maxLength' => '40')); ?>
                    <br> <br>
                    <label><strong>Event Subtitle</strong> <span>(60 characters or less)</span></label>
                    <?php echo $this->Form->input("sub_title", array("type" => "text", "label" => FALSE, "div" => false, "class" => "txtbx-bg", "id" => "sub_title", 'placeholder' => 'A Subtitle for your event (optional)', 'maxLength' => '60')); ?>

<!--                    <label><strong>Is this Event Public or Private?</strong></label>-->
                    <!--                    <label><?php
                    //$options = array('1' => 'This is a Public Event <span>(Your event will be visble to everyone on AList Hub)</span>' . "", '2' => 'This is a Private Event <span>(Your Event will only be visible to people you invite)</span>');
                    //$attributes = array('legend' => false, 'label' => 'gender_male', 'id' => 'radio');
                    //echo $this->Form->radio('event_type', $options, $attributes);
                    ?></label>
                                        <div style="display:none" id="showCal" class="e-public">
                    <?php
                    //echo $this->Form->input("Event.option_to_show_2", array("checked" => "checked", "type" => "checkbox", "label" => "Show on wordpress calendar", "div" => false, "class" => "validate[required] ", "id" => "list_hub_cal1", "name" => "data[Event][option_to_show][]"));
                    ?>
                                        </div>-->
                    <br> <br>                    
                    <label><strong>Location</strong><span class="red-star">*</span></label>
                    <!-- new way of location -->
                    <div class="location" style="margin-bottom: 10px; overflow:hidden;">
                        <div id="googleMap" style="float: right; width: 48%; height: 310px;"> Here will be map</div>
                        <div class="fields" style="float: left; width: 50%;">

                            <div id = "search_fields" class = "<?php echo $search_fields; ?>">

                                <h4>Choose the location of your event</h4>
                                <br>
                                <h5>Search by venue name and location to see if the venue is already listed</h5>
                                <br>
                                Venue:<br> <?php echo $this->Form->input("venue", array("type" => "text", "div" => FALSE, "label" => FALSE, "class" => "validate[required]")); ?>
                                <br>
                                City & State, or ZIP: <br><?php echo $this->Form->input("where", array("type" => "text", "div" => FALSE, "label" => FALSE, "class" => "validate[required]")); ?>
                                <br>
                                Within(miles): <br><?php echo $this->Form->input("within", array("type" => "text", "div" => FALSE, "label" => FALSE, "class" => "validate[required]", "value" => 85)); ?>
                                <br>
                                <a class='add-more-tt' href="javascript:void(0);" onclick="javascript:findAddress();">Search</a>
                                &nbsp;&nbsp;
                                <a class='add-more-tt' href="javascript:void(0);" onclick="javascript:$('#cant_find_my_address').toggle();
                                        $('#search_result').toggle();
                                        $('#search_fields').hide();
                                        $('#final_result').show();">Cant find your address</a>
                                <span class="loader" id="search_load" style="display: none; float: left; margin-left: 6px;"><img src="/img/admin/loader.gif" alt=""></span>
                            </div>
                            <div id = "final_result" class = "<?php echo $final_result; ?>">
                                <span id = "formatted_address" style="color:#7900A0;">
                                    <?php
                                    if (isset($this->data["Event"]["event_address"]) && !empty($this->data["Event"]["event_address"])) {
                                        //echo "Current Address<br>";
                                        $address = explode(",", $this->data["Event"]["event_address"]);
                                        $add1 = $address['0'];
                                        $add2 = $address['1'];
                                        if (isset($this->data["Event"]["specify"]) && !empty($this->data["Event"]["specify"])) {
                                            $add = $this->data["Event"]["specify"];
                                        } else {
                                            $add = $add2;
                                        }
                                        echo $add . "<br>" . $this->data["Event"]["cant_find_city"] . ",&nbsp;" . $this->data["Event"]["cant_find_state"] . "<br>" . $this->data["Event"]["cant_find_zip_code"];
                                    }
                                    ?>
                                </span><br/>
                                <a class='add-more-tt' href="javascript:void(0);" onclick='javascript:$("#final_result").css("display", "none");
                                        $("#search_result").css("display", "block");
                                        $("#search_fields").css("display", "block");
                                        $("#cant_find_my_address").css("display", "none");
                                   '>Change Location</a>
                            </div>
                        </div>
                        <div style="clear: both;"></div>
                        <div id="search_result" style="display: none; max-height: 300px; overflow: auto;">

                        </div>
                    </div>

                    <div class="location" style="margin-bottom: 10px; display: none;" id = "cant_find_my_address">
                        <label><strong>Saved Addresses</strong>
                            <?php
                            echo $this->Form->input("Event.specify", array("type" => "select", "label" => FALSE, "div" => FALSE, "options" => $saved_address, "empty" => "Select Address", "class" => "sltbx-sm", "onchange" => "javascript:checklocation();", "id" => "specify"));
                            ?></label>
                        <!--                    <a href="javascript:void(0);" class="find-location" onclick="javascript:$('#cant_find_address').toggle(600);
                                                        togglespecifyclass()">Cant find your address</a>-->
                        <!--                    <span id='specifyerror' class='red-star'></span>-->

                        <span id = "address_name">
                            <?php
                            if (!empty($this->data["Event"]["specify"])) {
                                $my_address = $this->data["Event"]["specify"];
                            } else {
                                $my_address = "";
                            }
                            echo $this->Form->input("Event.address_name", array("type" => "text", "placeholder" => "Venue name:*", 'label' => FALSE, "div" => false, "class" => "txtbx-md validate[required] ", "value" => $my_address, "id" => "my_address"));
                            ?>
                        </span>


                        <div style="display:block;" id = "cant_find_address">
                            <?php
                            if (!empty($this->data["Event"]["event_address"])) {
                                $address = explode(",", $this->data["Event"]["event_address"]);
                                $add1 = $address['0'];
                                $add2 = $address['1'];
                            } else {
                                $add1 = "";
                                $add2 = "";
                            }
                            //echo $this->Form->input("Event.event_address1", array("type" => "text", "placeholder" => "Address line 1:*", 'label' => FALSE, "div" => false, "class" => "txtbx-md validate[required] ", "value" => $add1));

                            echo $this->Form->input("Event.event_address1", array("type" => "text", "placeholder" => "Address line 1:", 'label' => FALSE, "div" => false, "class" => "txtbx-md", "value" => $add1));

                            echo $this->Form->input("Event.event_address2", array("type" => "text", "placeholder" => "Address line 2:", 'label' => FALSE, "div" => false, "class" => "txtbx-md", "value" => $add2));

                            echo "<br>" . $this->Form->input('Event.cant_find_state', array("class" => "sltbx-sm", "placeholder" => "State", "label" => FALSE, "div" => false, 'type' => 'select', 'empty' => 'Select State', 'options' => $zip)) . "<br>";

                            echo $this->Form->input("Event.cant_find_city", array("type" => "text", "placeholder" => "City*", 'label' => FALSE, "div" => false, "class" => "txtbx-md validate[required]", "id" => "cant_find_city"));

                            echo $this->Form->input("Event.cant_find_zip_code", array("type" => "text", "placeholder" => "Zipcode*", "label" => FALSE, "div" => false, "class" => "txtbx-md validate[required]", "id" => "cant_find_zip_code"));

                            echo "<br>" . $this->Form->input("Event.country", array("type" => "select", "label" => FALSE, "div" => FALSE, "options" => array("United States" => "United States"), "class" => "sltbx-sm"));
                            echo $this->Form->input("Event.lat", array("type" => "hidden", "div" => FALSE, 'label' => FALSE, "id" => "cant_find_lat"));
                            echo $this->Form->input("Event.lng", array("type" => "hidden", "div" => FALSE, 'label' => FALSE, "id" => "cant_find_lng"));
                            ?>

                        </div>
                    </div>

                    <!-------------- Start Date Time Code ----------------->


                    <script type="text/javascript" src="http://yui.yahooapis.com/2.9.0/build/yahoo/yahoo.js"></script>
                    <script type="text/javascript" src="http://yui.yahooapis.com/2.9.0/build/event/event.js" ></script>
                    <script type="text/javascript" src="http://yui.yahooapis.com/2.9.0/build/dom/dom.js" ></script>

                    <script type="text/javascript" src="http://yui.yahooapis.com/2.9.0/build/calendar/calendar.js"></script>
                    <link type="text/css" rel="stylesheet" href="http://yui.yahooapis.com/2.9.0/build/calendar/assets/skins/sam/calendar.css">
                    <link rel="stylesheet" type="text/css" href="/yui/build/fonts/fonts-min.css" />
                    <link rel="stylesheet" type="text/css" href="/yui/build/calendar/assets/skins/sam/calendar.css" />

                     <!----------------- Time Picker code start -------------------->
                    
                    <?php
                    echo $this->Html->css('event-time');
                    echo $this->Html->css('event-time-picker');
                    ?>
                
                <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js">
                    </script> 
                     
                    
                    <script type="text/javascript" src="http://tarruda.github.com/bootstrap-datetimepicker/assets/js/bootstrap-datetimepicker.min.js">
                    </script>
                     
                    <script type="text/javascript">
                        $time_picker = jQuery.noConflict();
                     
                        function timePicker(a)
                        {
                            $time_picker('#'+a).datetimepicker({
                          pickDate: false,
                          format: 'HH:mm PP',
                          pick12HourFormat: true,   // enables the 12-hour format time picker
                          pickSeconds: false, 
                        });
                        }
                        $time_picker(document).ready(function() {
                        var arrayOfIds = $time_picker.map($time_picker(".add-on"), function(n, i){
                            return n.id;
                          });
                        $time_picker.each(arrayOfIds, function( index, value ) {
                        timePicker(value);
                        });
                        //alert(arrayOfIds);
                        });
                    </script>

                    <!----------------- Time Picker code end -------------------->
    
                    
                    <?php
                    if (isset($this->data["Event"]['id'])) {
                        $Id = $this->data["Event"]['id'];
                    } else {
                        $Id = '';
                    }
                    if (isset($this->data["Event"]['recurring_or_not'])) {
                        $recurring_or_not = $this->data["Event"]['recurring_or_not'];
                    } else {
                        $recurring_or_not = '0';
                    }
                    ?>
                    <br>
                    <input type="hidden" name="data[Event][recurring_or_not]" id="recurring_or_not" value="<?php echo $recurring_or_not; ?>">
                    <label><strong>Date/Time</strong><span class="red-star">*</span></label>
                    <div align="center" id="dateError" style="display: none;">You Need to add atleast one date in List.</div>

                    <div id="tabs">
                        <div class="step1">
                            <h3>
                                <img  src="/img/bkg_step1.png" alt="step1"> To add a start time and date to your event, begin by adding the start time.
                            </h3>
                             <div class="entry createEvent-div">
                                <br><div class="createEvent-startDate">Start Time:</div>
                                <div id="start_time_picker" class="input-append date createEvent-startDate-tag">
                                    <span class="add-on"   id="start_time_picker">
                                          <input type="text" class="starttimepick"  id="start_timeN" name="start_timeN"></input>
                                  
                                      <i data-time-icon="icon-time new-icn" data-date-icon="icon-calendar"></i>
                                    </span>
                                </div>
                                <div class="createEvent-endDate">End Time:</div>
                                <div id="end_time_picker" class="input-append date">
                                    
                                    <span class="add-on"  id="end_time_picker">
                                      <input type="text" class="starttimepick" id="end_timeN" name="end_timeN"></input>
                                      <i data-time-icon="icon-time new-icn" data-date-icon="icon-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="step2">
                            <h3>
                                <img  src="/img/bkg_step2.png" alt="step2"> The next step is to select dates for your event.
                            </h3>
                        </div>

                        <ul>
                            <li><?php echo $this->Html->link('Calendar View', array('action' => 'cal/' . $Id), array("onclick" => "javascript:removeStart(0);")) ?></li>
                            <li><?php echo $this->Html->link('Advanced Recurring', array('action' => 'caln/' . $Id), array("onclick" => "javascript:removeStarts(1);")) ?></li>


                        </ul>
                    </div>

                    <script>

                                    function removeStart(recurs) {
                                        var myNode = document.getElementById("evtentriess");
                                        while (myNode.firstChild) {
                                            myNode.removeChild(myNode.firstChild);
                                        }
                                        var recur = document.getElementById("recurring_or_not");
                                        recur.value = recurs;
                                    }

                                    function removeStarts(recurs) {
                                        var myNode = document.getElementById("evtentries");
                                        while (myNode.firstChild) {
                                            myNode.removeChild(myNode.firstChild);
                                        }
                                        var recur = document.getElementById("recurring_or_not");
                                        recur.value = recurs;
                                    }

                                    function dateValidate() {
                                       
                                        if (document.getElementById("title").value!='' && document.getElementById("EventVenue").value!='' && document.getElementById("EventWhere").value!='' && document.getElementById("EventWithin").value!='') {
                                            if (document.getElementById("data[EventDate][start_date][]")) {
                                            document.getElementById("dateError").style.display = 'none';
                                            return true;
                                        } else {
                                            document.getElementById("dateError").style.display = 'block';
                                            $("html, body").animate({scrollTop: $("#dateError").offset().top}, 1000);
                                            return false;
                                        }
                                        }
                                         return true;
                                        
                                    }


                    </script>



                    <!-------------- End Date Time Code ----------------->


                    <?php if (empty($this->data["TicketPrice"])) { ?>
                        <table class="priceClass">	
                            <tr><td>
                                    <label><br><strong>Ticket Price</strong><span class="red-star">*</span></label>
                                </td></tr>

                            <tr id="ticket-price" class="tckt-price"> 
                                <td> $
                                    <?php echo $this->Form->input("TicketPrice.ticket_price.", array("placeholder" => "Ticket Price", "type" => "text", "label" => FALSE, "div" => false, "class" => "txtbx-bg validate[required,custom[integer]]", "id" => "", "style" => "width:190px;")); ?>
                                    &nbsp;
                                </td>
                                <td> 

                                    <?php echo $this->Form->input("TicketPrice.ticket_label.", array("placeholder" => "Ticket Description", "type" => "text", "label" => FALSE, "div" => false, "class" => "txtbx-bg validate[required]", "id" => "", "style" => "width:190px;")); ?>

                                    <a href="javascript:void(0);" class="img-prc-rmv" style="display: none;"><img src='/app/webroot/img/admin/delete.png' alt='delete' title='Remove this set' class="img"/></a>
                                </td>
                            </tr>

                        </table>
                        <div style="display:block;padding-left: 12px;" id="addPrice">

                            <?php echo $this->Html->link("+Add more", "javascript:void(0);", array('class' => 'add-more-tt', 'escape' => false, 'id' => 'add_more_price', 'id' => 'add_more_price', 'onclick' => 'checkprice()')); ?>
                        </div>
                    <?php } else {
                        ?>
                        <table class="priceClass">	
                            <tr><td>
                                    <label><strong>Ticket Price</strong></label>
                                </td></tr>

                            <?php
                            $tprice = $this->data["TicketPrice"];
                            //$ticket_price = explode("|", $event_price);
                            $counttprice = count($tprice);
                            if($counttprice==1)
                            {
                                $disNone = "style='display:none'";
                            }
                            else
                            {
                                $disNone = "";
                            }
                            foreach ($tprice as $tp) {
                                ?>
                                <tr id="ticket-price" class="tckt-price"> 
                                    <td> $
                                        <?php echo $this->Form->input("TicketPrice.ticket_price.", array("placeholder" => "Ticket price", "type" => "text", "label" => FALSE, "div" => false, "class" => "txtbx-bg validate[required]", "id" => "", "value" => $tp['ticket_price'], "style" => "width:190px;")); ?>

                                    &nbsp;&nbsp;
                                    </td>
                                    <td> 

                                        <?php echo $this->Form->input("TicketPrice.ticket_label.", array("placeholder" => "Ticket Description", "type" => "text", "label" => FALSE, "div" => false, "class" => "txtbx-bg validate[required]", "id" => "", "value" => $tp['ticket_label'], "style" => "width:190px;")); ?>

                                        <a href="javascript:void(0);" class="img-prc-rmv" <?php echo $disNone; ?>><img src='/app/webroot/img/admin/delete.png' alt='delete' title='Remove this set' class="img"/></a>
                                    </td>

                                </tr>
                           
                        <?php } ?>
                        </table>
                        <div style="display:block;padding-left: 12px;" id="addPrice">

                            <?php echo $this->Html->link("+Add more", "javascript:void(0);", array('class' => 'add-more-tt', 'escape' => false, 'id' => 'add_more_price', 'id' => 'add_more_price', 'onclick' => 'checkprice()')); ?>
                        </div> 
                    <?php }
                    ?>
                    <label><strong>Website</strong></label>
                    <?php echo $this->Form->input("Event.website_url", array("placeholder" => "The URL for your event's web page (optional)", "type" => "text", "label" => FALSE, "div" => false, "class" => "txtbx-bg validate[custom[url]]", "id" => "website_url")); ?>
                    <br> <br>
                    <label><strong>Ticket Vendor URL</strong></label>
                    <?php echo $this->Form->input("Event.ticket_vendor_url", array("placeholder" => "The URL for your event's ticket vendor (optional)", "type" => "text", "label" => FALSE, "div" => false, "class" => "txtbx-bg validate[custom[url]]", "id" => "ticket_vendor_url")); ?>
                    <br> <br>

                    <label><strong>Facebook URL</strong></label>
                    <?php echo $this->Form->input("Event.facebook_url", array("placeholder" => "The URL for your event facebook (optional)", "type" => "text", "label" => FALSE, "div" => false, "class" => "txtbx-bg validate[custom[url]]", "id" => "facebook_url")); ?>
                    
                    <br> <br>
                    <label><strong>Video URL</strong></label>
                    <?php echo $this->Form->input("Event.video", array("placeholder" => "The URL for your event video (optional)", "type" => "text", "label" => FALSE, "div" => false, "class" => "txtbx-bg validate[custom[url]]", "id" => "video_url")); ?>

                    <div class="img-upload-box_new">
                        <br>
                        <label><strong>Upload the primary image for your event:</strong><span class="red-star">*</span></label>
                        <?php
                        if (!empty($this->data["Event"]["image_name"])) {

                            echo $this->html->image("EventImages/small/" . $this->data["Event"]["image_name"]);
                        }
                        if (isset($this->data["Event"]["id"])) {
                            echo $this->Form->input("Event.image_name", array("type" => "file", "label" => FALSE, "div" => false, "class" => "validate[checkFileType[jpg|jpeg|gif|JPG|png|PNG],checkFileSize[2000]]", "id" => "image_name"));
                        } else {
                            echo $this->Form->input("Event.image_name", array("type" => "file", "label" => FALSE, "div" => false, "class" => "validate[required,checkFileType[jpg|jpeg|gif|JPG|png|PNG],checkFileSize[2000]]", "id" => "image_name"));
                        }
                        ?>
                        <label>Image will be displayed on all public calendar pages, and is recommended to be "400 x 400"</label>
                    </div>
                    <div class="img-upload-box_new">
                        <br>
                        <label><strong>Upload the flyer for your event:</strong><span class="red-star">*</span></label>
                        <?php
                        if (!empty($this->data["Event"]["flyer_image"])) {

                            echo $this->html->image("flyerImage/small/" . $this->data["Event"]["flyer_image"]);
                        }

                        if (isset($this->data["Event"]["id"])) {
                            echo $this->Form->input("flyer_image", array("type" => "file", "label" => FALSE, "div" => false, "class" => "validate[checkFileType[jpg|jpeg|gif|JPG|png|PNG],checkFileSize[2000]]", "id" => "flyer_image"));
                        } else {
                            echo $this->Form->input("flyer_image", array("type" => "file", "label" => FALSE, "div" => false, "class" => "validate[required,checkFileType[jpg|jpeg|gif|JPG|png|PNG],checkFileSize[2000]]", "id" => "flyer_image"));
                        }
                        ?>
                    </div>
                    <div class="clear"></div>
                    <div class="image_event">
                        <?php
                        if (!empty($this->data["EventImages"])) {

                            foreach ($this->data["EventImages"] as $ei) {
                                echo $this->html->image("EventImages/small/" . $ei["image_name"]);
                                ?>
                                <div class="cntTag"><?php echo $this->Html->link("Delete", array("controller" => "Events", "action" => "delEventImg", base64_encode($ei['id']), $ei['image_name']), array('escape' => false, 'title' => 'Delete', 'class' => "edidlt", "id" => "imgDel", 'onclick' => "return confirm('Are you sure you want to delete this Image ?');")); ?></div>
                                <?php
                            }
                        }
                        ?>

                    </div>
                    <div class="img-upload-box_new">
                        <br>
                        <label><strong>Upload more images for your event:</strong></label>
                        <?php echo $this->Form->input("EventImage.image_name.", array("type" => "file", "label" => FALSE, "div" => false, "class" => "validate[checkFileType[jpg|jpeg|gif|JPG|png|PNG],checkFileSize[2000]]", "multiple" => true)); ?>
                    </div>
                    *Please press CTRL to upload more than 1 secondary image at a time.


                    <br><br>
                    <label>
                        <?php //echo $this->Form->input("Event.allow_users_to_edit", array("type" => "checkbox", "label" => false, "div" => false, "class" => "", "id" => "allow_users_to_edit"));    ?>
                        <strong>Allow other users to edit this event</strong>

                        <?php
                        $str = "";
                        if (!empty($this->data['EventEditedUser'])) {
                            foreach ($this->data['EventEditedUser'] as $user):
                                $str .= $user['user_email'] . ',';
                            endforeach;
                            $str = rtrim($str, ',');
                        }
                        ?>



                        <div id="allow_users" style="display:block;" class="allow-user">

                            <p>Enter other user's email addresses, separated by a comma.  If the user does not have an A List Hub Account, they will be asked to create one before being able to edit this event:</p>
                            <?php echo $this->Form->input("EventEditedUser.user_email", array("type" => "text", "label" => FALSE, "div" => false, "class" => "txtbx-bg", "id" => "other_email", "value" => $str)); ?>
                        </div>
                    </label> 



                </div>
                <div class="emsi-part em-pa-rt">
                    <label><strong>Main Contact Info for your Event</strong><br><span>(This won't be public, it's just for us to get in touch if we need to)</span></label>
                    <label><strong>Name</strong><span class="red-star">*</span></label>
                    <?php
                    if (!empty($this->data["Event"]["main_info_name"])) {
                        $main_info_name = $this->data["Event"]["main_info_name"];
                    } else {
                        $main_info_name = AuthComponent::user("first_name") . " " . AuthComponent::user("last_name");
                    }
                    echo $this->Form->input("main_info_name", array("placeholder" => "The name of the main contact for your event", "type" => "text", "label" => FALSE, "div" => false, "class" => "txtbx-bg validate[required] ", "id" => "uname", "maxlength" => "20", "value" => $main_info_name));
                    ?>
                    <br> <br>
                    <label><strong>Email</strong><span class="red-star">*</span></label>
                    <?php
                    if (!empty($this->data["Event"]["main_info_email"])) {
                        $main_info_email = $this->data["Event"]["main_info_email"];
                    } else {
                        $main_info_email = AuthComponent::user("email");
                    }
                    echo $this->Form->input("main_info_email", array("placeholder" => "The email of the main contact for your event", "type" => "text", "label" => FALSE, "div" => false, "class" => "txtbx-bg validate[required,custom[email]] ", "maxlength" => "60", "id" => "main_info_email", "value" => $main_info_email));
                    ?>
                    <br> <br>
                    <label><strong>Phone Number</strong><span class="red-star">*</span></label>
                    <?php
                    if (!empty($this->data["Event"]["main_info_phone_no"])) {
                        $phone_no = $this->data["Event"]["main_info_phone_no"];
                    } else {
                        $phone_no = $user_detail['User']['phone_no'];
                    }
                    echo $this->Form->input("main_info_phone_no", array("type" => "text", "label" => FALSE, "placeholder" => "The phone number of the main contact for your event", "div" => false, "class" => "txtbx-bg validate[required,custom[phone]]", "id" => "main_info_phone_no", "value" => $phone_no));
                    ?>
                    <br> <br>

                    <label><strong>Enter a Short Description for your Event</strong><span>&nbsp;(250 characters or less)</span></label>
                    <span class="error_short_description" style="color:red;display:none;">Short Description Content Limit Reached.</span>
                    <?php echo $this->Form->input("short_description", array("type" => "textarea", "label" => FALSE, "div" => false, "class" => "txtarea-sh", "id" => "short_description", 'maxlength' => '250')); ?>

                    <span class="char-count" id = "showdata"></span>
                    <label><strong>Enter a Long Description for your Event</strong><span>&nbsp;(3600 characters or less)</span></label>
                    <span class="error_description" style="color:red;display:none;">Long Description Content Limit Reached.</span>
                    <?php echo $this->Form->input("description", array("type" => "textarea", "label" => FALSE, "div" => false, "class" => "txtarea-lo", "id" => "description", 'maxlength' => '3600')); ?>

                    <br>
                    <?php
                    $j = 0;
                    if (!empty($this->data["EventCategory"])) {
                        foreach ($this->data["EventCategory"] as $ec) {
                            $eve_cate[] = $ec["category_id"];
                        }
                    } else {
                        $eve_cate = array();
                    }
                    echo $this->Html->script('/js/Front/Events/eventErrors');
                    ?>
                    <label><strong>Categories (choose as many as you think apply)</strong><span class="red-star">*</span></label>
                    <div class="cat-error" id="cat-error" style="display: none;"></div>
                    <div class="cat-box">
                        <?php
//                        foreach ($categories as $cat):
//                            if (in_array($cat['Category']['id'], $eve_cate))
//                                echo "<label>" . $this->Form->input("EventCategory.category_id[]", array("checked" => true, "name" => "data[EventCategory][category_id][]", "type" => "checkbox", "label" => FALSE, "div" => false, "class" => "validate[required]", "value" => $cat['Category']['id'])) . $cat['Category']['name'] . "</label>";
//                            else
//                                echo "<label>" . $this->Form->input("EventCategory.category_id[]", array("name" => "data[EventCategory][category_id][]", "type" => "checkbox", "label" => FALSE, "div" => false, "class" => "validate[required]", "value" => $cat['Category']['id'])) . $cat['Category']['name'] . "</label>";
//                            $j++;
//                        endforeach;
                        ?>

                        <?php
                        echo $this->Form->input("EventCategory.0", array("type" => "select", "label" => FALSE, "div" => FALSE, "options" => $categories, "empty" => "Select Category", "onchange" => "checkCategories(this.value,this.id)", "class" => "sltbx-sm validate[required]"));
                        echo "<br>" . $this->Form->input("EventCategory.1", array("type" => "select", "label" => FALSE, "div" => FALSE, "options" => $categories, "empty" => "Select Category", "onchange" => "checkCategories(this.value,this.id)", "class" => "sltbx-sm"));
                        echo "<br>" . $this->Form->input("EventCategory.2", array("type" => "select", "label" => FALSE, "div" => FALSE, "options" => $categories, "empty" => "Select Category", "onchange" => "checkCategories(this.value,this.id)", "class" => "sltbx-sm"));
                        ?>
                    </div>
                    <?php
                    $i = 0;
                    if (!empty($this->data["EventVibe"])) {
                        foreach ($this->data["EventVibe"] as $ev) {
                            $eve_vib[] = $ev["vibe_id"];
                        }
                    } else {
                        $eve_vib = array();
                    }
                    ?>
                    <label><strong>Vibes (choose as many as you think apply)</strong><span class="red-star">*</span></label>
                    <div class="cat-error" id="vib-error" style="display: none;"></div>
                    <div class="cat-box">
                        <?php
//                        foreach ($vibes as $vibe):
//
//                            if (in_array($vibe['Vibe']['id'], $eve_vib))
//                                echo "<label>" . $this->Form->input("EventVibe.vibe_id", array("checked" => true, "name" => "data[EventVibe][vibe_id][]", "type" => "checkbox", "label" => FALSE, "div" => false, "class" => "validate[required]", "value" => $vibe['Vibe']['id'])) . $vibe['Vibe']['name'] . "</label>";
//                            else
//                                echo "<label>" . $this->Form->input("EventVibe.vibe_id", array("name" => "data[EventVibe][vibe_id][]", "type" => "checkbox", "label" => FALSE, "div" => false, "class" => "validate[required]", "value" => $vibe['Vibe']['id'])) . $vibe['Vibe']['name'] . "</label>";
//                            $i++;
//
//                        endforeach;
                        ?>

                        <?php
                        echo $this->Form->input("EventVibe.0", array("type" => "select", "label" => FALSE, "div" => FALSE, "options" => $vibes, "empty" => "Select Vibe", "onchange" => "checkVibes(this.value,this.id)", "class" => "sltbx-sm validate[required]"));
                        echo "<br>" . $this->Form->input("EventVibe.1", array("type" => "select", "label" => FALSE, "div" => FALSE, "options" => $vibes, "empty" => "Select Vibe", "onchange" => "checkVibes(this.value,this.id)", "class" => "sltbx-sm"));
                        echo "<br>" . $this->Form->input("EventVibe.2", array("type" => "select", "label" => FALSE, "div" => FALSE, "options" => $vibes, "empty" => "Select Vibe", "onchange" => "checkVibes(this.value,this.id)", "class" => "sltbx-sm"));
                        ?>
                    </div>
                    <div class = "location">
                        <label><strong>No Of Ticket</strong><span> (Enter no of ticket you want for giveaway)</span></label>
                        <?php
                        echo $this->Form->input("Giveaway.id", array("type" => "hidden"));
                        echo $this->Form->input("Giveaway.no_of_ticket", array("type" => "text", "label" => FALSE, "div" => false, "class" => "txtbx-bg validate[custom[integer]]", "placeholder" => "Enter no of ticket"));
                        ?>
                    </div>
                </div>

                <div class="clear"></div>
                <br>
                <?php
                echo $this->Form->input("Preview Event", array("type" => "submit", "label" => false, "name" => "review", "id" => "sub", "onclick" => "return dateValidate();"));
//echo $this->Form->input("Save & Continue", array("type" => "submit", "label" => false, "name" => "save"));
                echo $this->Form->end();
                ?>                   
            </div>
            <div class="clear"></div>
            <div class="breadcrumb">
                <ul>
                    <li class="active">Step 1: Event Details</li>
                    <li>Step 2: Confirm Details</li>
                    <li>Step 3: Event Marketing</li>
                    <li>Step 4: Share Your Event</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!--Bottom Details Section Ends-->   
<?php
if (isset($this->data["Event"]["lat"]) && !empty($this->data["Event"]["lat"])) {
    $latitude = $this->data["Event"]["lat"];
    $longitude = $this->data["Event"]["lng"];
} else if(isset ($zipcode) && !empty ($zipcode)){
    $latitude = $zipcode["Zip"]["lat"];
    $longitude = $zipcode["Zip"]["lng"];
}/* else {
    $latitude = "39.8282";
    $longitude = "98.5795";
}*/
?>
<script>
    getmapdata(<?php echo $latitude . "," . $longitude; ?>);
    function findAddress() {
        $("#search_load").show();
        var venue = $("#EventVenue").val();
        var where = $("#EventWhere").val();
        var within = $("#EventWithin").val();
        within = within * 1609.34;
        if (venue != "" && where != "" && within != "") {
            jQuery.ajax({
                url: '/Events/findVenue/' + venue + '/' + where + '/' + within,
                success: function(data) {
                    if ($.trim(data) == "ZERO_RESULTS") {
                        alert("No result found for your search, please try different keyword");
                        $("#EventVenue").val("");
                        $("#EventWhere").val("");
                        $("#EventWithin").val("");
                        $("#search_load").hide();
                        $("#search_result").css("display", "none");
                    } else {
                        $("#search_result").html(data);
                        $("#search_result").css("display", "block");
                        $("#search_load").hide();
                    }

                }
            });
        } else {
            alert("All fields are required");
            $("#search_load").hide();
        }
    }
    function getDetail(name, full_address, city, state, country, lat, lng, zipcode) {
        $("#EventEventAddress1").val(full_address);
        $("#EventEventAddress2").val("");
        state = $.trim(state);
        $("#EventCantFindState").val(state);
        $("#cant_find_city").val(city);
        if (zipcode.trim() == "") {
            var zip = getZipFromLatLng(lat, lng);
        } else {
            zip = zipcode;
        }
        $("#cant_find_zip_code").val(zip);
        country = $.trim(country);
        $("#EventCountry").val("United States");
        $("#my_address").val(name);
        $("#cant_find_lat").val(lat);
        $("#cant_find_lng").val(lng);
        $("#specify").val("");
        getmapdata(lat, lng);
        $("#search_result").css("display", "none");
        $("#search_fields").css("display", "none");
        $("#final_result").css("display", "block");
        if (zip != 0) {
            var zip_code = zip;
        }
        else
        {
            var zip_code = '';
        }
        if (full_address.trim() != "") {
            var ful_add = name + "<br>" + full_address + "<br>" + city + ", " + state + "<br>" + zip_code;
        } else {
            var ful_add = name + "<br>" + city + ", " + state + "<br>" + zip_code;
        }
        $("#formatted_address").html(ful_add);
    }
    function getZipFromLatLng(lat, lng) {
        var zip;
        $.ajax({url: 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' + lat + ',' + lng + '&sensor=true',
            async: false,
            success: function(data) {
                //console.log(data);
                if (data.status.trim() != "ZERO_RESULTS") {
                    zip = data.results[0].address_components.pop().long_name;
                } else {
                    zip = 0;
                }
            }
        });
        //alert(zip);
        return zip;
    }
    function changeLocation() {
        $("#final_result").css("display", "none");
        $("#search_result").css("display", "block");
        $("#search_fields").css("display", "block");
    }
</script>
<?php
//echo $this->Html->css('jquery-ui');
echo $this->Html->script('/js/Front/Events/createEvent');
echo $this->Html->css('jquery-ui');
echo $this->Html->script('/js/jquery-ui-1.10.4.min');

if (isset($this->data['Event']['recurring_or_not']) && $this->data['Event']['recurring_or_not'] == 1) {
    $selTab = "-1";
} else {
    $selTab = "0";
}
?>

<script type="text/javascript">
    $(document).ready(function() {
        $("input").on("click", function() {
            $(window).bind('beforeunload', function() {
                return 'Are you sure you want to leave? the changes you have made will not be saved';
            });
        });
        // for alert before leaving
        $("textarea").on("click", function() {
            $(window).bind('beforeunload', function() {
                return 'Are you sure you want to leave? the changes you have made will not be saved';
            });
        });
        // for unbind while saving
        $("input[type=submit]").on("click", function() {
            $(window).unbind('beforeunload');
        });
        $("input[type=file]").on("click", function() {
            $(window).unbind('beforeunload');
        });
        $(function() {
            $("#tabs").tabs({active: "<?php echo $selTab; ?>"});
        });
        $('#main_info_phone_no').mask('(999) 999-9999');
    });
    $(".img").click(function() {
        $(this).parent('td').parent('tr').remove();
    });
    function togglespecifyclass() {

        if ($('#specify').hasClass("validate[required]"))
        {
            $('#specify').removeClass('validate[required]');
        } else {
            $('#specify').addClass('validate[required]');
        }
    }
    $("#EventCantFindState").change(function() {
        $('.ajaxLoader').show();
        var stateab = $('#EventCantFindState').val();
        var availableTags = "";
        jQuery.ajax({
            url: '/Events/findCity/' + stateab,
            async: false,
            success: function(data) {
                availableTags = data;
                $('.ajaxLoader').hide();
            }
        });
        $("#cant_find_city").autocomplete({
            source: JSON.parse(availableTags)
        });
    });
    function myCustomOnChangeHandler() {
        //alert(tinyMCE.activeEditor.id);
        $('span.mceEditor').after('<div id="' + tinyMCE.activeEditor.id + '_wordcount" class="wordcount">0  words, 0 characters</div>');
        return true;
    }

    tinymce.init({
        selector: "textarea",
        theme: "modern",
        theme_advanced_toolbarMenu_containers: "",
        width: 600,
        height: 200,
        setup: function(ed) {
            var maxlength = parseInt($('#' + (ed.id)).attr("maxlength"));
            var count = 0;
            ed.on('keydown', function(e) {

                count = tinyMCE.get(ed.id).getContent().replace(/<[^>]+>/g, '').length;
                if (count >= maxlength)
                {
                    var key = e.which;  // backspace = 8, delete = 46, arrows = 37,38,39,40

                    if ((key >= 37 && key <= 40) || key == 8 || key == 46)
                    {
                        $('.error_' + (ed.id)).css("display", "none");
                    }
                    else {
                        $('.error_' + (ed.id)).css("display", "block");
                        return false;
                    }
                }
            });
            ed.on('change', function(e) {

                count = tinyMCE.get(ed.id).getContent().replace(/<[^>]+>/g, '').length;
                if (count >= maxlength)
                {
                    var textval = tinyMCE.get(ed.id).getContent().replace(/<[^>]+>/g, '');
                    tinyMCE.get(ed.id).setContent(textval.substring(0, maxlength));
                    $('.error_' + (ed.id)).css("display", "block");
                    $('html, body').animate({
                    scrollTop: '600px'
                },
                0);
                }
            });
        },
        //setup : function(ed) {
        //    ed.on("change", myCustomOnChangeHandler);
        //},    
        plugins: [
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime"

        ],
        //theme_advanced_buttons3_add : "spellchecker",
        //   spellchecker_languages : "+English=en,

        //content_css: "/css/content.css",
        toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
        style_formats: [
            {title: 'Bold text', inline: 'b'},
            {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
            {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
            {title: 'Example 1', inline: 'span', classes: 'example1'},
            {title: 'Example 2', inline: 'span', classes: 'example2'},
            {title: 'Table styles'},
            {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
        ],
        templates: [
            {
                title: "Editor Details",
                src: "editor_details.htm",
                content: "Adds Editor Name and Staff ID"
            },
            {
                title: "Timestamp",
                src: "flash.ctp ",
                content: "Adds an editing timestamp."
            }
        ]
    });
    tinymce.PluginManager.add('wordcount', function(editor) {
        var self = this, countre, cleanre;
        countre = editor.getParam('wordcount_countregex', /[\w\u2019\x27\-]+/g); // u2019 == &rsquo;
        cleanre = editor.getParam('wordcount_cleanregex', /[0-9.(),;:!?%#$?\x27\x22_+=\\\/\-]*/g);
        function update() {
            //editor.theme.panel.find('#wordcount').text(['Words: {0}, Character Count: {1}', self.getCount(), self.getCountCharacters()]);
            editor.theme.panel.find('#wordcount').text(['Character Count: {1}', self.getCount(), self.getCountCharacters()]);
        }

        editor.on('init', function() {
            var statusbar = editor.theme.panel && editor.theme.panel.find('#statusbar')[0];
            if (statusbar) {
                window.setTimeout(function() {
                    statusbar.insert({
                        type: 'label',
                        name: 'wordcount',
                        //text: ['Words: {0}, Character Count: {1}', self.getCount(), self.getCountCharacters()],
                        text: ['Character Count: {1}', self.getCount(), self.getCountCharacters()],
                        classes: 'wordcount'
                    }, 0);
                    editor.on('setcontent beforeaddundo keyup', update);
                }, 0);
            }
        });
        self.getCount = function() {
            var tx = editor.getContent({format: 'raw'});
            var tc = 0;
            if (tx) {
                tx = tx.replace(/\.\.\./g, ' '); // convert ellipses to spaces
                tx = tx.replace(/<.[^<>]*?>/g, ' ').replace(/&nbsp;|&#160;/gi, ' '); // remove html tags and space chars

                // deal with html entities
                tx = tx.replace(/(\w+)(&.+?;)+(\w+)/, "$1$3").replace(/&.+?;/g, ' ');
                tx = tx.replace(cleanre, ''); // remove numbers and punctuation

                var wordArray = tx.match(countre);
                if (wordArray) {
                    tc = wordArray.length;
                }
            }
            return tc;
        };
        self.getCountCharacters = function() {
            // First replace: remove HTML tags, ie. <div>, </span>
            // Second replace: swap html entities like &nbsp; into one character
            var strip = editor.getContent({format: 'raw'}).replace(/<.[^<>]*?>/g, "").replace(/&[^;]+;/g, "?");
            return strip.length;
        };
    });
//            $.get("http://freegeoip.net/json/", function(data){
//            //console.log(data);
//            $("#cant_find_zip_code").val(data.zipcode);
//            });

    $(document).ready(function() {
        $('#sub').click(function() {



            var l = tinyMCE.get('short_description').getContent().replace(/<[^>]+>/g, '').length;
            var j = tinyMCE.get('description').getContent().replace(/<[^>]+>/g, '').length;
            if (l > 250) {
                $('.error_short_description').css("display", "block");
                $('.error_description').css("display", "none");
                $('html, body').animate({
                    scrollTop: '300px'
                },
                1500);
                return false;
            }
            else if (j > 3600) {
                $('.error_description').css("display", "block");
                $('.error_short_description').css("display", "none");
                $('html, body').animate({
                    scrollTop: '600px'
                },
                1500);
                return false;
            } else {
                $('.error_description').css("display", "none");
                $('.error_short_description').css("display", "none");
            }
        });
    });
</script>