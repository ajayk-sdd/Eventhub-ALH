<?php
if (isset($_POST['data']['EventCategory']) || isset($_POST['data']['EventVibe']) || (isset($_POST['data']['Event']['title']) && !empty($_POST['data']['Event']['title']))) {
    $clss = "show-hide-panel";
    $txt = "Hide";
} else {
    $clss = "";
    $txt = "Show";
}
?>
<section class="inner-content">
<div class="center-block">
    <div class="em-sec profile-whole">
        <h1>Choose Email Lists</h1>
        <div class="breadcrumb">
            <ul>
                <li class="active">Step 1: Design</li>
                <li>Step 2: Set Up</li>
                <li>Step 3: Preview</li>
                <li>Step 4: Recipients</li>
            </ul>
        </div>
        <div class="createnewcampaign">
        <h3 class="title-text">Single Event</h3>

        <div class="eventsinthismail">
            <h3>Events in this Email :</h3>
            <ul class="addmoreevents" id = "addedEvent">
                <?php
                if (!empty($campaignEvent)) {

                    foreach ($campaignEvent as $ceKey => $ceValue) {
                        ?>
                        <li id="addedEvent<?php echo "_" . $ceKey; ?>"><span><?php echo $ceValue; ?></span><a href="javascript:void(0);" onclick="javascript:remove_event(<?php echo $ceKey; ?>)" class="smlpink_button">Remove</a></li>
                        <?php
                    }
                }
                ?>

            </ul>
        </div>
        <div style="clear:both;"></div>

        <!--        <div class="addmoreevents">
                    <h3><label>Add More Events</label>   <a href="#" class="violet_button">Create New Event</a></h3>
                </div>-->

