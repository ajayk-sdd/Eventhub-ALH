
    <script>
        
        function changeLocOpen()
        {

            document.getElementById("findByNum").style.display = "block";
            document.getElementById("btn_zipfilter").style.display = "none";


        }

        function changeLocClose()
        {

            document.getElementById("findByNum").style.display = "none";
            document.getElementById("btn_zipfilter").style.display = "block";

        }

   

    </script>
    <?php
if (isset($this->data['EventCategory']) || isset($this->data['EventVibe']) || (isset($this->data['Event']['title']) && !empty($this->data['Event']['title']))) {
    $clss = "show-hide-panel";
    $txt = "Hide";
} else {
    $clss = "";
    $txt = "Show";
}
//pr($this->data);
//pr($events);
//die;
?> <?php echo $this->Form->create("Campaigns", array("action" => "chooseEventMultiple", "name" => "search_form")); ?>

     <div class="em-sec">
            <h1>Create New Campaign</h1>
            <?php echo $this->Session->flash(); ?>
            <div class="breadcrumb">
                           <ul>
            <li class="active">Step 1: Select Event</li>
            <li>Step 2: Design</li>
            <li>Step 3: Recipients</li>
            <li >Step 4: Preview</li>

        </ul>
            </div>
            <div class="clear"></div>
             <div class="search-panel inner-contentnew">
          
            <h1><div class="search-top">
                  <label>Find an Event:</label>
                        <?php echo $this->Form->input("Event.title", array('type' => 'text', 'div' => false, 'label' => false, "placeholder" => "Event Title"));
                         //echo $this->Form->input("Search", array('type' => 'submit', 'div' => false, 'label' => false, "onclick" => "javascript:document.search_form.submit();","class"=>"src-btn"));
                       
                        ?>
                        <input type="submit" onclick="javascript:document.search_form.submit();" class="src-btn" value="Search">

            </div>
                <a href="javascript:void(0);" class="bn-hide-show"><?php echo $txt; ?></a>
            </h1>
            <div class="sp-inner">
                <ul class="sp-hide-content <?php echo $clss; ?>" >
                  
                        <li>
                            <label>Event Categories: &nbsp;</label>
                            <p>
                                <?php
                                if (isset($categoriesS)) {
                                    echo $categoriesS;
                                }
                                ?>
                            </p>
                            <a href="javascript:void(0);" class="show-more-categories">More+</a>
                            <div class="categories-more-option">
                                <?php
                                foreach ($categories as $category):
                                    $cID = $category['Category']['id'];
                                    ?>
                                    <label><input type="checkbox" name="data[EventCategory][id][<?php echo $category['Category']['id']; ?>]"  <?php
                                        if (isset($this->data['EventCategory']['id'][$cID]) && $this->data['EventCategory']['id'][$cID] == "on") {
                                            echo 'checked';
                                        }
                                        ?>><?php echo $category['Category']['name']; ?></label>
                                    <?php endforeach; ?>
                            </div>

                        </li>
                      
                        <li>
                            <label>Event Vibe: &nbsp;</label>
                            <p><?php
                                if (isset($vibesS)) {
                                    echo $vibesS;
                                }
                                ?></p>
                            <a href="javascript:void(0);" class="show-more-vibes">More+</a>
                            <div class="vibes-more-option">
                                <?php
                                foreach ($vibes as $vibe):
                                    $vID = $vibe['Vibe']['id'];
                                    ?>
                                    <label><input type="checkbox" name="data[EventVibe][id][<?php echo $vibe['Vibe']['id']; ?>]" <?php
                                        if (isset($this->data['EventVibe']['id'][$vID]) && $this->data['EventVibe']['id'][$vID] == "on") {
                                            echo 'checked';
                                        }
                                        ?>><?php echo $vibe['Vibe']['name']; ?></label>
                                    <?php endforeach; ?>
                            </div>
                        </li>
                     <li>
                      
 <p>

                <?php if (isset($zip_code_name) && !isset($zip_condition)) { ?>
                    Showing all events within <span><?php echo $distance; ?></span> miles of <span><?php echo $zip_code_name; ?></span>.
                <?php } else if (isset($city_name) && !empty($city_name) && !isset($zip_condition)) {
                    ?>
                    Showing all events within <span><?php echo $distance; ?></span> miles of <span><?php echo $city_name; ?></span>.

                <?php } else {
                    ?>
                <p style="color:red;">Excluding zip code from search result, as it is seems to be wrong.</p>
                <?php
            }
            ?></p>
            <a href="javascript:changeLocOpen();"  id="btn_zipfilter"  class="show-more-location" >Change Location</a>
            

           <div class="location-more-option">
                    <span>Within</span>
                    <?php echo $this->Form->input("Event.distance", array('type' => 'select', 'options' => array('200' => 'Select All', "5" => "5", "10" => "10", "15" => "15", "20" => "20", "25" => "25", "30" => "30", "50" => "50", "100" => "100"), 'div' => false, 'label' => false)); ?>
                    <?php echo $this->Form->input("Event.lat_long", array("type" => "hidden", "id" => "lat_long")); ?>


                    <span>Mile(s) of</span>
                     <div class="show_events"  style="padding: 0px; float: right;">   <a href="javascript:changeLocClose();" class="btn-zipfilter-cancel">Cancel</a></div>

                    <div class="search_events box-location"><?php echo $this->Form->input("Event.zip", array('type' => 'text', 'div' => false, 'label' => false, "placeholder" => "Change location, enter zip.", "maxlength" => 6)); ?>

                        <?php echo $this->Form->input("", array('type' => 'submit', 'div' => false, 'label' => false, "onclick" => "javascript:document.search_form.submit();", "class" => "search_button")); ?>
                    </div>
                   
                </div>
           

                    </li>
                  
                    <li>
                        <?php
                       // echo $this->Form->input("Search", array('type' => 'submit', 'div' => false, 'label' => false, "onclick" => "javascript:document.search_form.submit();"));
                        echo $this->html->link('Clear Search', array('controller' => 'Campaigns', 'action' => 'chooseEventMultiple', "clear"), array("class" => "clear-search"));
                        ?>

                    </li>
                    <div class="clear"></div>
                </ul>
            </div>
            
             </div>
     </div>
     
     <div id='selectedEvent' class="sp-inner  inner-contentnew">
        <h2>Events in this Email:</h2><br>
    <?php
