<?php
#pr($lists);
?>
<?php
if (isset($_POST['data']['listCategory']) || isset($_POST['data']['listVibe']) || (isset($_POST['data']['MyList']['title']) && !empty($_POST['data']['MyList']['title']))) {
    $clss = "show-hide-panel";
    $txt = "Hide";
} else {
    $clss = "";
    $txt = "Show";
}
?>

<div class="center-block">
    <div class="em-sec">
        <h1>Available Public Lists</h1>
<!--p class="">Showing Search Results for: <strong>"bikram yoga"</strong></p-->
        <!-- search panel start here -->
        <div class="search-panel">
            <?php echo $this->Form->create("MyLists", array("action" => "premiumList", "name" => "search_form")); ?>

            <h1>Search and Filtering
                <a href="javascript:void(0);" class="bn-hide-show"><?php echo $txt; ?></a>
            </h1>
            <div class="sp-inner">
                <ul class="sp-hide-content <?php echo $clss; ?>" >
                    <li>
                        <label>Find an List:</label>
                        <?php echo $this->Form->input("MyList.title", array('type' => 'text', 'div' => false, 'label' => false, "placeholder" => "List Title")); ?>


                    </li>
                    <li>
                        <label>List Categories: &nbsp;</label>
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
                                <label><input type="checkbox" name="data[listCategory][id][<?php echo $category['Category']['id']; ?>]"  <?php
                                              if (isset($_POST['data']['listCategory']['id'][$cID]) && $_POST['data']['listCategory']['id'][$cID] == "on") {
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
                        <label>List Vibe: &nbsp;</label>
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
                                <label><input type="checkbox" name="data[listVibe][id][<?php echo $vibe['Vibe']['id']; ?>]" <?php
                                if (isset($_POST['data']['listVibe']['id'][$vID]) && $_POST['data']['listVibe']['id'][$vID] == "on") {
                                    echo 'checked';
                                }
                                    ?>><?php echo $vibe['Vibe']['name']; ?></label>
                        <?php endforeach; ?>
                        </div>
                    </li>
                    <li>
                <?php
                echo $this->Form->input("Search", array('type' => 'submit', 'div' => false, 'label' => false, "onclick" => "javascript:document.search_form.submit();"));
                echo $this->html->link('Clear Search', array('controller' => 'MyLists', 'action' => 'premiumList'), array("class" => "clear-search"));
                ?>

                    </li>
                    <div class="clear"></div>
                </ul>
            </div>
            <div class="sp-bottom">
                <?php echo $this->Form->input("MyList.order", array('type' => 'select', 'empty' => 'Sort by....', 'options' => array("MyList.list_name ASC" => "Name A-Z", "MyList.list_name DESC" => "Name Z-A"), 'div' => false, 'label' => false, "onchange" => "javascript:document.search_form.submit();")); ?>
                 <?php echo $this->Form->input("MyList.limit", array('type' => 'hidden', "id" => "limit")); ?>
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
                <div class="paginate-list marg-left245">
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
           

                <?php echo $this->Form->end(); ?>
            <!-- event container start here -->
            <div class="event-container premiumList">
<?php
$k = 0;
foreach ($lists as $list) {
    //if (!empty($event['EventCategory']) && !empty($event['EventVibe'])) {
        ?>
                        <div class="event-box">
                                    <?php 
                                    if(isset($list["User"]["Brand"]["logo"]))
                                    echo $this->html->image("brand/small/" . $list["User"]["Brand"]["logo"], array('class' => 'event_img', 'width' => '120px', 'height' => '100px')); ?>
                            <div class="event-short-des line-hight">
                                <h2><?php echo $list["MyList"]["list_name"]; ?></h2>
                                <div class="event-dv-content">
                                <div class="para-ed">
                                   <label><b>Vibe: </b> <?php
                foreach ($list["ListVibe"] as $vibes) {
                    $Vlist[] = $vibes["Vibe"]["name"];
                                      
                }
              
                echo substr(implode(', ',$Vlist), 0, 70) . '...';
                ?>
            </label><br/>
            <label><b>Category: </b> <?php
                foreach ($list["ListCategory"] as $categories) {
                    $Clist[] = $categories["Category"]["name"];
                }
               
                echo substr(implode(', ',$Clist), 0, 70) . '...';
                ?>
            </label>
                            </div></div>
                                <?php
                             
                                 $RateDetail =   $this->Common->listrate($list["OpenRate"]);
                               
                                    
                                    ?>
                                <p><b>Open-Rate: </b> <?php echo $RateDetail["OpenRate"] ;?></p>
                                 <div class="event-dv-content">
                                 <p><b>Click-through rate: </b> <?php echo $RateDetail["ClickRate"]; ?></p>
                                 <p><b class="list-rgn">Region Breakdown: </b> <div class="list-rgn-break"><?php echo $RateDetail["RegionPer1"] ;?> Northeast US <br> <?php echo $RateDetail["RegionPer2"] ;?> Midwest US <br> <?php echo $RateDetail["RegionPer3"] ;?> South US <br> <?php echo $RateDetail["RegionPer4"] ;?> West US <br> <?php echo $RateDetail["RegionPer5"] ;?> Others</div></p>
                                 </div>
                                <div class="flt-left"><b><?php echo $list["MyList"]["dedicated_send_points"];?> points for dedicated send</b></div>
                                <div class="flt-left"><b><?php echo $list["MyList"]["multi_event_points"];?> points for multi-event email</b></div>
                               
                            </div>
                            <div class="clear"></div>
                            
                        <?php echo $this->html->link("Make an offer",array("controller"=>"MyLists","action"=>"makeAOffer",  base64_encode($list["MyList"]["id"])), array("class" => "btn-addToCal"));?>
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
                <span class="peginationTxt"><?php echo $this->Paginator->counter(array('format' => 'List %start% - %end% of %count%')); ?></span>
                <?php
              ;
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





