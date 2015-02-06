<?php
#pr($user);
?>
<section class="inner-content">
    <div class="center-block">
        <div class="em-sec profile-whole">
            <h1>My Account</h1>
            <?php echo $this->Session->flash(); ?>
            <ul class="tabs profile-tabs">
                <li class="active">
                    <?php echo $this->Html->link('Profile & Preferences', '/Users/viewProfile'); ?>
                </li>
                <li>
                    <?php echo $this->Html->link('Added Events', '/Events/MyEventList'); ?>
                </li>
                <li>
                    <?php echo $this->Html->link('List Subscriptions', '/brands/brandList'); ?>
                </li>
                <li>
                    <?php echo $this->Html->link('Billing Info', '/Users/BillingInfo'); ?>
                </li>
                <li>
                    <?php echo $this->Html->link('Order History', '/Sales/orderList'); ?>
                </li>
                <li>
                    <?php echo $this->Html->link('Track', '/Users/track'); ?>
                </li>
            </ul>
            <div class="content_outer">
                <div id="div1" class="content">

                    <div class="lt-tabcontnt">
                        <div class="profile-details">
                            <h5>Profile Picture :</h5>
                            <span id = "image_uploaded">
                                <?php
                                 if($user['User']['fb_connect'] != 1 && empty($user['User']['profile_image']))
                                {
                                    echo '<img src="'.BASE_URL.'/img/user-dummy.png">';
                                }
                                elseif (($user['User']['fb_connect'] != 1) || !empty($user['User']['profile_image'])) { ?>
                                    <img src="<?php echo $image; ?>">

                                    <?php
                                } else {
                                    $uid = $user['User']['fbID'];
                                    $profile_pic = "http://graph.facebook.com/" . $uid . "/picture?width=200&height=200";


                                    echo "<img src=\"" . $profile_pic . "\" />";
                                }
                                ?>
                            </span>

                            <div id="dateError" style="display:none;">
                            </div>
                          
                            <div class="img-update">
                                <?php
                                echo $this->Form->create("User", array("action" => "changeImage", "id" => "ImageForm", 'enctype' => 'multipart/form-data'));
                                echo '<span style="display: none;" id="load_img" class="loader"><img style="border:0px;width:auto;" alt="" src="/img/admin/loader.gif"></span>';
                                echo $this->Form->input("User.image_name", array("type" => "file", "label" => "Upload Image: ", "div" => false, "class" => "validate[required] form_input", "onchange" => "upload_this_image()"));
                                //echo $this->Form->button("Submit", array("type" => "", "label" => false, "id" => "subImg","class" => "violet_button submt-btn"));

                                echo $this->Form->end();
                                ?>
                            </div>
                            <ul>
                                <li> 
                                    <blockquote>Name :</blockquote>   <span><?php echo $user['User']['first_name'], " " . $user['User']['last_name']; ?></span>
                                </li>

                                <li> 
                                    <blockquote>Email :</blockquote>   <span><?php echo $user['User']['email']; ?></span>
                                </li>

                                <li> 
                                    <blockquote>Phone :</blockquote>   <span><?php echo $user['User']['phone_no']; ?></span>
                                </li>
                                <li> <a href="/users/changePassword" class="violet_button">Change Password</a>&nbsp;<a href="/users/register" class="violet_button mar_left">Update Profile</a></li>
                            </ul>

                            <?php if ($user["User"]["fb_connect"] == 0) { ?>
                                <h3>It looks like you haven't linked with Facebook yet!</h3>
                                <p>Link your account to Facebook for these great reasons:</p>

                                <ul class="bullets-points">
                                    <li>Facebook Event</li>
                                    <li>Your Profile Details (like: email, about..)</li>

                                </ul>

                                <a onclick="javascript:fbAddedAccCheck(<?php echo $user["User"]["id"]; ?>);" href="javascript:void(0);" class="fb-link-btn">Link My Account with Facebook</a>
                                <br><br>
                            <?php } else { ?>
                                <div class="img-update">   <a onclick="javascript:facebookEvents();" href="javascript:void(0);" class="violet_button">Click Here</a>&nbsp;&nbsp; to import your Facebook Events. 
                                    <span class="loader" id="load_fb" style="display: none;"><img src="/img/admin/loader.gif" alt="" style="border:0px;width:auto;"></span>
                                    <div id = "event_came"></div></div>
                            <?php } ?>
                            <div class="point-detail">
                                <h6>Purchase Points:</h6>
                                <?php echo $this->Form->create("Point", array("action" => "buyNow", "id" => "BuyPointForm")); ?>
                                <span style="color:red;" id = "error_message"></span>
                                <ul>

                                    <li> 
                                        <blockquote>Number of Points :</blockquote>   <span><?php echo $this->Form->input("BuyPoint.id", array("type" => "select", "empty" => "Select Points", "options" => $buy_points, "label" => FALSE, "div" => FALSE, "onchange" => "javascript:getPointPrice(this.value);", "class" => "validate[required]")); ?></span>
                                    </li>

                                    <li> 
                                        <blockquote>Amount :</blockquote>   <span id = "buy_now_price"></span>
                                    </li>
                                    <li>
                                        <?php echo $this->Form->input("Buy Now", array("type" => "submit", "class" => "violet_button", "label" => FALSE, "div" => FALSE)); ?>
                                    </li>
                                </ul>
                                <?php echo $this->Form->end(); ?>
                            </div>

                            <div class="point-detail">
                                <h6>CashOut Points:</h6>
                                <?php if ($user['User']['ALH_point'] > 0) { ?>
                                    <?php echo $this->Form->create("Point", array("action" => "cashOut/" . base64_encode($point_price), "id" => "CashOutPointForm")); ?>
                                    <span style="color:red;" id = "error_message"></span>
                                    <ul>

                                        <li> 
                                            <blockquote>Number of Points :</blockquote>   <span><?php echo $user['User']['ALH_point']; ?></span>
                                        </li>

                                        <li> 
                                            <blockquote>Amount :</blockquote>   <span><?php echo "$" . $point_price; ?></span>
                                            <?php echo $this->Form->input('', array("type" => "hidden", "name" => "price_point", "value" => $point_price, "label" => FALSE, "div" => FALSE)); ?>
                                        </li>
                                        <li>
                                            <?php echo $this->Form->input("Continue", array("type" => "submit", "class" => "violet_button", "label" => FALSE, "div" => FALSE)); ?>
                                        </li>
                                    </ul>
                                    <?php
                                    echo $this->Form->end();
                                } else {
                                    ?><br>
                                    You have zero points. You are not able to cashout points.
                                <?php } ?>
                            </div>

                        </div>
                    </div>

                    <?php
                    echo $this->Form->create("User", array("action" => "viewProfile", "id" => "prefrences"));
                    ?>
                    <div class="rt-tabcontnt">
                        <h3>My Preferences</h3>

                        <div class="preference-box">
                            <h6>I Prefer Events in these regions (choose as many as you'd like) :</h6>
                            <?php
                            $k = 0;

                            if (!empty($user['UserpRegion'])) {
                                foreach ($user['UserpRegion'] as $ec) {
                                    $user_reg[] = $ec["region_id"];
                                }
                            } else {
                                $user_reg = array();
                            }


                            foreach ($regions as $reg):
                                echo '<div class="checkbox-whole">';
                                if (in_array($reg['Region']['id'], $user_reg))
                                    echo $this->Form->input("UserpRegion.region_id[]", array("checked" => true, "name" => "data[UserpRegion][region_id][]", "type" => "checkbox", "label" => $reg['Region']['name'], "div" => false, "class" => "validate[required] form_input", "value" => $reg['Region']['id']));
                                else
                                    echo $this->Form->input("UserpRegion.region_id[]", array("name" => "data[UserpRegion][region_id][]", "type" => "checkbox", "label" => $reg['Region']['name'], "div" => false, "class" => "validate[required] form_input", "value" => $reg['Region']['id']));
                                $k++;
                                echo '</div>';

                            endforeach;
                            ?>
                        </div>
                        <div class="preference-box">
                            <h6>Favorite Event Categories (choose as many as you'd like) :</h6>
                            <?php
                            $j = 0;

                            if (!empty($user["UserpCategory"])) {
                                foreach ($user["UserpCategory"] as $ec) {
                                    $user_cate[] = $ec["category_id"];
                                }
                            } else {
                                $user_cate = array();
                            }


                            foreach ($categories as $cat):
                                echo '<div class="checkbox-whole">';
                                if (in_array($cat['Category']['id'], $user_cate))
                                    echo $this->Form->input("UserpCategory.category_id[]", array("checked" => true, "name" => "data[UserpCategory][category_id][]", "type" => "checkbox", "label" => $cat['Category']['name'], "div" => false, "class" => "validate[required] form_input", "value" => $cat['Category']['id']));
                                else
                                    echo $this->Form->input("UserpCategory.category_id[]", array("name" => "data[UserpCategory][category_id][]", "type" => "checkbox", "label" => $cat['Category']['name'], "div" => false, "class" => "validate[required] form_input", "value" => $cat['Category']['id']));
                                $j++;
                                echo '</div>';

                            endforeach;
                            ?>
                        </div>
                        <div class="preference-box">
                            <h6>Favorite Event Vibes (choose as many as you'd like) :</h6>
                            <?php
                            $i = 0;
                            if (!empty($user["UserpVibe"])) {
                                foreach ($user["UserpVibe"] as $ev) {
                                    $user_vib[] = $ev["vibe_id"];
                                }
                            } else {
                                $user_vib = array();
                            }


                            foreach ($vibes as $vibe):
                                echo '<div class="checkbox-whole">';
                                if (in_array($vibe['Vibe']['id'], $user_vib))
                                    echo $this->Form->input("UserpVibe.vibe_id", array("checked" => true, "name" => "data[UserpVibe][vibe_id][]", "type" => "checkbox", "label" => $vibe['Vibe']['name'], "div" => false, "class" => "validate[required] form_input", "value" => $vibe['Vibe']['id']));
                                else
                                    echo $this->Form->input("UserpVibe.vibe_id", array("name" => "data[UserpVibe][vibe_id][]", "type" => "checkbox", "label" => $vibe['Vibe']['name'], "div" => false, "class" => "validate[required] form_input", "value" => $vibe['Vibe']['id']));
                                $i++;
                                echo '</div>';
                            endforeach;
                            ?>
                        </div>
                        <input type="submit" value="Save all Preferences" class="violet_button">
                        <?php
                        echo $this->Form->end();
                        ?>
                    </div>
                </div>
            </div>
        </div>
</section>
<?php echo $this->Html->script("Front/ajaxform"); ?>
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
        $("#BuyPointForm").validationEngine();


    });

    function upload_this_image() {
        $("#load_img").show();
        var imgSize = $("#UserImageName")[0].files[0].size/1000;
        
        if (imgSize>2000) {
                    $("#load_img").hide();
                    $("#dateError").show();
                    $("#dateError").html("You can upload max 2MB size file. Please try again!");
        return false;
        }
        else
        {
        $("#ImageForm").ajaxForm({
            success: function(data) {
                data = jQuery.parseJSON(data);
                if (data.success == "Updated") {
                      $("#dateError").hide();
                      $("#load_img").hide();
                    var img = "<img src='" + data.name + "'>";
                    $("#image_uploaded").html(img);
                }
                
                  else if (data.success == "MaxSizeReached")
                {
                    $("#load_img").hide();
                    $("#dateError").show();
                    $("#dateError").html("You can upload max 2MB size file. Please try again!");
                }
                  else
                {
                    $("#load_img").hide();
                    $("#dateError").show();
                    $("#dateError").html("Invalid file format. Please try again with valid Image.");
                }

            }
        }).submit();
        return true;
        }
    }
</script>
<?php echo $this->Html->script('/js/Front/facebook');
?>