<!--p class="">Showing Search Results for: <strong>"bikram yoga"</strong></p-->
        <!-- search panel start here -->
        <div class="search-panel">
            <?php
            $campaign_id = "";
            if ($campaign_data && !empty($campaign_data)) {
                $campaign_id = base64_encode($campaign_data["Campaign"]["id"]);
            }
            echo $this->Form->create("Campaign", array("action" => "campaignDesignStep2/", "name" => "search_form"));
            ?>

            <h1>Search and Filtering
                <a href="javascript:void(0);" class="bn-hide-show"><?php echo $txt; ?></a>
            </h1>
            <div class="sp-inner campaigndesgnstp2">
                <ul class="sp-hide-content <?php echo $clss; ?>" >
                    <li>
                        <label>Find an Event:</label>
                        <?php echo $this->Form->input("Event.title", array('type' => 'text', 'div' => false, 'label' => false, "placeholder" => "Event Title")); ?>


                    </li>
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
                                    if (isset($_POST['data']['EventCategory']['id'][$cID]) && $_POST['data']['EventCategory']['id'][$cID] == "on") {
                                        echo 'checked';
                                    }
                                    ?>><?php echo $category['Category']['name']; ?></label>
                                <?php endforeach; ?>
                        </div>

                    </li>
                    <?php /* ?> <li>
                      <label>Regions:</label>
                      <p>Northern California,  Southern California, Washington...</p>
                      <a href="javascript:void(0);" class="show-more">More+</a>
                      <div class="more-option">
                      <?php foreach($regions as $region):?>
                      <label><input type="checkbox"><?php echo $region['Region']['name'];?></label>
                      <?php endforeach;?>
                      </div>
                      </li><?php */ ?>
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
                                    if (isset($_POST['data']['EventVibe']['id'][$vID]) && $_POST['data']['EventVibe']['id'][$vID] == "on") {
                                        echo 'checked';
                                    }
                                    ?>><?php echo $vibe['Vibe']['name']; ?></label>
                                <?php endforeach; ?>
                        </div>
                    </li>
                    <li>
                        <?php
                        echo $this->Form->input("Search", array('type' => 'submit', 'div' => false, 'label' => false, "onclick" => "javascript:document.search_form.submit();"));
                        echo $this->html->link('Clear Search', array('controller' => 'Campaigns', 'action' => 'campaignDesignStep2/'), array("class" => "clear-search"));
                        ?>

                    </li>
                    <div class="clear"></div>
                </ul>
            </div>
            <div class="sp-bottom">
                <?php echo $this->Form->input("Event.order", array('type' => 'select', 'empty' => 'Sort by....', 'options' => array("Event.title ASC" => "Name A-Z", "Event.title DESC" => "Name Z-A", "Event.start_date ASC" => "Date: Soonest", "Event.created DESC" => "Date: Lattest"), 'div' => false, 'label' => false, "onchange" => "javascript:document.search_form.submit();")); ?>


                <?php echo $this->Form->input("Event.date", array('type' => 'select', 'empty' => 'Select date range', 'options' => array("today" => "Today", "week" => "This week", "month" => "This month", "year" => "This year", "all" => "All"), 'div' => false, 'label' => false, "onchange" => "javascript:document.search_form.submit();")); ?>
                <?php echo $this->Form->input("Event.limit", array('type' => 'hidden', "id" => "limit")); ?>
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

                <select class="ld-view">
                    <option selected="" value="Detailed View">Detailed View</option>
                    <option value="List View">List View</option>
                </select>
                <div class="clear"></div>
            </div>
            <div class="sp-cl">
                <p>Now showing events within <?php echo $distance; ?> miles of your location.</p>
                <div class="findByNum" style="display:none">
                    <span>Within</span>
                    <?php echo $this->Form->input("Event.distance", array('type' => 'select', 'empty' => 'Select All', 'options' => array("10" => "10", "20" => "20", "30" => "30", "50" => "50", "100" => "100"), 'div' => false, 'label' => false)); ?>
                    <?php echo $this->Form->input("Event.lat_long", array("type" => "hidden", "id" => "lat_long")); ?>


                    <span>Mile(s) of</span>
                    <?php echo $this->Form->input("Event.zip", array('type' => 'text', 'div' => false, 'label' => false, "placeholder" => "Change location, enter zip.")); ?>

                    <?php echo $this->Form->input("Go", array('type' => 'submit', 'div' => false, 'label' => false, "onclick" => "javascript:document.search_form.submit();")); ?>
                </div>
                <a href="javascript:void(0);" class="btn-changeLoc" style="display:inline-block;">Change Location</a>
            </div>

            <?php echo $this->Form->end(); ?>
            <!-- event container start here -->
            <div class="event-container">
                <?php
                $k = 0;
                foreach ($events as $event) {
                    //if (!empty($event['EventCategory']) && !empty($event['EventVibe'])) {
                    ?>
                    <div class="event-box">
                        <div class="event-box-img"> <?php echo $this->html->image("flyerImage/small/" . $event["Event"]["flyer_image"], array('class' => 'event_img')); ?> </div>
                        <div class="event-short-des">
                            <h2><?php echo $event["Event"]["title"]; ?></h2>
                            <p><?php echo $event["Event"]["sub_title"]; ?></p>
                            <p> <?php echo $event["Event"]["event_location"]; ?></p>
                            <p><?php
                                foreach ($event["EventDate"] as $ed) {
                                    echo date('l, F d, Y', strtotime($ed["date"])) . "  " . date('H:i A', strtotime($ed['start_time'])) . " - " . date('H:i A', strtotime($ed['end_time'])) . "<br>";
                                }
                                ?></p>
                            <p><?php
                                echo "Ticket Cost: ";
                                if (isset($event["Event"]["ticket_price"])) {
                                    echo "$" . $event["Event"]["ticket_price"];
                                }
                                ?></p>
                        </div>
                        <div class="clear"></div>
                        <div class="event-dv-content">
                            <div class="para-ed"><?php echo substr($event["Event"]['short_description'], 0, 300) . '...'; ?></div>
                            <?php echo $this->html->link("View Details", array("controller" => "Events", "action" => "viewEvent", base64_encode($event["Event"]["id"])), array("class" => "")); ?>
                            <?php
                            if (!$this->Session->read('Auth.User')) {
                                $ifLog = 'data-target="#sign_in" data-toggle="modal"';
                            } else {
                                $ifLog = '';
                            }
                            ?>
                            <a class="btn-addToCal" href="javascript:void(0);" <?php echo $ifLog; ?> onclick="add_to_event(<?php echo $event['Event']['id']; ?>, '<?php echo $event['Event']['title']; ?>')"><span id = "<?php echo $event["Event"]["id"]; ?>"><?php
                                    if (isset($campaignEvent[$event["Event"]["id"]]))
                                        echo "-Remove Event";
                                    else
                                        echo "+Add Event";
                                    ?></span></a>
                            <span class="loader" id="load_<?php echo $event['Event']['id']; ?>" style="display: none;"><img src="/img/admin/loader.gif" alt=""></span>

                        </div>
                        <?php echo $this->html->link("View Event", array("controller" => "Events", "action" => "viewEvent", base64_encode($event["Event"]["id"])), array("class" => "btn-addToCal btn-viewEvent")); ?>
                        <?php
                        $k++;

                        if ($k % 2 == 0) {
                            echo '<br>';
                        }
                        ?>
                    </div>
                    <?php
                    // }
                }
                ?>



            </div>
            <div class="clear"></div>
            <div class="event-pagination paginate-list">
                <span class="peginationTxt"><?php echo $this->Paginator->counter(array('format' => 'Events %start% - %end% of %count%')); ?></span>
                <?php
