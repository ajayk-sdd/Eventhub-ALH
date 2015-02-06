<?php
#pr($event);
?>


<div class="center-block">
    <div class="em-sec">
        <br> <br>

        <div class="clear"></div>
        <div class="em-sec-inner" style="margin-top:20px; width: 100%;">
            <form name="event-detail" class="event-form" action="#" method="post">
                <div class="emsi-part em-pa-lt confirm-event-left">
                    <div class="ev-des-box event-pemp ">
                        <h1 class="event-title"><?php echo $event["title"]; ?></h1>
                      
                        <span><?php
                         
                                    echo "<span style='text-transform:none;'>";
                                    echo "Start Time: ".date('l, F d, Y G:i A', strtotime($event["start_time"]));
                                    if(!empty($event["stop_time"]))
                                    {
                                    echo "<br>End Time: ".date('l, F d, Y G:i A', strtotime($event["stop_time"]));
                                    }
                                    echo "</span>";
                               
                            ?></span>
                        <p><?php
                      
                        if(is_string($event["description"]))
                        {
                        echo substr(strip_tags($event["description"]),0,250).'...';
                        }
                        ?></p>
                        <span>Cost($): <?php
                        
                            if (!empty($event["price"])) {
                              
                                   echo $event["price"]; 
                             
                            }
                            else
                            {
                            echo "N/A";
                            }
                            ?></span>
                        <?php if (!empty($event["links"]["link"])) {
                            if(isset($event["links"]["link"]['type']) && $event["links"]["link"]['type']=="Tickets")
                            {
                                $TUrl = $event["links"]["link"]['url'];
                            }
                            else
                            {
                                $TUrl = $event['url'];
                            }
                            echo $this->Html->link('Buy Tickets', $TUrl, array("target"=>"_blank", "class"=>"btn-buyTicket"));
                            }
                            ?>
                        <?php
                        if (!empty($event["url"]))
                        {
                            echo $this->Html->link('Event Website', $event['url'],array("target"=>"_blank", "class"=>"btn-eventWeb"));
                        }
                        ?>
                    </div>

                    <div class="ev-des-box map-detail-box">
                        <div class="map-content">
                            <address>
                                <?php
                               
                                echo "<b>" . $event["venue_name"] . "</b><br/>";
                                
                                echo $event["city"];
                                 if(is_string($event["postal_code"]))
                        {
                                echo " , " . $event["postal_code"];
                        }
                                echo "<br/>";
                            
                                ?>
                            </address>
                        </div>
                        <div class="event-map">

                            <div class = "map">
                               <div id="googleMap"></div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="ev-des-box">
                        <p><?php
                          if(is_string($event["description"]))
                        {
                        echo $event["description"];
                        }
                        
                        ?></p>
                        
                            <h3>Event Categories:</h3>
                            <p><?php 
                            if(isset($event['categories']['category'][0]['name']))
                            {
                            foreach($event['categories']['category'] as $CatList)
                            {
                                $ListCat[] = $CatList['name'];
                            }
                            echo implode(", ",$ListCat);
                            }
                            else
                            {
                                echo $event['categories']['category']['name'];
                                
                            }
                            ?></p>

                           
                       </div>

                </div>
                <div class="emsi-part confirm-event-right" >
                   
                    <div class="social-share-box">

                        <span class='st_sharethis_large' displayText='ShareThis'></span>
                        <span class='st_facebook_large' displayText='Facebook'></span>
                        <span class='st_googleplus_large' displayText='Google +'></span>
                        <span class='st_twitter_large' displayText='Tweet'></span>
                        <span class='st_linkedin_large' displayText='LinkedIn'></span>
                        <span class='st_pinterest_large' displayText='Pinterest'></span>
                        <span class='st_email_large' displayText='Email'></span>
                       
                    <?php
                        $evnt_id = $event['@attributes']['id'];
                        $evnt_id = str_replace("@", "_", $evnt_id);
                       
                        if(isset($_SESSION['Auth']['User']['role_id']) && $_SESSION['Auth']['User']['role_id']==1)
                        {
                       echo $this->html->link("Add to ALH ", "javascript:void(0)", array("class" => "btn-addEvent","onclick" => "add_to_alh_ef('".$evnt_id."');"));
                        }
                       ?>
                     
                     
                    </div>
                    <div class="loader" id="load_<?php echo $evnt_id; ?>" style="margin-top: -10px; margin-right: 10px;display: none;"><img src="/img/admin/loader.gif" alt=""></div>
                    <div id="alhmsg" style="display: none;"></div>
                    
                    <div style="clear:both;">&nbsp;</div>
                    <div class="gallery" style="margin-top:10px;text-align: center;">
                    <?php
                            
                        if(!empty($event["images"]))
                        {
                            if(isset($event["images"]["image"][0]))
                                {
                                    $img = $event["images"]["image"][0]["medium"]["url"];    
                                }
                                else
                                {
                                    $img = $event["images"]["image"]["medium"]["url"];
                                }
                        }
                        else
                        {
                            $img = "/img/no_image.jpeg";
                        }
                            ?>
                       
                        <!-- Elastislide Carousel -->
                      <?php
                        if(!empty($event["images"]))
                        {
                       echo $this->html->image($img);
                        }
                        ?>  
                        <!-- End Elastislide Carousel -->

                    </div>
    </div>
                <div class="clear"></div>
                <br>


            </form>
        </div>
        <div class="clear"></div>

    </div>
</div>







<script>
    $('.bxslider').bxSlider({
        pagerCustom: '#bx-pager'
    });
</script>

<script>
    var lat = "<?php echo $event["latitude"]; ?>";
    var lng = "<?php echo $event["longitude"]; ?>";
    // function define in custom js and google js calls in frontend layout
    getmapdata(lat, lng);
</script>
<script type="text/javascript" src="/js/jquerypp.custom.js"></script>
<script type="text/javascript" src="/js/jquery.elastislide.js"></script>
<script type="text/javascript">

    // example how to integrate with a previewer

    var current = 0,
            $preview = $('#preview'),
            $carouselEl = $('#carousel'),
            $carouselItems = $carouselEl.children(),
            carousel = $carouselEl.elastislide({
                current: current,
                minItems: 4,
                onClick: function(el, pos, evt) {

                    changeImage(el, pos);
                    evt.preventDefault();

                },
                onReady: function() {

                    changeImage($carouselItems.eq(current), current);

                }
            });

    function changeImage(el, pos) {

        $preview.attr('src', el.data('preview'));
        $carouselItems.removeClass('current-img');
        el.addClass('current-img');
        carousel.setCurrent(pos);

    }

</script>