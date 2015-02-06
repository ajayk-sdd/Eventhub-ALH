
    <script>
        function dateFilter(datemode)
        {
            document.getElementById('date_filter').value = datemode;
            document.search_forms.submit();

        }
        function orderFilter(ordermode)
        {
            document.getElementById('order_filter').value = ordermode;
            document.search_forms.submit();

        }

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
        
        function SelectBox() {
             if( document.getElementById('FacebookShow').checked == false && document.getElementById('allFacebookShow').checked == false && document.getElementById('myFacebookShow').checked == false)
             {
             document.getElementById("EventEventShow").selectedIndex = "0";
             }
             else
             {
             document.getElementById('FacebookShow').checked = true;
             document.getElementById("EventEventShow").selectedIndex = "2";  
             }
        }
        
         function resetVibes(cntVibe)
         {
            for (var v=0;v<cntVibe;v++) {
            
             
                     document.getElementById('event_vibe'+v).checked = false;
              
            }
           
            document.search_forms.submit();
         }
         
           function resetCat(cntCat)
         {
           for (var c=0;c<cntCat;c++) {
            
               
                     document.getElementById('event_cat'+c).checked = false;
              
            }
           
            document.search_forms.submit();
        
         }
         
         function VibeSearch(check,id) {
            if ( document.getElementById('event_vibe'+check).checked == true) {
               
                 $.post(base_url+'/Users/vibeSearchCount', {"data[Vibe]": id}, function(data) {
                
                //alert(data);
                    
            });
                 
            }
            
         }
         
           function CatSearch(check,id) {
            if ( document.getElementById('event_cat'+check).checked == true) {
               
                 $.post(base_url+'/Users/catSearchCount', {"data[Category]": id}, function(data) {
                
                //alert(data);
                    
            });
                 
            }
            
         }
         
          function popCatSearch(id) {
          if ( document.getElementById('popcat'+id).checked == true) {
           document.getElementsByName("data[EventCategory][id]["+id+"]")[0].checked = true;
          }
          else
          {
            document.getElementsByName("data[EventCategory][id]["+id+"]")[0].checked = false;
          }
          document.search_forms.submit();
         }
         
          function popSearch(cv,id,cnt_cat,cnt_vib) {
            
             for (var v1=0;v1<cnt_vib;v1++) {
            
             
                     document.getElementById('event_vibe'+v1).checked = false;
              
            }
            
            for (var c1=0;c1<cnt_cat;c1++) {
            
               
                     document.getElementById('event_cat'+c1).checked = false;
              
            }
            
          if ( cv == '2') {
           document.getElementsByName("data[EventCategory][id]["+id+"]")[0].checked = true;
          }
          else
          {
            document.getElementsByName("data[EventVibe][id]["+id+"]")[0].checked = true;
          }
          document.search_forms.submit();
         }

    </script>
    <section class="inner-content inner-contentnew">
        <?php echo $this->Form->create("Campaigns", array("action" => "chooseEventSingle", "name" => "search_forms")); ?>
        <!--Left Section Starts-->
        <section class="lt_sctn">

            <h2 class="main_heading">search filter</h2>			
            <div class="search_events">
                <?php
                echo $this->Form->input("Event.title", array('type' => 'text', 'div' => false, 'label' => false, "placeholder" => "Search Event"));
                echo $this->Form->input("", array('type' => 'submit', 'div' => false, 'label' => false, "onclick" => "javascript:document.search_forms.submit();", "class" => "search_button"));
                ?>

            </div>
            <p class="show_events">
                <?php
                if (isset($save_from_zip)) {
                    $display = "block";
                    $chane_location = "none";
                } else {
                    $display = "none";
                    $chane_location = "inline-block";
                }
                ?>
                <?php if (isset($zip_code_name) && !isset($zip_condition)) { ?>
                    Now showing events within <?php echo $distance; ?> miles from <span><?php echo $zip_code_name; ?></span>.
                <?php } else if (isset($city_name) && !empty($city_name) && !isset($zip_condition)) {
                    ?>
                    Showing all events around <span><?php echo $city_name; ?></span>.
                <?php } else {
                    ?>
                <p style="color:red;">Excluding zip code from search result, as it is seems to be wrong.</p>
                <?php
            }
            ?>
            <a href="javascript:changeLocOpen();"  id="btn_zipfilter" class="btn-zipfilter" style="display:<?php echo $chane_location; ?>;">Change Location</a>
            </p>

            <div class="zipLocation">
                <div class="findByNum" id="findByNum" style="display:<?php echo $display; ?>">
                    <span>Within</span>
                    <?php echo $this->Form->input("Event.distance", array('type' => 'select', 'empty' => 'Select All', 'options' => array("5" => "5", "10" => "10", "15" => "15", "20" => "20", "25" => "25", "30" => "30", "50" => "50", "100" => "100"), 'div' => false, 'label' => false)); ?>
                    <?php echo $this->Form->input("Event.lat_long", array("type" => "hidden", "id" => "lat_long")); ?>


                    <span>Mile(s) of</span>
                    <div class="search_events"><?php echo $this->Form->input("Event.zip", array('type' => 'text', 'div' => false, 'label' => false, "placeholder" => "Change location, enter zip.", "maxlength" => 6)); ?>

                        <?php echo $this->Form->input("", array('type' => 'submit', 'div' => false, 'label' => false, "onclick" => "javascript:document.search_forms.submit();", "class" => "search_button")); ?>
                    </div>
                    <p class="show_events">   <a href="javascript:changeLocClose();" class="btn-zipfilter-cancel">Cancel</a></p>

                </div>
            </div>


            <h3>by date</h3>
            <ul class="link_listing">
                <?php
                if (isset($this->data['Event']['date']) && !empty($this->data['Event']['date'])) {
                    $date_value = $this->data['Event']['date'];
                } else {
                    $date_value = '';
                }

                echo $this->Form->input("Event.date", array('type' => 'hidden', 'div' => false, 'label' => false, 'value' => $date_value, 'id' => 'date_filter'));
                ?>

                <li><a  href="javascript:dateFilter('today');" id='today' >Today</a></li>
                <li><a  href="javascript:dateFilter('week');" id="week">This week</a></li>
                <li><a  href="javascript:dateFilter('month');" id="month">This month</a></li>
                <li><a  href="javascript:dateFilter('year');" id="year">This year</a></li>
                <li><a  href="javascript:dateFilter('all');" id="all">All</a></li>
            </ul>
            <h3>Popular Searches</h3>
            <ul class="link_listing">

                <?php
                $popular_cat2 = 0;
                $cnt_vib = count($vibes);
                $cnt_cat = count($categories);
                foreach ($pop_categories as $pop_cat2):
                    $pcID2 = $pop_cat2['Category']['id'];
                    ?>
                    <li><a <?php
                      if (isset($this->data['EventCategory']['id'][$pcID2]) && $this->data['EventCategory']['id'][$pcID2] == "on") {
                           echo 'style="font-weight:bold"';
                        }
                        ?> href="javascript:popSearch(<?php echo "2".','.$pcID2.','.$cnt_cat.','.$cnt_vib; ?>)" id="popcats<?php echo $pcID2; ?>"><?php echo $pop_cat2['Category']['name']; ?></a></li>
                    <?php $popular_cat2++; endforeach;
                    
                    foreach ($pop_vibes as $pop_vibe):
                    $pvID = $pop_vibe['Vibe']['id'];
                    ?>
                    <li><a <?php
                      if (isset($this->data['EventVibe']['id'][$pvID]) && $this->data['EventVibe']['id'][$pvID] == "on") {
                            echo 'style="font-weight:bold"';
                        }
                        ?> href="javascript:popSearch(<?php echo "1".','.$pvID.','.$cnt_cat.','.$cnt_vib; ?>)" id="popvib<?php echo $pvID; ?>"><?php echo $pop_vibe['Vibe']['name']; ?></a></li>
                    <?php endforeach; ?>
               </ul>
            
             <h3>By Order</h3>
            <ul class="link_listing">

                <?php
                
                if (isset($this->data['Event']['order']) && !empty($this->data['Event']['order'])) {
                    $order_value = $this->data['Event']['order'];
                } else {
                    $order_value = '';
                }
                echo $this->Form->input("Event.order", array('type' => 'hidden', 'div' => false, 'label' => false, 'value' => $order_value, 'id' => 'order_filter'));
                ?>

                <li><a  href="javascript:orderFilter('Event.title ASC');" id='Event_title_ASC' >Name A-Z</a></li>
                <li><a  href="javascript:orderFilter('Event.title DESC');" id="Event_title_DESC">Name Z-A</a></li>
                <li><a  href="javascript:orderFilter('Event.start_date ASC');" id="start_date_ASC">Date: Soonest</a></li>
                <li><a  href="javascript:orderFilter('Event.created DESC');" id="created_DESC">Date: Lattest</a></li>
            </ul>
            
            <h3>by type</h3>
            <ul class="checkbox_listing">
                <li>
                    <input type="checkbox" name="data[FacebookShow]" value="1"
                    <?php if (isset($this->data['FacebookShow']) && $this->data['FacebookShow']==1) { echo "checked"; }?> id="FacebookShow" onclick="javascript:SelectBox();document.search_forms.submit();"/>
                    <label>FaceBook</label>
                </li>	
                <li class="inner_list">
                   <input type="checkbox" name="data[allFacebookShow]" value="1"
                   <?php if (isset($this->data['allFacebookShow']) && $this->data['allFacebookShow']==1) { echo "checked"; }?> id="allFacebookShow" onclick="javascript:SelectBox();document.search_forms.submit();"/>
                    <label>All FaceBook</label>
                </li>	
                <li class="inner_list">
                    <input type="checkbox" name="data[myFacebookShow]" value="1"
                     <?php if (isset($this->data['myFacebookShow']) && $this->data['myFacebookShow']==1) { echo "checked"; }?> id="myFacebookShow" onclick="javascript:SelectBox();document.search_forms.submit();"/>
                    <label>My FaceBook</label>
                </li>
                
                <li>
                    <h4>Popular</h4>
                </li>
                <?php
                $popular_cat = 0;
                foreach ($pop_categories as $pop_cat):
                    $pcID = $pop_cat['Category']['id'];
                    ?>
                    <li><input type="checkbox" name="data['popsearchcat'][<?php echo $pcID; ?>]" <?php
                      if (isset($this->data['EventCategory']['id'][$pcID]) && $this->data['EventCategory']['id'][$pcID] == "on") {
                            echo 'checked';
                        }
                        ?> onclick="javascript:popCatSearch(<?php echo $pcID; ?>)" id="popcat<?php echo $pcID; ?>"><label><?php echo $pop_cat['Category']['name']; ?></label></li>
                    <?php $popular_cat++; endforeach; ?>

                <li>
                    <h4>Vibes</h4>
                </li>
                <?php
                $vi = 0;
                foreach ($vibes as $vibe):
                    $vID = $vibe['Vibe']['id'];
                    ?>
                    <li><input type="checkbox" name="data[EventVibe][id][<?php echo $vibe['Vibe']['id']; ?>]" <?php
                        if (isset($this->data['EventVibe']['id'][$vID]) && $this->data['EventVibe']['id'][$vID] == "on") {
                            echo 'checked';
                        }
                        ?> onclick="javascript:VibeSearch(<?php echo $vi.','.$vID; ?>),document.search_forms.submit();" id="event_vibe<?php echo $vi; ?>"><label><?php echo $vibe['Vibe']['name']; ?></label></li>
                    <?php $vi++; endforeach; ?>

                <li>
                     <?php
                     $cnt_vib = count($vibes);
                     echo $this->html->link("Reset Vibes", "javascript:resetVibes($cnt_vib);", array("class" => "reset-cat-vib")); ?>
                </li>
                <li>
                    <h4>Categories</h4>
                </li>

                <?php $ca = 0;
                foreach ($categories as $category):
                    $cID = $category['Category']['id'];
                    ?>
                    <li><input type="checkbox" name="data[EventCategory][id][<?php echo $category['Category']['id']; ?>]"  <?php
                        if (isset($this->data['EventCategory']['id'][$cID]) && $this->data['EventCategory']['id'][$cID] == "on") {
                            echo 'checked';
                        }
                        ?> onclick="javascript:CatSearch(<?php echo $ca.','.$cID; ?>),document.search_forms.submit();" id="event_cat<?php echo $ca; ?>" class="cat<?php echo $cID; ?>"><label><?php echo $category['Category']['name']; ?></label></li>
                    <?php $ca++; endforeach; ?>

                      <li>
                     <?php
                      $cnt_cat = count($categories);
                     echo $this->html->link("Reset Categories", "javascript:resetCat($cnt_cat);", array("class" => "reset-cat-vib")); ?>
                </li>
                      
            </ul>