//echo '<a rel="first" href="/Events/MyEventList" class = "ep-first"></a>';
//echo '<a rel="prev" href="/Events/MyEventList" class = "ep-prev"></a>';
//echo '<a rel="next" href="/Events/MyEventList" class = "ep-next"></a>';
//echo '<a rel="last" href="/Events/MyEventList" class = "ep-last"></a>';
                echo $this->Paginator->first('', null, null, array());

                echo $this->Paginator->prev('', null, null, array());
//echo $this->Paginator->numbers(array('separator' => ''));
                echo $this->Paginator->next('', null, null, array());
                echo $this->Paginator->Last('', null, null, array());
//echo $this->Paginator->counter(array('format' => 'Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%'));
                ?>

                <div class="clear"></div>
            </div>
        </div><!-- /event container end -->
        <div class="clear"></div>
    </div>
    <div class="clear"></div><br><br>
    <div class="breadcrumb">
        <ul>
            <li class="active">Step 1: Design</li>
            <li>Step 2: Set Up</li>
            <li>Step 3: Preview</li>
            <li>Step 4: Recipients</li>
        </ul>
    </div>
    <div class="clear"></div>
    <!----------------------------------- editor starts------------------------------------->
    <div style="width: 100%;">
        <span id = "CampaignHtmlSpan" style="color: #8A2BE2;
    display: block;
    padding: 10px 0;"></span><br>
        <span style="color:blueviolet;"><input type="button" class="previewcode-btn" value="Preview" onclick="javascript:preview();"><input type="button"  class="previewcode-btn" value="Source" onclick="javascript:source();"></span><br>
        <div class="iframe-code-lt">
            <span id = "IWillShowSource">
                <?php
                echo $this->Form->create("Campaign", array("action" => "saveHtml", "class" => "event-form"));
