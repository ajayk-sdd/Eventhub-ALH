<?php
#pr($events);
if (isset($_POST['data']['EventCategory']) || isset($_POST['data']['EventVibe']) || (isset($_POST['data']['Event']['title']) && !empty($_POST['data']['Event']['title']))) {
    $clss = "show-hide-panel";
    $txt = "Hide";
} else {
    $clss = "";
    $txt = "Show";
}
?>

<div class="center-block">
    <div class="em-sec">
        <h1>My WP-Plugin Setting</h1>
<!--p class="">Showing Search Results for: <strong>"bikram yoga"</strong></p-->
              
        <!-- search panel start here -->
        <div class="search-panel">
            <?php echo $this->Form->create("Event", array("action" => "myWpplugin", "name" => "search_form")); ?>
 <div class="sp-bottom wp-select"><?php echo $this->Form->input("Event.list", array('type' => 'select', 'options' => array( "2" => "All Events", "1" => "Events I added in Wp-Plugin", "3" => "Events I am not added in Wp-Plugin"), 'div' => false, 'label' => false, "onchange" => "javascript:document.search_form.submit();")); ?>
 </div>
            <h1>Search and Filtering
                <a href="javascript:void(0);" class="bn-hide-show"><?php echo $txt; ?></a>
            </h1>
            <div class="sp-inner">
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
                echo $this->html->link('Clear Search', array('controller' => 'Events', 'action' => 'myWpplugin'), array("class" => "clear-search"));
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
                   <?php echo $this->Form->input("Event.event_show", array('type' => 'select', 'options' => array("all" => "All Events", "ALH" => "ALH Events", "FB" => "Facebook Events", "EF" => "Eventful Events"), 'div' => false, 'label' => false, "onchange" => "javascript:document.search_form.submit();", "class" => "float-right")); ?>
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
                             <?php
                         $viewDetail = '/Events/viewEvent/'.base64_encode($event["Event"]["id"]);
                        if($event["Event"]["event_from"]=="facebook")
                        {
                          $imgPath='';  
                        }
                        elseif($event["Event"]["event_from"]=="eventful")
                        {
                          $imgPath='';
                          $viewDetail = '/Events/viewEventfulEvent/' . $event["Event"]["ef_event_id"];
                        }
                        else
                        {
                        $imgPath = "EventImages/small/";
                        }
                        ?>
                                    <?php echo $this->html->image($imgPath . $event["Event"]["image_name"], array('class' => 'event_img', 'width' => '120px', 'height' => '100px')); ?>
                            <div class="event-short-des">
                                <a href="<?php echo $viewDetail; ?>"><h2><?php echo $event["Event"]["title"]; ?></h2></a>
                                <p><?php echo $event["Event"]["sub_title"]; ?></p>
                                <p> <?php echo $event["Event"]["event_location"]; ?></p>
                                <p>
                                <?php
                                $n = 1;
                                foreach ($event["EventDate"] as $ed) {
                                    if($n==1)
                                    {
                                    echo date('l, F d, Y', strtotime($ed["date"])) . "  " . date('h:i A', strtotime($ed['start_time'])) . " - " . date('h:i A', strtotime($ed['end_time'])) . "<br>";
                                    }                              
                               $n++; } ?>
                                <?php echo $this->html->link("Click to know Event Schedule.", $viewDetail, array("class" => "")); ?>
                                   
                                </p>
                                <p><?php
                            
                            if(isset($event["Event"]["ticket_price"])) { echo "Ticket Cost: "; echo $event["Event"]["ticket_price"]; } 
                            ?></p>
                            </div>
                            <div class="clear"></div>
                            <div class="event-dv-content">
                                <div class="para-ed"><?php echo substr(strip_tags($event["Event"]['short_description']), 0, 300) . '...'; ?></div>
                               <div class="view-detail">
                                <?php echo $this->html->link("View Details", $viewDetail, array("class" => "")); ?>
                               </div>
                                <?php
                               
                                  $log_user = $this->Session->read('Auth.User');
                                 
                                ?>
                                 
                                
                                <?php
                                 if(isset($log_user['role_id']) && $log_user['role_id']==4)
                                  {
                                
                                  ?>
                                  <div class="add-cal">
                                <a class="btn-addToCal"  href="javascript:void(0);" onclick="add_to_mywpplugin(<?php echo $event['Event']['id']; ?>)"><span id = "wp<?php echo $event["Event"]["id"]; ?>"><?php
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
                        <?php echo $this->html->link("View Event", $viewDetail, array("class" => "btn-addToCal btn-viewEvent")); ?>
<?php  $k++;
   
    if($k%2==0)
    {
        echo '<br>';
        
    } ?>
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