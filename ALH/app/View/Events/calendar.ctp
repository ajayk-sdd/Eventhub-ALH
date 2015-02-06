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
?>
<style>
    .event-box.feature_event {
        border: 4px solid #7900a0;
    }
</style>

<div class="center-block">
    <div class="em-sec">
        <h1>ALIST Calendar</h1>
<!--p class="">Showing Search Results for: <strong>"bikram yoga"</strong></p-->
        <!-- search panel start here -->
        <div class="search-panel">
            <?php echo $this->Form->create("Event", array("action" => "calendar", "name" => "search_form")); ?>

            <h1>Search and Filtering
                <a href="javascript:void(0);" class="bn-hide-show"><?php echo $txt; ?></a>
            </h1>
            <div class="sp-inner">
                <ul class="sp-hide-content <?php echo $clss; ?>" >
                    <li>
                        <label>Find an Event:</label>
                        <?php echo $this->Form->input("Event.title", array('type' => 'text', 'div' => false, 'label' => false, "placeholder" => "Event Title")); ?>


                    </li>
                    <?php if ($event_show == "ALH") {
                        ?>
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
                                        if (isset($this->data['EventVibe']['id'][$vID]) && $this->data['EventVibe']['id'][$vID] == "on") {
                                            echo 'checked';
                                        }
                                        ?>><?php echo $vibe['Vibe']['name']; ?></label>
                                    <?php endforeach; ?>
                            </div>
                        </li>
                    <?php } ?>
                    <li>
                        <?php
                        echo $this->Form->input("Search", array('type' => 'submit', 'div' => false, 'label' => false, "onclick" => "javascript:document.search_form.submit();"));
                        echo $this->html->link('Clear Search', array('controller' => 'Events', 'action' => 'calendar', "clear"), array("class" => "clear-search"));
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
                <?php
                if (isset($giveaway) && $giveaway == 1) {
                    ?>
                    <div class="ticket-give"> <input name = 'data[Event][giveaway]' type ="checkbox" class="free-ticket" onclick ="document.search_form.submit();" value="1" checked="checked"/>Ticket Giveaway</div>
                    <?php
                } else {
                    ?>
                    <div class="ticket-give">  <input name = 'data[Event][giveaway]' type ="checkbox" class="free-ticket" onclick ="document.search_form.submit();" value="1" />Ticket Giveaway</div>
                <?php }
                ?>
                <?php
                //echo $is_feature;
                if (isset($is_feature) && $is_feature == 1) {
                    ?>
                    <div class="ticket-give"> <input name = 'data[Event][is_feature]' type ="checkbox" class="free-ticket" onclick ="document.search_form.submit();" value="1" checked="checked"/>Recommended</div>
                    <?php
                } else {
                    ?>
                    <div class="ticket-give">  <input name = 'data[Event][is_feature]' type ="checkbox" class="free-ticket" onclick ="document.search_form.submit();" value="0" />Recommended</div>
                <?php }
                ?>

                <select class="ld-view">
                    <option selected="" value="Detailed View">Detailed View</option>
                    <option value="List View">List View</option>
                </select>
                <div class="clear"></div>
            </div>
            <div class="sp-cl">
                <?php if (isset($zip_code_name)) { ?>
                    <p>Now showing events within <?php echo $distance; ?> miles from <?php echo $zip_code_name; ?>.</p>
                <?php } else if (isset($city_name) && !empty($city_name)) {
                    ?>
                    <p>Showing all events around <?php echo $city_name; ?>.</p>
                <?php } else {
                    ?>
                    <p style="color:red;">Excluding zip code from search result, as it is seems to be wrong.</p>
                    <?php
                }
                ?>
                <?php
                if (isset($save_from_zip)) {
                    $display = "block";
                    $chane_location = "none";
                } else {
                    $display = "none";
                    $chane_location = "inline-block";
                }
                ?>
                <div class="findByNum" style="display:<?php echo $display; ?>">
                    <span>Within</span>
                    <?php echo $this->Form->input("Event.distance", array('type' => 'select', 'empty' => 'Select All', 'options' => array("5" => "5", "10" => "10", "15" => "15", "20" => "20", "25" => "25", "30" => "30", "50" => "50", "100" => "100"), 'div' => false, 'label' => false)); ?>
                    <?php echo $this->Form->input("Event.lat_long", array("type" => "hidden", "id" => "lat_long")); ?>


                    <span>Mile(s) of</span>
                    <?php echo $this->Form->input("Event.zip", array('type' => 'text', 'div' => false, 'label' => false, "placeholder" => "Change location, enter zip.", "maxlength" => 6)); ?>

                    <?php echo $this->Form->input("Go", array('type' => 'submit', 'div' => false, 'label' => false, "onclick" => "javascript:document.search_form.submit();")); ?>
                </div>
                <a href="javascript:void(0);" class="btn-changeLoc" style="display:<?php echo $chane_location; ?>;">Change Location</a>

                <?php echo $this->Form->input("Event.event_show", array('type' => 'select', 'options' => array("all" => "All Events", "ALH" => "ALH Events", "FB" => "Facebook Events", "EF" => "Eventful Events"), "selected" => $event_show, 'div' => false, 'label' => false, "onchange" => "javascript:document.search_form.submit();", "class" => "float-right")); ?>
            </div>

            <?php echo $this->Form->end(); ?>
            <!-- event container start here -->
            <div class="event-container">
                <?php
                $k = 0;
                foreach ($events as $event) {
                    $event_id_name[$event["Event"]["id"]] = $event["Event"]["title"];
                    $cntDate = count($event['EventDate']);
                    // for($i=0;$i<=$cntDate-1;$i++){ 
                    ?>


                    <div class="event-box <?php if ($event["Event"]["is_feature"] == 1) echo "feature_event"; ?>" id = "event_box_<?php echo $event["Event"]["id"]; ?>">
                        <?php
                        if ($event["Event"]["event_from"] == "facebook") {
                            $imgPath = '';
                        } else {
                            $imgPath = "EventImages/small/";
                        }
                        ?>
                        <?php echo $this->html->image($imgPath . $event["Event"]["image_name"], array('class' => 'event_img', 'width' => '120px', 'height' => '100px')); ?>
                        <div class="event-short-des">
                            <a href="/Events/viewEvent/<?php echo base64_encode($event["Event"]["id"]); ?>"><h2><?php echo $event["Event"]["title"]; ?></h2></a>
                            <p><?php echo $event["Event"]["sub_title"]; ?></p>
                            <p> <?php echo $event["Event"]["event_location"]; ?></p>
                            <p><?php
                                $n = 1;
                                foreach ($event["EventDate"] as $ed) {
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
                                ?>

                                <?php
                                if (count($event["EventDate"]) > 1) {
                                    echo "This is a multi-day event<br>";
                                    echo $this->html->link("Click to see full event schedule.", array("controller" => "Events", "action" => "viewEvent", base64_encode($event["Event"]["id"])), array("class" => ""));
                                }
                                ?>
                            </p>
                            <p><?php
                                if (!empty($event["TicketPrice"])) {
                                    $str = "";
                                    foreach ($event['TicketPrice'] as $tcktPrice):
                                        $tp = str_replace("$", "", $tcktPrice['ticket_price']);
                                        $str .= $tcktPrice['ticket_label'] . ' $' . $tp . ",&nbsp;&nbsp;";

                                    endforeach;
                                    echo $str;
                                }
                                ?></p>
                        </div>
                        <div class="addtofav">
                            <img src="/img/ratingstar.png" />
                        </div>
                        <div class="clear"></div>
                        <div class="event-dv-content">
                            <div class="para-ed"><?php echo substr(strip_tags($event["Event"]['short_description']), 0, 300) . '...'; ?></div>
                            <div class="view-detail"><?php echo $this->html->link("View Details", array("controller" => "Events", "action" => "viewEvent", base64_encode($event["Event"]["id"])), array("class" => "")); ?>

                                <?php if (AuthComponent::user("role_id") && AuthComponent::user("role_id") == 1) { ?>
                                    <br>
                                    <a class="" title="Edit" href="/Events/editEvent/<?php echo base64_encode($event["Event"]["id"]); ?>"><img width="16" height="16" alt="" src="/img/admin/edit1.png" title="Edit"></a>
                                    <?php if ($event["Event"]["is_feature"] == 0) { ?>
                                        <a class="" title="Make Feature" href="javascript:void(0);" onclick="javascript:feature(<?php echo $event["Event"]["id"]; ?>);"><img id ="feature_icon_<?php echo $event["Event"]["id"]; ?>" width="16" height="16" alt="" src="/img/feature.png" title="Make Feature"></a>
                                    <?php } else { ?>
                                        <a class="" title="Make Feature" href="javascript:void(0);" onclick="javascript:feature(<?php echo $event["Event"]["id"]; ?>);"><img id ="feature_icon_<?php echo $event["Event"]["id"]; ?>" width="16" height="16" alt="" src="/img/not_feature.png" title="Remove Feature"></a>
                                    <?php } ?>
                                    <a class="" title="Remove" href="javascript:void(0);" onclick="javascript:remove_event(<?php echo $event["Event"]["id"]; ?>);"><img width="16" height="16" alt="" src="/img/admin/delete.png" title="Delete"></a>
                                    <span id="load_<?php echo $event["Event"]["id"]; ?>" class="loader" style="display: none;"><img alt="" src="/img/admin/loader.gif"></span>
                                <?php } ?>
                                <?php if (AuthComponent::user("id") && AuthComponent::user("role_id") != 1 && AuthComponent::user("id") == $event["Event"]["user_id"]) {
                                    ?>
                                    <br>
                                    <a class="" title="Edit" href="/Events/editEvent/<?php echo base64_encode($event["Event"]["id"]); ?>"><img width="16" height="16" alt="" src="/img/admin/edit1.png" title="Edit"></a>
                                    <a class="" title="Remove" href="javascript:void(0);" onclick="javascript:remove_event(<?php echo $event["Event"]["id"]; ?>);"><img width="16" height="16" alt="" src="/img/admin/delete.png" title="Delete"></a>
                                <?php }
                                ?>
                            </div><?php
                            if (!$this->Session->read('Auth.User')) {
                                $ifLog = 'data-target="#sign_in" data-toggle="modal"';
                            } else {
                                $ifLog = '';
                            }
                            $log_user = $this->Session->read('Auth.User');
                            ?>
                            <div class="add-cal">
                                <a class="btn-addToCal" href="javascript:void(0);" <?php echo $ifLog; ?> onclick="add_to_mycalender(<?php echo $event['Event']['id']; ?>)"><span id = "<?php echo $event["Event"]["id"]; ?>"><?php
                                        if (in_array($event["Event"]["id"], $my_calendar))
                                            echo "-Remove from My Calendar";
                                        else
                                            echo "+Add to My Calendar";
                                        ?></span></a>
                                <?php
                                if (isset($log_user['role_id']) && $log_user['role_id'] == 4) {
                                    ?>
                                    <a class="btn-addToCal" style="margin-right:10px" href="javascript:void(0);" <?php echo $ifLog; ?> onclick="add_to_mywpplugin(<?php echo $event['Event']['id']; ?>)"><span id = "wp<?php echo $event["Event"]["id"]; ?>"><?php
                                            if (in_array($event["Event"]["id"], $my_wpplugin))
                                                echo "Remove From Wp-Plugin";
                                            else
                                                echo "+Add To Wp-Plugin";
                                            ?></span></a>
                                    <?php
                                }
                                ?>
                                <span class="loader" id="load_<?php echo $event['Event']['id']; ?>" style="display: none;"><img src="/img/admin/loader.gif" alt=""></span>
                            </div>
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
                    //}
                }
                if (isset($event_id_name)) {
                    $event_id_name = json_encode($event_id_name);
                    echo $this->Form->create("Event", array("action" => "ticketGiveawayMultiple", "name" => "ticketGiveawayForm"));
                    echo $this->Form->hidden("event_id_name", array("value" => $event_id_name));
                    echo $this->Form->end();
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
</div>

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
</script>