//echo $this->Form->hidden("Campaign.status", array("value" => 0));
                if ($campaign_data && !empty($campaign_data)) {
                    echo $this->Form->hidden("Campaign.id", array("value" => $campaign_data["Campaign"]["id"]));
                    echo $this->Form->input("Campaign.user_id", array("type" => "hidden", "value" => AuthComponent::user("id")));
                    $html_data = $campaign_data["Campaign"]["html"];
                    echo $this->Form->input("Campaign.id", array("value" => $campaign_data["Campaign"]["id"], "type" => "hidden"));
                } else {
                    $html_data = "";
                    echo $this->Form->hidden("Campaign.id");
                    echo $this->Form->input("Campaign.user_id", array("type" => "hidden", "value" => AuthComponent::user("id")));
                }
                ?>            
                <?php echo $this->Form->input("Campaign.html", array("type" => "textarea", "div" => FALSE, "label" => FALSE, "class" => "txtarea-sh", "value" => $html_data)); ?>
                <?php echo $this->Form->submit("Submit"); ?>
                <?php echo $this->Form->end(); ?>
            </span>
            <span id = "IWillShowPreview" style="display:none;"><?php
                if ($campaign_data && !empty($campaign_data)) {
                    echo $campaign_data["Campaign"]["html"];
                }
                ?></span>
        </div>
        <div class="iframe-code-rt" title="Select this template">
            <?php foreach ($event_template as $et) {
                ?>
                <div onclick="javascript:selectTemplate(<?php echo $et["EventTemplate"]["id"]; ?>, '<?php echo $et["EventTemplate"]["name"]; ?>')">
                    <?php echo $this->Html->image("front/" . $et["EventTemplate"]["image"], array("style" => "width:100%; height:100%;")); ?>
                </div>
            <?php }
            ?>
        </div>
    </div>
</div>
<!------------------------------------editor ends------------------------------------------>

</div>
</section>
<script>
    function selectTemplate(id, name) {
        jQuery.ajax({
            url: '/Campaigns/selectTemplate/' + id,
            success: function(data) {
                $("#CampaignHtml").val(data);
                $("#IWillShowPreview").html(data);
                $("#CampaignHtmlSpan").html(name + " Event template is choosen.");
            }
        });
    }
    // for getting latitude and longitude from IP address
    $.get("http://ipinfo.io", function(response) {
        $("#lat_long").val(response.loc);
    }, "jsonp");

    $(document).ready(function() {
        $('.bn-hide-show').click(function() {
            $('.sp-hide-content').toggleClass('show-hide-panel');
            $(this).text(($(this).text() == 'Show' ? 'Hide' : 'Show'))

        });

        $('.show-more').click(function() {
            $('.more-option').toggleClass('show-more-option');
        });

        $('.btn-changeLoc').click(function() {
            $('.findByNum').css('display', 'block');
            $('.btn-changeLoc').css('display', 'none');
        });

        $('.ld-view').change(function() {
            $('.event-container .event-box').toggleClass('event-list-view');
        });
        $('.show-more-vibes').click(function() {
            $('.vibes-more-option').toggleClass('show-more-option');
        });
        $('.show-more-categories').click(function() {
            $('.categories-more-option').toggleClass('show-more-option');
        });

    });

    function setLimit(limit) {
        $("#limit").val(limit);
        document.search_form.submit();
    }
    function add_to_event(id, title) {
        $('#load_' + id).show();
        jQuery.ajax({
            url: '/Campaigns/addToEvent/' + id + '/' + title,
            success: function(data) {
                $('#load_' + id).hide();
                if (data == 1) {
                    $("#" + id).html("+Add Event");
                    $("#addedEvent_" + id).remove();
                } else if (data == 2) {
                    $("#" + id).html("-Remove Event");
                    $("#eventAdded_" + id).hide();
                    var toAppend = '<li id="addedEvent_' + id + '">' + title + '<a href="javascript:void(0);" onclick="javascript:remove_event(' + id + ')">Remove</a></li>';
                    $("ul#addedEvent").append('<li id="addedEvent_' + id + '"><span>' + title + '</span><a href="javascript:void(0);" onclick="javascript:remove_event(' + id + ')" class="smlpink_button">Remove</a></li>');
                } else {
                    alert(data);
                }


            }
        });
    }

    function remove_event(id) {
        jQuery.ajax({
            url: '/Campaigns/removeEvent/' + id,
            success: function(data) {
                if (data == 1) {
                    $("#addedEvent_" + id).remove();
                    $("#"+id).html("+Add Event");
                } else {
                    alert(data);
                }


            }
        });
    }

    function preview() {
        //alert("I will show you preview one day");
        $("#IWillShowSource").hide();
        $("#IWillShowPreview").show();

    }
    function source() {
        //alert("I will show you source one day");
        $("#IWillShowSource").show();
        $("#IWillShowPreview").hide();
    }
</script>