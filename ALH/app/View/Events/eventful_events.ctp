<?php
#pr($within);die;  

if (isset($this->data["cat"]) || (isset($this->data["Event"]["keyword"]) && !empty($this->data["Event"]["keyword"]))) {
    $clss = "show-hide-panel";
    $txt = "Hide";
} else {
    $clss = "";
    $txt = "Show";
}

?>

<?php
$counter = '';
$adjacents = 3;
$targetpage = ""; //your file name
$limit = $eevent->page_size; //how many items to show per page
if(isset($_GET['pages']))
        {
        $pages = $_GET['pages'];
        }
        else
        {
            $pages = "";
        }
$total = $eevent->total_items;

if($pages){ 
$start = ($pages - 1) * $limit; //first item to display on this page
}else{
$start = 0;
}

/* Setup page vars for display. */
if ($pages == 0) $page = 1; //if no page var is given, default to 1.
$prev = $pages - 1; //previous page is current page - 1
$next = $pages + 1; //next page is current page + 1
$lastpage = ceil($total/$limit); //lastpage.
$lpm1 = $lastpage - 1; //last page minus 1


/* CREATE THE PAGINATION */

if(!isset($usr_id))
{

$pagination = "";
if($lastpage > 1)
{ 
$pagination .= "<div class='pagination1 paginate-list'> <ul>";
if ($pages > $counter+1) {
$pagination.= "<li><a href=\"$targetpage?pages=$prev\">prev</a></li>"; 
}

if ($lastpage < 7 + ($adjacents * 2)) 
{ 
for ($counter = 1; $counter <= $lastpage; $counter++)
{
if ($counter == $pages)
$pagination.= "<li><a href='#' class='active'>$counter</a></li>";
else
$pagination.= "<li><a href=\"$targetpage?pages=$counter\">$counter</a></li>"; 
}
}
elseif($lastpage > 5 + ($adjacents * 2)) //enough pages to hide some
{
//close to beginning; only hide later pages
if($pages < 1 + ($adjacents * 2)) 
{
for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
{
if ($counter == $pages)
$pagination.= "<li><a href='#' class='active'>$counter</a></li>";
else
$pagination.= "<li><a href=\"$targetpage?pages=$counter\">$counter</a></li>"; 
}
$pagination.= "<li>...</li>";
$pagination.= "<li><a href=\"$targetpage?pages=$lpm1\">$lpm1</a></li>";
$pagination.= "<li><a href=\"$targetpage?pages=$lastpage\">$lastpage</a></li>"; 
}
//in middle; hide some front and some back
elseif($lastpage - ($adjacents * 2) > $pages && $pages > ($adjacents * 2))
{
$pagination.= "<li><a href=\"$targetpage?pages=1\">1</a></li>";
$pagination.= "<li><a href=\"$targetpage?pages=2\">2</a></li>";
$pagination.= "<li>...</li>";
for ($counter = $pages - $adjacents; $counter <= $pages + $adjacents; $counter++)
{
if ($counter == $pages)
$pagination.= "<li><a href='#' class='active'>$counter</a></li>";
else
$pagination.= "<li><a href=\"$targetpage?page=$counter\">$counter</a></li>"; 
}
$pagination.= "<li>...</li>";
$pagination.= "<li><a href=\"$targetpage?pages=$lpm1\">$lpm1</a></li>";
$pagination.= "<li><a href=\"$targetpage?pages=$lastpage\">$lastpage</a></li>"; 
}
//close to end; only hide early pages
else
{
$pagination.= "<li><a href=\"$targetpage?pages=1\">1</a></li>";
$pagination.= "<li><a href=\"$targetpage?pages=2\">2</a></li>";
$pagination.= "<li>...</li>";
for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; 
$counter++)
{
if ($counter == $pages)
$pagination.= "<li><a href='#' class='active'>$counter</a></li>";
else
$pagination.= "<li><a href=\"$targetpage?pages=$counter\">$counter</a></li>"; 
}
}
}

//next button
if ($pages < $counter - 1) 
$pagination.= "<li><a href=\"$targetpage?pages=$next\">next</a></li>";
else
$pagination.= "";
$pagination.= "</ul></div>\n"; 
}
}
//pr($catList);die;
?>



  <div class="center-block">
        <div class="em-sec">
			<h1>Eventful Events</h1>
           
            <!-- search panel start here -->
            <div class="search-panel">
                 <?php echo $this->Form->create("Event", array("action" => "eventfulEvents", "name" => "search_form")); ?>
    
            	<h1>Search and Filtering
                	<a href="javascript:void(0);" class="bn-hide-show"><?php echo $txt; ?></a>
                </h1>
                <div class="sp-inner">
                	<ul class="sp-hide-content <?php echo $clss; ?>">
                    	<li>
                        	<label>Find an Event:</label>
                           <?php echo $this->Form->input("Event.keyword", array('type' => 'text', 'div' => false, 'label' => false, "placeholder" => "Event Title")); ?>
  
                         
                        </li>
                         <li>
                            <label>Event Categories: &nbsp;</label>
                            <p>
                              
                               <?php
                                foreach($catList['category'] as $CLists):
                                    $cIDs = $CLists['id'];
                                
                                        if (isset($this->data["cat"][$cIDs]) && $this->data["cat"][$cIDs] == "on") {
                                             $Catl[] = $CLists['name'];
                                        }
                                       endforeach;
                                       if(isset($Catl))
                                       {
                                       echo substr(implode(', ', $Catl), 0, 50) . '...';
                                       }
                                       ?>
                              
                            </p>
                            <a href="javascript:void(0);" class="show-more-categories">More+</a>
                            <div class="categories-more-option">
                                <?php
                                foreach($catList['category'] as $CList):
                                    $cID = $CList['id'];
                                    ?>
                                    <label><input type="checkbox" name="cat[<?php echo $CList['id']; ?>]"  <?php
                                        if (isset($this->data["cat"][$cID]) && $this->data["cat"][$cID] == "on") {
                                            echo 'checked';
                                        }
                                        ?>><?php echo $CList['name']; ?></label>
                                    <?php endforeach; ?>
                            </div>

                        </li>
                       <li>
                        <?php
                         echo $this->Form->input("Search", array('type' => 'submit', 'div' => false, 'label' => false, "onclick" => "javascript:document.search_form.submit();"));
                         echo $this->html->link('Clear Search', array('controller' => 'Events', 'action' => 'eventfulEvents'), array("class" => "clear-search"));
                        ?>
                       </li>
                       <li>&nbsp;</li>
                        <div class="clear"></div>
                    </ul>
                </div>
                <div class="sp-bottom">
                                        
                    <?php echo $this->Form->input("Event.date", array('type' => 'select', 'empty' => 'Select date range', 'options' => array("Today" => "Today", "This Week" => "This week", "Next week" => "Next week", "Last Week" => "Last Week", "Future" => "Future", "Past" => "Past", "all" => "All"), 'div' => false, 'label' => false, "onchange" => "javascript:document.search_form.submit();")); ?>
 
                    
                    <!--div class="paginate-list" >
                    	<span>Show</span>
                        <a href="#" class="active">10</a>
                        <a href="#">20</a>
                        <a href="#">40</a>
                        <span>Per Page</span>
                    </div-->
                    
                    <select class="ld-view">
                        <option selected="" value="Detailed View">Detailed View</option>
                        <option value="List View">List View</option>
                    </select>
                    <div class="clear"></div>
                </div>
                <div class="sp-cl">
                	<p>Now showing events within <?php echo $within; ?> miles of <?php echo $scity; ?>.</p>
                    <div class="findByNum" style="display:none">
                    	<span>Within</span>
                           <?php echo $this->Form->input("Event.distance", array('type' => 'select', 'empty' => 'Select All','selected'=>$within, 'options' => array("10" => "10", "20" => "20", "30" => "30", "50" => "50", "100" => "100"), 'div' => false, 'label' => false)); ?>
                           

                        
                        <span>Mile(s) of</span>
                          <?php echo $this->Form->input("Event.city", array('type' => 'text', 'div' => false, 'label' => false, "placeholder" => "Change Location, Enter ZipCode.")); ?>
  
                        <?php echo $this->Form->input("Go", array('type' => 'submit', 'div' => false, 'label' => false, "onclick" => "javascript:document.search_form.submit();")); ?>
                    </div>
                    <a href="javascript:void(0);" class="btn-changeLoc" style="display:inline-block;">Change Location</a>
                  <?php echo $this->Form->input("Event.event_show", array('type' => 'select','selected'=>"EF", 'options' => array("all"=>"All Events","ALH" => "ALH Events", "FB" => "Facebook Events", "EF" => "Eventful Events"), 'div' => false, 'label' => false, "onchange" => "javascript:document.search_form.submit();", "class"=> "float-right")); ?>
      
                </div>
                  
                <?php echo $this->Form->end(); ?>
                <!-- event container start here -->
                <div class="event-container">
                    <?php
        $i = 1;
        foreach ($eevent->events->event as $event) {
     
             $i++;
            if ($i % 2 == 0)
            {
                $cls = "left-side";
            }
            else
            {
                $cls = "right-side";
            }
            
            ?>
            
              <?php
                             $er = json_decode(json_encode($event), true);
                            $evnt_id = $er['@attributes']['id'];
                            $evnt_id = str_replace("@", "_", $evnt_id);
                            ?>
                    <div class="event-box <?php echo $cls; ?>">
                        <?php if(isset($event->image->url))
                        { ?>
                        <img src="<?php echo $event->image->url; ?>" class="event_img" width="120px" height="100px" >
                         <?php } else {
                            echo $this->html->image("no_image.jpeg", array('class'=>'event_img','width'=>'120px','height' =>'100px'));
                         } ?>
                     
                        <div class="event-short-des">
                            <h2>
                              <?php echo $this->html->link($event->title, array("controller" => "Events", "action" => "viewEventfulEvent",$evnt_id ), array("class" => "", "target" => "_blank")); ?>
                         
                           </h2>
                            <p> <?php echo $event->venue_name.', '.$event->venue_address.' '.$event->city_name.', '.$event->country_name; ?></p>
                            <p><?php echo date("l jS \of F Y h:i:s A", strtotime($event->start_time)); ?></p>
                            
                        </div>
                        <div class="clear"></div>
                        <div class="event-dv-content">
                          
                            <div class="para-ed"><?php echo substr(strip_tags($event->description),0,250).'...';  ?></div>
                          <div class="view-detail">
                            <?php echo $this->html->link("View Details", array("controller" => "Events", "action" => "viewEventfulEvent",$evnt_id ), array("class" => "", "target" => "_blank")); ?>
                          </div>
                            <?php
                            if (!$this->Session->read('Auth.User')) {
                                $ifLog = 'data-target="#sign_in" data-toggle="modal"';
                            } else {
                                $ifLog = '';
                            }
                            $log_user = $this->Session->read('Auth.User');
                            
                            
                          

                            ?>
<div class="add-cal">
                            <a class="btn-addToCal" href="javascript:void(0);" <?php echo $ifLog; ?> onclick="add_to_mycalender_ef('<?php echo $evnt_id; ?>')"><span id = "<?php echo $evnt_id; ?>"><?php
                                  //$evnt_ids = str_replace("_", " ", $evnt_id);
                                 //echo $evnt_id;
                               // echo "<pre>"; print_r($my_calendar); echo "</pre>";
                                    if (in_array($evnt_id, $my_calendar))
                                        echo "-Remove from My Calendar";
                                    else
                                        echo "+Add to My Calendar";
                                    ?></span></a>
                            <?php
                            if (isset($log_user['role_id']) && $log_user['role_id'] == 4) {
                                ?>
                                <a class="btn-addToCal" style="margin-right:10px" href="javascript:void(0);" <?php echo $ifLog; ?> onclick="add_to_mywpplugin_ef('<?php echo $evnt_id; ?>')"><span id = "wp<?php echo $evnt_id; ?>"><?php
                                if (in_array($evnt_id, $my_wpplugin))
                                    echo "Remove From Wp-Plugin";
                                else
                                    echo "+Add To Wp-Plugin";
                                ?></span></a>
                                        <?php
                                    }
                                    ?>
                            <span class="loader" id="load_<?php echo $evnt_id; ?>" style="display: none;"><img src="/img/admin/loader.gif" alt=""></span>

</div>
                         
                        </div>
                        <?php echo $this->html->link("View Event", array("controller" => "Events", "action" => "viewEventfulEvent",$evnt_id), array("class" => "btn-addToCal mrgtop  btn-viewEvent", "target" => "_blank")); ?>
                       
                    </div>
                    <?php
                    }
                    ?>
                   
                  
                
                </div>
                <div class="clear"></div>
                <div class="evnt-pagn">
                	<span><?php
echo $pagination; 
?>		</span>
                    
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
$(document).ready(function(){
        $('.bn-hide-show').click(function(){
                $('.sp-hide-content').toggleClass('show-hide-panel');
                $(this) .text( ($(this).text() == 'Show' ? 'Hide' : 'Show') )
               
        });
        
        $('.show-more').click(function(){
                $('.more-option').toggleClass('show-more-option');
        });
        
        $('.btn-changeLoc').click(function(){
                $('.findByNum').css('display','block');
                $('.btn-changeLoc').css('display','none');
        });
        
        $('.ld-view').change(function(){
                $('.event-container .event-box').toggleClass('event-list-view');
        });
         $('.show-more-vibes').click(function(){
                $('.vibes-more-option').toggleClass('show-more-option');
        });
          $('.show-more-categories').click(function(){
                $('.categories-more-option').toggleClass('show-more-option');
        });
          
});
	</script>