<?php echo $this->Form->input("Event.limit", array('type' => 'hidden', "id" => "limit")); ?>
        </section>  	
        <!--Left Section Ends-->
        <!--Middle Section Starts-->
        <section class="middle_section">
            
            <div class="alist_heading" style="padding: 0px;">
                <h2 class="main_heading">ALIST Calendar / FaceBook</h2>
                <?php echo $this->Form->input("Event.event_show", array('type' => 'select', 'options' => array("all" => "All Events", "ALH" => "ALH Events", "FB" => "Facebook Events"), 'div' => false, 'label' => false, "onchange" => "javascript:document.search_forms.submit();", "class" => "float-right")); ?>
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
                            <section class="rcmnded_events">


                                <?php
                                if ($event["Event"]["event_from"] == "facebook") {
                                    $imgPath = '';
                                    $event_icon = 'fb_btn.png';
                                } else {
                                    $imgPath = "EventImages/small/";
                                    $event_icon = 'listhub_icon.png';
                                }
                                ?>

                                <a href="#" class="listhub_icon"><img src="img/<?php echo $event_icon; ?>" alt=""/></a>
                                <?php
                                if (isset($event["Giveaway"]) && $event["Giveaway"]["id"] != '') {
                                    ?> 
                                    <a href="#" class="listhub_icon1"><img src="img/ticon.png" alt=""/></a>
                                <?php }
                                ?>


                                <span class="dummy_thumb">
                                    <?php echo $this->html->image($imgPath . $event["Event"]["image_name"], array('class' => 'event_img', 'width' => '120px', 'height' => '100px')); ?>
                                </span>	
                                <section class="events_info">
                                    <a href="/Events/viewEvent/<?php echo base64_encode($event["Event"]["id"]); ?>"><h3><?php echo $event["Event"]["title"]; ?></h3></a>
                                    <label><?php
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
                                        ?></label>
                                    <span><?php echo $event["Event"]["specify"] . ', ' . $event["Event"]["cant_find_city"] . ', ' . $event["Event"]["cant_find_state"]; ?></span>
                                    <div class="detailEvent"> <div class="event-dv-content"><?php echo substr(strip_tags($event["Event"]['short_description']), 0, 100) . '...'; ?></div></div>
                                </section>
                            </section>   	     
                        </li>
                        <?php
                    }
                } else {
                    echo "<center>No Event Found.</center>";
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
        <?php echo $this->Form->end(); ?>
        
        <div class="clear"></div>
    </section>


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
<?php
if (isset($this->data['Event']['order']) && !empty($this->data['Event']['order'])) {
    $order_event = str_replace(' ', '_', str_replace('.', '_', $this->data['Event']['order']));
} else {
    $order_event = '';
}
?>
<script>
    $(document).ready(function() {

        $('#<?php echo $this->data['Event']['date']; ?>').css('font-weight', 'bold');

        $('#<?php echo $order_event; ?>').css('font-weight', 'bold');

        $('.bn-hide-show').click(function() {
            $('.sp-hide-content').toggleClass('show-hide-panel');
            $(this).text(($(this).text() == 'Show' ? 'Hide' : 'Show'))

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

    });

    function setLimit(limit) {
        $("#limit").val(limit);
        document.search_forms.submit();
    }
</script>