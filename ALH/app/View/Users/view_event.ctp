<?php
//pr($event);
echo $this->Html->css('front/gallery');
?>


<div class="center-block">
    <div class="em-sec">
        <br> <br>

        <div class="clear"></div>
        <div class="em-sec-inner viewevent-inner" style="margin-top:20px;">
            <form name="event-detail" class="event-form" action="#" method="post">
                <div class="emsi-part em-pa-lt confirm-event-left viewevntlt-sctn">
                    <div class="ev-des-box event-pemp ">
                        <h1 class="event-title"><?php echo $event["Event"]["title"]; ?></h1>
                        <h2 class="event-subtitle"><?php echo $event["Event"]["sub_title"]; ?></h2>
                        <span><?php
                            if (!empty($event["EventDate"])) {

                                foreach ($event["EventDate"] as $ed) {
                                    echo "<span style='text-transform:none;'>";
                                    echo date('l, F d, Y', strtotime($ed["date"]));
                                    if (!empty($ed["start_time"])) {
                                        echo "    " . date('g:i A', strtotime($ed['start_time']));
                                    } if (!empty($ed["end_time"])) {
                                        echo " - " . date('g:i A', strtotime($ed['end_time']));
                                    }
                                    echo "</span><br/>";
                                }
                            }
                            ?></span>
                        <p><?php echo $event["Event"]["short_description"]; ?></p>
                        <span><?php
                            if (!empty($event['TicketPrice'])) {
                                $str = "";
                                foreach ($event['TicketPrice'] as $tcktPrice):
                                    $tp = str_replace("$", "", $tcktPrice['ticket_price']);
                                    $str .= $tcktPrice['ticket_label'] . ' $' . $tp . "<br />";

                                endforeach;
                                echo $str;
                            }
                            ?></span>
                        <?php if (!empty($event["Event"]["ticket_vendor_url"])) { ?><a href="<?php echo $event["Event"]["ticket_vendor_url"]; ?>" class="btn-buyTicket" target="_blank">Buy Tickets</a><?php } ?>
                        <?php if (!empty($event["Event"]["website_url"])) { ?><a href="<?php echo $event["Event"]["website_url"]; ?>" class="btn-eventWeb" target="_blank">Event Website</a><?php } ?>
                    </div>

                    <div class="ev-des-box map-detail-box">
                        <div class="map-content">
                            <address>
                                <?php
                                echo "<b>" . $event["Event"]["specify"] . "</b><br>";
                                $event_address = explode(",", $event["Event"]["event_address"]);
                                echo "<b>" . $event_address[0] . "</b><br/>";
                                if (isset($event_address[1])) {
                                    echo $event_address[1] . "<br/>";
                                }
                                echo $event["Event"]["cant_find_city"];
                                if(!empty($event["Event"]["cant_find_state"]))
                                {
                                echo " , " . $event["Event"]["cant_find_state"];
                                }
                                echo "<br/>";
                                echo $event["Event"]["cant_find_zip_code"] . "<br>";
                                echo $event["Event"]["main_info_phone_no"];
                                ?>
                            </address>
                        </div>
                        <div class="event-map">

                            <div class = "map">
                                <?php //echo $this->html->image("map.jpg", array("style" => "width:184px; height:184px;"));      ?>
                                <div id="googleMap"></div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="ev-des-box">
                        <p><?php echo $event["Event"]["description"]; ?></p>
                        <?php
                        if ($event["Event"]["event_from"] != "facebook") {
                            ?>
                            <h3>Event Categories:</h3>
                            <p><?php
                                $ecat = "";
                                foreach ($event['EventCategory'] as $evt) {
                                    if (isset($evt['Category']['name']))
                                        $ecat = $evt['Category']['name'] . ",  " . $ecat;
                                }
                                echo rtrim($ecat, ',  ');
                                ?></p>
                            <h3>Event Vibes:</h3>
                            <p><?php
                                $EVibe = "";
                                foreach ($event['EventVibe'] as $evt) {
                                    if (isset($evt["Vibe"]["name"]))
                                        $EVibe = $evt['Vibe']['name'] . ",  " . $EVibe;
                                }
                                echo rtrim($EVibe, ',  ');
                                ?></p>
                            <?php
                        }
                        if (!$this->Session->read('Auth.User')) {
                            $ifLog = 'data-target="#sign_in" data-toggle="modal"';
                            $log_in = 0;
                        } else {
                            $ifLog = '';
                            $log_in = 1;
                        }
                        $log_user = $this->Session->read('Auth.User');
                        ?>
                        <a style="margin-left:0px; float: left;" class="btn-subscribe" href="javascript:void(0);" <?php echo $ifLog; ?> onclick="add_to_mycalender(<?php echo $event['Event']['id']; ?>)"><span id = "<?php echo $event["Event"]["id"]; ?>"><?php
                                if (in_array($event["Event"]["id"], $my_calendar))
                                    echo "-Remove from My Calendar";
                                else
                                    echo "+Add to My Calendar";
                                ?></span></a>
                        <br><br><br>
                       <?php
                        if ($is_brand != 0) {
                            //echo "I'm Brand";
                            if (AuthComponent::user("id")) {
                                if (AuthComponent::user("id") != $event["Event"]["user_id"]) {
                                    ?>
                                    <a style="" class="btn-subscribe" href="javascript:void(0);" onclick="suscribe_to_brand(<?php echo $is_brand; ?>, '<?php echo $event["User"]["Brand"]["name"]; ?>')">
                                        <span id = "<?php echo $is_brand; ?>">
                                            <?php
                                            if (in_array($is_brand, $my_suscribe))
                                                echo "Unsubscribe to " . $event["User"]["Brand"]["name"] . " Newsletter";
                                            else
                                                echo "Subscribe to " . $event["User"]["Brand"]["name"] . " Newsletter";
                                            ?>
                                        </span>
                                    </a>
                                    <span class="loader" id="load_<?php echo $is_brand; ?>" style="display: none; float: left; margin-left: 6px;"><img src="/img/admin/loader.gif" alt=""></span>
                                    <?php
                                }
                            } else {
                                ?>
                                <a style="" class="btn-subscribe" href="javascript:void(0);" data-target="#sign_in" data-toggle="modal" ><?php echo "Unsubscribe to " . $event["User"]["Brand"]["name"] . " Newsletter"; ?></a>
                                <?php
                            }
                        } else {
                            //echo "Not Yet Brand";
                        }
                        ?>
                    </div>

                </div>
                <div class="emsi-part confirm-event-right" >
                    <?php if (!empty($event["Giveaway"]["id"])) { ?>
                        <a href="/Events/ticketGiveaway/<?php echo base64_encode($event["Event"]["id"]); ?>" class="free-ticket">Enter Now For<br><strong>Free Tickets</strong></a>
                    <?php } ?>
                    <div class="social-share-box">

                        <span class='st_sharethis_large' displayText='ShareThis'></span>
                        <span class='st_facebook_large' displayText='Facebook'></span>
                        <span class='st_googleplus_large' displayText='Google +'></span>
                        <span class='st_twitter_large' displayText='Tweet'></span>
                        <span class='st_linkedin_large' displayText='LinkedIn'></span>
                        <span class='st_pinterest_large' displayText='Pinterest'></span>
                        <span class='st_email_large' displayText='Email'></span>
                        <?php
                        if ($event["Event"]["event_from"] != "facebook")
                        {
                        if (AuthComponent::user("id")) {
                            if (in_array(AuthComponent::user("email"), $edited_user) || (AuthComponent::user("id") == $event["Event"]["user_id"]) || (AuthComponent::user("role_id") == 1)) {
                                echo $this->html->link("Edit Event", array("controller" => "Events", "action" => "editEvent", base64_encode($event["Event"]["id"])), array("class" => "btn-addEvent"));
                            } else {
                                //echo $this->html->link("Add Event", array("controller" => "Events", "action" => "createEvent"), array("class" => "btn-addEvent"));
                            }
                        }
                        }
                        ?>
                    </div>
                    <div style="clear:both;"></div>
                  
                    <!------------------------------------------------------------------------------------------------------------>
                   <div id="container">
                     <?php
                        if ($event["Event"]["event_from"] == "facebook")
                        {
                           echo $this->html->image($event["Event"]["image_name"]);
                           
                        } else {
                            ?>
				<!-- Start Advanced Gallery Html Containers -->				
				
				<div class="content">
					<div class="slideshow-container">
						<div id="controls" class="controls"></div>
						<div id="loading" class="loader"></div>
						<div id="slideshow" class="slideshow"></div>
					</div>
					<div id="caption" class="caption-container">
						<div class="photo-index"></div>
					</div>
				</div>
                <div class="navigation-container">
					<div id="thumbs" class="navigation">
						<a class="pageLink prev" style="visibility: hidden;" href="#" title="Previous Page"></a>
					
						<ul class="thumbs noscript">
                         <li>
                            <a class="thumb" name="slide0" href="/img/EventImages/large/<?php echo $event['Event']['image_name']; ?>" title="Title #0"><?php echo $this->html->image("EventImages/small/" . $event['Event']['image_name'], array("style" => "width:50px;height:54px;", "alt"=>"Title #0")); ?></a>
                          </li>
                         <?php
                            $i = 1;
                            foreach ($event["EventImages"] as $event_image) {
                                ?>
                        <li>
								<a class="thumb" name="leaf" href="/img/EventImages/large/<?php echo $event_image["image_name"]; ?>" title="Title #<?php echo $i; ?>"><?php echo $this->html->image("EventImages/small/" . $event_image["image_name"], array("style" => "width:50px;height:54px;", "alt"=>"Title #".$i)); ?>
									
								</a>
								
							</li>
	 <?php
                                $i++;
                            }
                            ?>
							
						</ul>
						<a class="pageLink next" style="visibility: hidden;" href="#" title="Next Page"></a>
					</div>
				</div>
				<!-- End Gallery Html Containers -->
				<div style="clear: both;"></div>
                                 <?php
                        }
                        ?>
			</div>
                   <!------------------------------------------------------------------------------------------------------------>

                    
                    <?php