if ($this->Session->check("CampaignEvent")) {
    $readAddedEvent = $this->Session->read("CampaignEvent");
 
     echo "<ul  class='sp-hide-content show-hide-panel'>";
    foreach ($readAddedEvent as $key => $value) {
       
        ?>
        <li><p><?php echo $value; ?></p><a href="javascript:void(0);" onclick="javascript:selectThisEvent(<?php echo $key; ?>,'<?php echo $value; ?>');" >Remove</a></li>
        <?php
        
    }
    echo "</ul>";
    echo '<div align="right" style="width: 100%; float: right; margin-top: 25px;">';
    echo $this->Html->link("Save & Continue",array("controller"=>"Campaigns","action"=>"chooseTemplate"),array("class"=>"clear-search"));
    echo '</div>';
} else {
    echo "<b style='color:red;'>No Event Selected Yet</b>";
}
?>
</div>
    <section class="inner-content inner-contentnew">
        <?php //echo $this->Form->create("User", array("action" => "index", "name" => "search_forms")); ?>
        <!--Left Section Starts-->
       

           
          
            

            <?php echo $this->Form->input("Event.limit", array('type' => 'hidden', "id" => "limit")); ?>
     


        <!--Left Section Ends-->
        <!--Middle Section Starts-->
        <h2>Add More Events:</h2>
        <section class="middle_section camp-left">
          
           
            <div class="alist_heading">
                <h2 class="main_heading">ALIST Calendar / FaceBook</h2>
                <?php
                
                 $log_option =  array("all" => "All Events", "ALH" => "ALH Events", "FB" => "Facebook Events", "EF" => "Eventful Events");  
             
                echo $this->Form->input("Event.event_show", array('type' => 'select', 'options' => $log_option, 'div' => false, 'label' => false, "onchange" => "javascript:document.search_form.submit();", "class" => "float-right")); ?>
            </div>
            <ul class="rcmevent_listing">
                <?php
                if (isset($events) && !empty($events)) {
                    //pr($events);
                    foreach ($events as $event) {
                        $event_id_name[$event["Event"]["id"]] = $event["Event"]["title"];
                        $cntDate = count($event['EventDate']);
                        ?>
                        <li>
                            <section class="rcmnded_events" id="<?php echo 'event_box_' . $event['Event']['id']; ?>">


                                 <?php
                                $viewDetails = BASE_URL.'/Events/viewEvent/' . base64_encode($event["Event"]["id"]);
                                if ($event["Event"]["event_from"] == "facebook") {
                                    $imgPath = '';
                                    $event_icon = 'fb_btn.png';
                                } elseif ($event["Event"]["event_from"] == "eventful") {
                                    $imgPath = '';
                                    $event_icon = 'listhub_icon.png';
                                    $viewDetails = BASE_URL.'/Events/viewEventfulEvent/' . $event["Event"]["ef_event_id"];
                                } else {
                                    $imgPath = BASE_URL."/img/EventImages/small/";
                                    $event_icon = 'listhub_icon.png';
                                }
                                ?>
                                
                                <a href="#" class="listhub_icon"><img src="<?php echo BASE_URL."/img/". $event_icon; ?>" alt=""/></a>
                                <?php
                                if (isset($event["Giveaway"]) && $event["Giveaway"]["id"] != '') {
                                    ?> 
                                    <a href="#" class="listhub_icon3"><img src="<?PHP echo BASE_URL;?>/img/ticon.png" alt=""/></a>
                                <?php }
                                ?>
                                <?php
                                if (!$this->Session->read('Auth.User')) {
                                    $ifLog = 'data-target="#sign_in" data-toggle="modal"';
                                } else {
                                    $ifLog = '';
                                }
                                $log_user = $this->Session->read('Auth.User');
                                ?>

                                <span class="loader" id="load_<?php echo $event['Event']['id']; ?>" style="display: none;"><img src="<?PHP echo BASE_URL;?>/img/admin/loader.gif" alt=""></span>

                                <span class="dummy_thumb">
                                    <?php echo $this->html->image($imgPath . $event["Event"]["image_name"], array('class' => 'event_img', 'width' => '120px', 'height' => '100px')); ?>
                                </span>	
                                <section class="events_info">
                                    <a href="<?php echo $viewDetails; ?>"><h3><?php
                                            if (strlen($event["Event"]["title"]) > 40) {
                                                echo substr($event["Event"]["title"], 0, 40) . "..";
                                            } else {
                                                echo $event["Event"]["title"];
                                            }
                                            ?></h3></a>
                                    <label><?php
                                        $n = 1;

                                        sort($event["EventDate"]);

                                        $now = strtotime(date('Y-m-d'));
                                        foreach ($event["EventDate"] as $ed) {
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
                                        ?></label>
                                    <span><?php
                                        if (!empty($event["Event"]["specify"])) {
                                            echo $event["Event"]["specify"] . ', ';
                                        } echo $event["Event"]["cant_find_city"] . ', ' . $event["Event"]["cant_find_state"];
                                        ?></span>
                                    <div class="detailEvent"> <div class="event-dv-content"><?php echo substr(strip_tags($event["Event"]['short_description']), 0, 195) . '...'; ?></div></div>
                                </section>
                                <?php
                                if(isset($readAddedEvent))
                                {
                                    if (array_key_exists($event["Event"]["id"], $readAddedEvent)) {
                                            $addedText = "- Remove Event";
                                        }
                                        else
                                        {
                                            $addedText = "+ Add Event";
                                        }
                                }
                                 else
                                        {
                                            $addedText = "+ Add Event";
                                        }
                                 ?>
                              <a href="javascript:void(0);" class="btn-addEvent" id = '<?php echo $event["Event"]["id"]; ?>' title='<?php echo $event["Event"]["title"]; ?>' onclick='selectThisEvent(this.id, this.title);'><?php echo $addedText; ?></a>                            
   <span id="load_<?php echo $event["Event"]["id"]; ?>" class="loader" style="display: none;"><img alt="" src="/img/admin/loader.gif"></span>


                            </section>   	     
                        </li>
                        <?php
                    }
                } else {
                    ?>
                    <?php
                    //foreach ($eevent->events->event as $event) {
                    foreach ($efsorted as $event) {
                        ?>

                        <?php
                        //$er = json_decode(json_encode($event), true);
                        $evnt_id = $event['@attributes']['id'];
                        $evnt_id = str_replace("@", "_", $evnt_id);
                        ?>
                        <li>
                            <section class="rcmnded_events">

                                <?php if (isset($event['image']['url']) && !empty($event['image']['url'])) {
                                    ?>
                                    <span class="dummy_thumb">
                                        <img src="<?php echo $event['image']['url']; ?>" class="event_img" width="120px" height="100px" >
                                    </span>
                                    <?php
                                } else {
                                    //echo $this->html->image("no_image.jpeg", array('class' => 'event_img', 'width' => '120px', 'height' => '100px'));
                                }
                                ?>

                                <section class="events_info">
                                    <h3>
                                        <?php
                                        $title = $event['title'];
                                        if (isset($event['image']['url'])) {
                                            if (strlen($title) > 40) {
                                                $title = substr($title, 0, 40) . "..";
                                            }
                                        } else {
                                            if (strlen($title) > 50) {
                                                $title = substr($title, 0, 50) . "..";
                                            }
                                        }
                                        echo $this->html->link($title, array("controller" => "Events", "action" => "viewEventfulEvent", $evnt_id), array("class" => "evnt-ttl", "target" => "_blank", "title" => $event['title']));
                                        ?>

                                    </h3>	
                                    <label><?php
                                        $today = date("l, F d");
                                         if(isset($event['stop_time']) && !empty($event['stop_time']))
                                    {
                                    if (strtotime($event['start_time']) <= strtotime($today) && strtotime($event['stop_time']) >= strtotime($today)) {
                                        echo $today;
                                    } else {
                                        echo date("l, F d", strtotime($event['start_time']));
                                    }
                                    }
                                    else {
                                        echo date("l, F d", strtotime($event['start_time']));
                                    }
                                        ?></label>
                                    <span><?php echo $event['city_name']; ?></span>
                                      <?php
                                  
                                  
                                    if (isset($EFAddedEventId))
                                        {
                                     if (in_array($evnt_id, $EFAddedEventId))
                                        {
                                            $addedText = "- Remove Event";
                                        }
                                        else
                                        {
                                            $addedText = "+ Add Event";
                                        }
                                         }
                                     else
                                        {
                                            $addedText = "+ Add Event";
                                        }
      
                                    ?>
   <a href="javascript:void(0);" class="btn-addEvent" id = '<?php echo $evnt_id; ?>' title='<?php echo $title; ?>' onclick='selectThisEFEvent(this.id);'><?php echo $addedText; ?></a>                            
   <span id="load_<?php echo $evnt_id; ?>" class="loader" style="display: none;"><img alt="" src="/img/admin/loader.gif"></span>
                                </section>
                            </section>   	     
                        </li>
                    <?php } ?>
        <?php
    }
    ?>

            </ul>

            <div class="clear"></div>
            <div class="pagination">

                <?php
                if ($limit == 10)
                    $active_10 = "active";
                else
                    $active_10 = "";
                if ($limit == 20)
                    $active_20 = "active";
                else
                    $active_20 = "";
                if ($limit == 40)
                    $active_40 = "active";
                else
                    $active_40 = "";
                ?>
                <div class="paginate-list">
                    <span>Show</span>
                    <a href="javascript:void(0);" onclick="javscript:setLimit(10);" class="<?php echo $active_10; ?>">10</a>
                    <a href="javascript:void(0);" onclick="javscript:setLimit(20);" class="<?php echo $active_20; ?>">20</a>
                    <a href="javascript:void(0);" onclick="javscript:setLimit(40);" class="<?php echo $active_40; ?>">40</a>
                    <span>Per Page</span>
                </div>
                <select class="new-view">
                    <option selected="" value="Detailed View">Detailed View</option>
                    <option value="List View">List View</option>
                </select>
            </div>
        </section>


        <!--Middle Section Ends-->
    <?php //echo $this->Form->end(); ?>
        <!--Right Section Starts-->
        
         <section class="middle_section camp-right">
          
           
            <div class="alist_heading">
                <h2 class="main_heading">Events Feed</h2>
              </div>
           <ul class="rcmevent_listing eventfeed">
                 <?php
                //foreach ($eevent->events->event as $event) {
                foreach ($efsorted as $event) {
                    ?>

                    <?php
                    //$er = json_decode(json_encode($event), true);
                    $evnt_id = $event['@attributes']['id'];
                    $evnt_id = str_replace("@", "_", $evnt_id);
                    ?>
                    <li>
                        <section class="rcmnded_events">

        <?php if (isset($event['image']['url']) && !empty($event['image']['url'])) {
            ?>
                                <span class="dummy_thumb">
                                    <img src="<?php echo $event['image']['url']; ?>" class="event_img" width="120px" height="100px" >
                                </span>
                                <?php
                            } else {
                                //echo $this->html->image("no_image.jpeg", array('class' => 'event_img', 'width' => '120px', 'height' => '100px'));
                            }
                            ?>

                            <section class="events_info">
                                <h3>
                                    <?php
                                    $title = $event['title'];
                                    if (isset($event['image']['url'])) {
                                        if (strlen($title) > 20) {
                                            $title = substr($title, 0, 20) . "..";
                                        }
                                    } else {
                                        if (strlen($title) > 30) {
                                            $title = substr($title, 0, 30) . "..";
                                        }
                                    }
                                    echo $this->html->link($title, array("controller" => "Events", "action" => "viewEventfulEvent", $evnt_id), array("class" => "evnt-ttl", "target" => "_blank", "title" => $event['title']));
                                    ?>

                                </h3>	
                                <label><?php
                                    $today = date("l, F d");
                                    if(isset($event['stop_time']) && !empty($event['stop_time']))
                                    {
                                    if (strtotime($event['start_time']) <= strtotime($today) && strtotime($event['stop_time']) >= strtotime($today)) {
                                        echo $today;
                                    } else {
                                        echo date("l, F d", strtotime($event['start_time']));
                                    }
                                    }
                                    else {
                                        echo date("l, F d", strtotime($event['start_time']));
                                    }
                                    ?></label>
                                <span><?php echo $event['city_name']; ?></span>
                                 <?php
                                  
                                  
                                    if (isset($EFAddedEventId))
                                        {
                                     if (in_array($evnt_id, $EFAddedEventId))
                                        {
                                            $addedText = "- Remove Event";
                                        }
                                        else
                                        {
                                            $addedText = "+ Add Event";
                                        }
                                         }
                                     else
                                        {
                                            $addedText = "+ Add Event";
                                        }
      
                                    ?>
   <a href="javascript:void(0);" class="btn-addEvent" id = '<?php echo $evnt_id; ?>' title='<?php echo $title; ?>' onclick='selectThisEFEvent(this.id);'><?php echo $addedText; ?></a>                            
   <span id="load_<?php echo $evnt_id; ?>" class="loader" style="display: none;"><img alt="" src="/img/admin/loader.gif"></span>
                            </section>
                        </section>   	     
                    </li>
    <?php } ?>
            </ul>
         </section>
       
          
        <!--Right Section Ends-->

        <div class="clear"></div>
    </section>
<?php echo $this->Form->end(); ?>
<?php //} ?>
<style>

    .event-dv-content
    {
        font-size: 11px;}</style>
<script>
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({'address': "01001"},
    function(results_array, status) {
        console.log(status);
        console.log(results_array[0].geometry.location.lat());
        // Check status and do whatever you want with what you get back
        // in the results_array variable if it is OK.
        // var lat = results_array[0].geometry.location.lat()
        // var lng = results_array[0].geometry.location.lng()
    });

    function vistBanner(banner) {
        //alert(banner);
        jQuery.ajax({
            type: "POST",
            data: {banner: banner},
            url: '/Users/visitBanner',
            success: function(data) {

            }
        });
    }
</script>

<script>
    // for getting latitude and longitude from IP address
    $.get("http://ipinfo.io", function(response) {

        $("#lat_long").val(response.loc);
    }, "jsonp");
    $.get("http://freegeoip.net/json/", function(data) {
        //console.log(data);

    });
</script>

<script>
    
    function selectThisEFEvent(id, title) {
        $('#load_' + id).show();
        jQuery.ajax({
            url: '/Campaigns/selectMultipleEFEvent/' + id,
            success: function(data) {
                $('#load_' + id).hide();
                var html = $("#" + id).html();
                if (html == "+ Add Event") {
                    $("#" + id).html("- Remove Event");
                } else {
                    $("#" + id).html("+ Add Event");
                }
                location.reload();
                $("#selectedEvent").html(data);
            }
        });
    }
    
    function selectThisEvent(id, title) {
         $('#load_' + id).show();
        jQuery.ajax({
            url: '/Campaigns/selectMultipleEvent/' + id + '/' + title,
            success: function(data) {
                $('#load_' + id).hide();
                var html = $("#" + id).html();
                if (html == "+ Add Event") {
                    $("#" + id).html("- Remove Event");
                } else {
                    $("#" + id).html("+ Add Event");
                }
                location.reload();
                $("#selectedEvent").html(data);
            }
        });
    }

    
    $(document).ready(function() {
        $("a").on("click", function() {
            $(window).unbind('beforeunload');
        });

     
        $('.bn-hide-show').click(function() {
            $('.sp-hide-content').toggleClass('show-hide-panel');
            $(this).text(($(this).text() == 'Show' ? 'Hide' : 'Show'))

        });

        $("#vibe-flip").click(function() {
            $("#vibe-panel").slideToggle("fast");
            $("#plus").toggleClass("plus-hide");
            $("#minus").toggleClass("minus-show");
        });

        $('.show-more').click(function() {
            $('.more-option').toggleClass('show-more-option');
        });

        $('.btn-zipfilter').click(function() {
            $('.findByNum').css('display', 'block');
            $('.btn-zipfilter').css('display', 'none');
        });

        $('.new-view').change(function() {
            $('.detailEvent').toggleClass('event-list-view');
        });
        $('.show-more-vibes').click(function() {
            $('.vibes-more-option').toggleClass('show-more-option');
        });
        $('.show-more-categories').click(function() {
            $('.categories-more-option').toggleClass('show-more-option');
        });
        
         $('.show-more-location').click(function() {
            $('.location-more-option').toggleClass('show-more-option');
        });

        $("#save-pref-cat").click(function() {
            getCatValueUsingClass();
        });

        $("#save-pref-vib").click(function() {
            getVibValueUsingClass();
        });

    });

    function getCatValueUsingClass() {
        /* declare an checkbox array */
        var chkArray = [];
        $("#load_cats").show();
        /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
        $(".catsave:checked").each(function() {
            var cval = $(this).attr('class');
            var intval = cval.replace(/[A-Za-z$-]/g, "");
            var catv = parseInt(intval);
            chkArray.push(catv);
        });

        var selected;
        selected = chkArray.join(',') + ",";

        if (selected.length > 1) {

            var dtt;
            $.post(base_url + '/Users/saveprefrence', {"data[Savep]": chkArray}, function(dtt) {

                $("#load_cats").hide();
                $("#save-cat").show();
                $("#save-cat").html("Saved Successfully");
            });
        } else {
            $("#load_cats").hide();
            alert("Please at least one of the checkbox");
        }

    }

    function getVibValueUsingClass() {
        /* declare an checkbox array */
        var chkArray = [];
        $("#load_vibs").show();
        /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
        $(".vibsave:checked").each(function() {
            var vval = $(this).attr('class');
            var intval = vval.replace(/[A-Za-z$-]/g, "");
            var vibv = parseInt(intval);
            chkArray.push(vibv);
        });

        var selected;
        selected = chkArray.join(',') + ",";

        if (selected.length > 1) {

            var dtt;
            $.post(base_url + '/Users/savevibprefrence', {"data[Savep]": chkArray}, function(dtt) {
                $("#load_vibs").hide();
                $("#save-vib").show();
                $("#save-vib").html("Saved Successfully");
            });
        } else {
            $("#load_vibs").hide();
            alert("Please at least one of the checkbox");
        }

    }
    function setLimit(limit) {
        $("#limit").val(limit);
        document.search_form.submit();
    }
</script>