// pr($this->Session);
                    if (isset($event['Event']['facebook_url']) && !empty($event['Event']['facebook_url'])) {
                        $fb_event_id = explode("/", $event['Event']['facebook_url']);
                        if (isset($fb_event_id[4])) {
                            global $fb_event;
                            global $eventId;
                            $fb_event = $fb_event_id[4];
                            $eventId = $event["Event"]["id"];
                            ?>


                            <img alt="Facebook" src="/img/FbTitle.jpg">
                            <div class="fb-fl-box">


                                <div class="fb-fc">
                                    <ul>
                                        <?php /*
                                          pr($event);
                                          if (isset($_SESSION['event_attend']) && !empty($_SESSION['event_attend']) && $_SESSION['event_attend']['pageid'] == $fb_event_id[4]) {
                                          foreach ($_SESSION['event_attend']['data'] as $event_attend) {
                                          ?>
                                          <li><a href="http://www.facebook.com/<?php echo $event_attend['id']; ?>" target="_blank"><img src="<?php echo $event_attend['picture']['data']['url']; ?>" alt="<?php echo $event_attend['name']; ?>" /></a><br><?php //echo $event_attend['name'];              ?></li>
                                          <?php
                                          }
                                          } else {
                                          ?>
                                          <a id="fb_event" onclick="javascript:fbEventAttend(<?php echo $fb_event_id[4]; ?>);" href="javascript:void(0);" class="fb-link-btn">Click Here</a> to fetch who are following this event on FB.
                                          <?php
                                          } */
                                        ?>
                                        <?php
                                        if (!empty($event["EventFbattendUser"])) {
                                            echo '<li style="width:100%"><div class="fb-count"><b>' . $event['Event']['attand_count'] . '</b> people are going to this Event.</div></li>';
                                            $n = 1;
                                            foreach ($event["EventFbattendUser"] as $event_attend) {
                                                if ($n < 28) {
                                                    ?>
                                                    <li><a href="http://www.facebook.com/<?php echo $event_attend['user_fbID']; ?>" target="_blank"><img src="<?php echo $event_attend['user_image']; ?>" alt="<?php echo $event_attend['user_name']; ?>" /></a><br><?php //echo $event_attend['name'];                      ?></li>
                                                    <?php
                                                }

                                                $UserFBid[] = $event_attend['user_fbID'];
                                                $n++;
                                            }
                                        } else {
                                            ?>
                                            <li><a onclick="javascript:fbAttEventPost(<?php echo $fb_event_id[4] . ',' . $event["Event"]["id"]; ?>);" href="javascript:void(0);"><img src="/img/fb_detail.png" alt="FB" /></a>&nbsp;&nbsp;<?php echo '<div style="float: right; margin-top: 8px;"> Be the first to brag about it!</div>'; ?></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <a href="<?php echo $event['Event']['facebook_url']; ?>" target="_blank" class="fb-btn-link">View this Event's Facebook Page</a>
                                <?php
                              if(isset($logUser) && isset($UserFBid) && in_array($logUser['User']['fbID'],$UserFBid))
                              {
                                if($event['User']['fbID'] == $logUser['User']['fbID'])
                                {
                                    echo "<div class='user-event'> You are the host on fb for this event.</div>";
                                }
                                else
                                {
                                ?>
                                  <a onclick="javascript:fbAttEventRemove(<?php echo $fb_event_id[4] . ',' . $logUser['User']['fbID']. ',' . $event["Event"]["id"]; ?>);" href="javascript:void(0);" id="<?php echo $fb_event_id[4]; ?>" class="fb-btn-link float-right" title="Click Here if you dont want to Go on this Event.">FB - I'm Going!</a>
                                <div class='user-event'><a onclick="javascript:fbAttEventRemove(<?php echo $fb_event_id[4] . ',' . $logUser['User']['fbID']. ',' . $event["Event"]["id"]; ?>);" href="javascript:void(0);" id="<?php echo $fb_event_id[4]; ?>">Click Here</a> if you dont Want to Go on this Event.</div>
                               <?php
                                } }
                              else
                              {
                              ?>
                              
                                <a onclick="javascript:fbAttEventPost(<?php echo $fb_event_id[4] . ',' . $event["Event"]["id"] . ',' . $log_in; ?>);" href="javascript:void(0);" id="<?php echo $fb_event_id[4]; ?>" class="fb-btn-link float-right"><img style="margin-bottom: -3px;" src="/img/front/fb_icon.png">&nbsp;Join this Event</a>
                                <?php
                                
                              }
                              ?>
                                
                                 <span class="loader" id="load_fb" style="display: none;"><img src="/img/admin/loader.gif" alt=""></span>
                                                                
                            </div>

                            <?php
                        }
                    }
                    ?>
                    <?php if (!empty($event["Event"]["video"])) { ?>
                        <div class="event-video">
                            <iframe width="385" height="254" src="//www.youtube.com/embed/<?php echo $event['Event']['video']; ?>" frameborder="0" allowfullscreen></iframe><br><br>
                        </div>
                    <?php } ?>
                    <br><br>
                    <div class="ev-des-box event-flyer">
                        <?php
                        if ($event["Event"]["event_from"] == "facebook") {
                            $imgPathF = '';
                        } else {
                            $imgPathF = "/img/flyerImage/large/";
                        }
                        ?>
                        <img src="<?php echo $imgPathF . $event['Event']['flyer_image']; ?>" alt="">
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
    var lat = "<?php echo $zip['Zip']['lat'] ?>";
    var lng = "<?php echo $zip['Zip']['lng'] ?>";
    // function define in custom js and google js calls in frontend layout
    getmapdata(lat, lng);
</script>
<script type="text/javascript" src="/js/jquerypp.custom.js"></script>
<script type="text/javascript" src="/js/jquery.elastislide.js"></script>

<link rel="stylesheet" href="/css/galleriffic-5.css" type="text/css" />


               
                <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
		<script type="text/javascript" src="/js/slider/jquery.history.js"></script>
		<script type="text/javascript" src="/js/slider/jquery.galleriffic.js"></script>
		<script type="text/javascript" src="/js/slider/jquery.opacityrollover.js"></script>



<script type="text/javascript">
			jQuery(document).ready(function($) {
				// We only want these styles applied when javascript is enabled
				$('div.content').css('display', 'block');

				// Initially set opacity on thumbs and add
				// additional styling for hover effect on thumbs
				var onMouseOutOpacity = 0.67;
				$('#thumbs ul.thumbs li, div.navigation a.pageLink').opacityrollover({
					mouseOutOpacity:   onMouseOutOpacity,
					mouseOverOpacity:  1.0,
					fadeSpeed:         'fast',
					exemptionSelector: '.selected'
				});
				
				// Initialize Advanced Galleriffic Gallery
				var gallery = $('#thumbs').galleriffic({
					delay:                     2500,
					numThumbs:                 6,
					preloadAhead:              5,
					enableTopPager:            false,
					enableBottomPager:         false,
					imageContainerSel:         '#slideshow',
					controlsContainerSel:      '#controls',
					captionContainerSel:       '#caption',
					loadingContainerSel:       '#loading',
					renderSSControls:          true,
					renderNavControls:         true,
					playLinkText:              '',
					pauseLinkText:             '',
					prevLinkText:              '&lsaquo; Previous',
					nextLinkText:              'Next &rsaquo;',
					nextPageLinkText:          'Next &rsaquo;',
					prevPageLinkText:          '&lsaquo; Prev',
					enableHistory:             true,
					autoStart:                 true,
					syncTransitions:           true,
					defaultTransitionDuration: 900,
					onSlideChange:             function(prevIndex, nextIndex) {
						// 'this' refers to the gallery, which is an extension of $('#thumbs')
						this.find('ul.thumbs').children()
							.eq(prevIndex).fadeTo('fast', onMouseOutOpacity).end()
							.eq(nextIndex).fadeTo('fast', 1.0);

						// Update the photo index display
						this.$captionContainer.find('div.photo-index')
							.html('Photo '+ (nextIndex+1) +' of '+ this.data.length);
					},
					onPageTransitionOut:       function(callback) {
						this.fadeTo('fast', 0.0, callback);
					},
					onPageTransitionIn:        function() {
						var prevPageLink = this.find('a.prev').css('visibility', 'hidden');
						var nextPageLink = this.find('a.next').css('visibility', 'hidden');
						
						// Show appropriate next / prev page links
						if (this.displayedPage > 0)
							prevPageLink.css('visibility', 'visible');

						var lastPage = this.getNumPages() - 1;
						if (this.displayedPage < lastPage)
							nextPageLink.css('visibility', 'visible');

						this.fadeTo('fast', 1.0);
					}
				});

				/**************** Event handlers for custom next / prev page links **********************/

				gallery.find('a.prev').click(function(e) {
					gallery.previousPage();
					e.preventDefault();
				});

				gallery.find('a.next').click(function(e) {
					gallery.nextPage();
					e.preventDefault();
				});

				/****************************************************************************************/

				/**** Functions to support integration of galleriffic with the jquery.history plugin ****/

				// PageLoad function
				// This function is called when:
				// 1. after calling $.historyInit();
				// 2. after calling $.historyLoad();
				// 3. after pushing "Go Back" button of a browser
				function pageload(hash) {
					// alert("pageload: " + hash);
					// hash doesn't contain the first # character.
					if(hash) {
						$.galleriffic.gotoImage(hash);
					} else {
						gallery.gotoIndex(0);
					}
				}

				// Initialize history plugin.
				// The callback is called at once by present location.hash. 
				$.historyInit(pageload, "advanced.html");

				// set onlick event for buttons using the jQuery 1.3 live method
				$("a[rel='history']").live('click', function(e) {
					if (e.button != 0) return true;

					var hash = this.href;
					hash = hash.replace(/^.*#/, '');

					// moves to a new page. 
					// pageload is called at once. 
					// hash don't contain "#", "?"
					$.historyLoad(hash);

					return false;
				});

				/****************************************************************************************/
			});
		</script>
<script type="text/javascript">
window.onload = asds();
function asds()
{
     FB.getLoginStatus(function(response) {
                        if (response.status === 'connected') {
                            var uid = response.authResponse.userID;
                            var accessToken = response.authResponse.accessToken;
<?php if(isset($fb_event) && $fb_event!="") { ?>

                            AttendList(<?php echo $fb_event . ',' . $eventId; ?>);
<?php } ?>
                        }
                    });
}
   
      



        function totalCountAttend(eId, event_id) {

            FB.api(
                    "/" + eId + "/attending?summary=true&limit=0",
                    function(response) {
                        if (response && !response.error) {
                            response.event_id = event_id;
                            //console.log(response);
                            $.post(base_url + '/Events/totalCount', {"data[eventattendtotal]": response}, function(data) {

                            });

                        }
                    }
            );

        }
        function  AttendList(eId, event_id) {
           
            FB.api(
                    "/" + eId + "/attending?fields=picture,name,rsvp_status&limit=50",
                    function(response) {
                        if (response && !response.error) {
                            response.pageid = eId;
                            response.event_id = event_id;
                            totalCountAttend(eId, event_id);
                            $.post(base_url + '/Events/addattendmult', {"data[eventattend]": response}, function(data) {
                                //alert(data);
                                if (document.cookie.indexOf('visit' + eId + '=true') == -1) {

                                    var fifteenDays = 1000 * 60 * 60;
                                    var expires = new Date((new Date()).valueOf() + fifteenDays);
                                    document.cookie = "visit" + eId + "=true;expires=" + expires.toUTCString();
                                    location.reload();

                                }

                                //$("#event_came").html() = response;       
                            });
                            // console.log(response);
                        }
                    }
            );
        }

  

</script>
