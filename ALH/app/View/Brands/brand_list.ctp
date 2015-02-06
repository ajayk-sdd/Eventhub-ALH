<?php
if (isset($_POST['data']['BrandCategory']) || isset($_POST['data']['BrandVibe']) || (isset($_POST['data']['Brand']['name']) && !empty($_POST['data']['Brand']['name']))) {
    $clss = "show-hide-panel";
    $txt = "Hide";
} else {
    $clss = "";
    $txt = "Show";
}
?>

<section class="inner-content">
    <div class="center-block">
        <div class="em-sec">
            <div class="profile-whole">
            <h1>My Account</h1>
            </div>
            <ul class="tabs profile-tabs">
                <li>
                    <?php echo $this->Html->link('Profile & Preferences', '/Users/viewProfile'); ?>
                </li>
                <li>
                    <?php echo $this->Html->link('Added Events', '/Events/MyEventList'); ?>
                </li>
                <li  class="active">
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
                <div id="div3" class="content">
    <div class="em-sec">
 
        <!-- search panel start here -->
        <div class="search-panel">
            <?php echo $this->Form->create("Brand", array("action" => "brandList", "name" => "search_form")); ?>

            <h1 class="src-fltr">Search and Filtering
                <a href="javascript:void(0);" class="bn-hide-show"><?php echo $txt; ?></a>
            </h1>
            <div id="dateError" style="display: none;text-align: center">You need to fill/select any of the search critaria.</div>
            <div class="sp-inner">
                <ul class="sp-hide-content <?php echo $clss; ?>" >
                    <li>
                        <label>Find an Brand:</label>
                       <?php echo $this->Form->input("Brand.name", array('type' => 'text', 'div' => false, 'label' => false, "placeholder" => "Enter Brand Name", "required" => false)); ?>
   

                    </li>
                    <li>
                        <label>Brand Categories: &nbsp;</label>
                        <p>
                            <?php
                            if (isset($categoriesS)) {
                                echo $categoriesS;
                            }
                            ?>
                        </p>
                        <?php echo $this->Html->link('More+', 'javascript:void(0);',array("class"=>"show-more-categories")); ?>
                        
                        <div class="categories-more-option">
                            <?php 
                            foreach ($categories as $category):
                                $cID = $category['Category']['id'];
                                ?>
                                <label><input type="checkbox" name="data[BrandCategory][id][<?php echo $category['Category']['id']; ?>]"  <?php
                                              if (isset($_POST['data']['BrandCategory']['id'][$cID]) && $_POST['data']['BrandCategory']['id'][$cID] == "on") {
                                                  echo 'checked';
                                              }
                                              ?>><?php echo $category['Category']['name']; ?></label>
                    <?php endforeach; ?>
                        </div>

                    </li>
                   
                    <li>
                        <label>Brand Vibe: &nbsp;</label>
                        <p><?php
                            if (isset($vibesS)) {
                                echo $vibesS;
                            }
                            ?></p>
                         <?php echo $this->Html->link('More+', 'javascript:void(0);',array("class"=>"show-more-vibes")); ?>
                       
                        <div class="vibes-more-option">
                                <?php 
                                foreach ($vibes as $vibe):
                                    $vID = $vibe['Vibe']['id'];
                                    ?>
                                <label><input type="checkbox" name="data[BrandVibe][id][<?php echo $vibe['Vibe']['id']; ?>]" <?php
                                if (isset($_POST['data']['BrandVibe']['id'][$vID]) && $_POST['data']['BrandVibe']['id'][$vID] == "on") {
                                    echo 'checked';
                                }
                                    ?>><?php echo $vibe['Vibe']['name']; ?></label>
                        <?php endforeach; ?>
                        </div>
                    </li>
                    <li>
                    <?php
                        echo $this->Form->input("Search", array('type' => 'submit', 'div' => false, 'label' => false, "id" => "search-sub-btn"));
                        echo $this->html->link('Clear Search', array('controller' => 'Brands', 'action' => 'brandList'), array("class" => "clear-search"));
                    ?>

                    </li>
                    <div class="clear"></div>
                </ul>
            </div>
            <div class="sp-bottom">
               <?php echo $this->Form->input("Brand.list", array('type' => 'select', 'options' => array( "2" => "All Brands", "1" => "Brands I'm subscribed to", "3" => "Brands I'm not subscribed to"), 'div' => false, 'label' => false, "onchange" => "javascript:document.search_form.submit();", "style" => array("width:230px"))); ?>
                
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
                <div class="paginate-list marg-left200">
                    <span>Show</span>
                    <?php echo $this->Html->link('10', 'javascript:void(0);',array("class"=>$active_10,"onclick"=>"javscript:setLimit(10);")); ?>
                    <?php echo $this->Html->link('20', 'javascript:void(0);',array("class"=>$active_20,"onclick"=>"javscript:setLimit(20);")); ?>
                    <?php echo $this->Html->link('40', 'javascript:void(0);',array("class"=>$active_40,"onclick"=>"javscript:setLimit(40);")); ?>
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
            <div class="event-container brand">
<?php
$k = 0;
if(!empty($brands))
{
foreach ($brands as $brand) {  ?>
        <div class="event-box">
            
        <?php echo $this->html->image("brand/small/" . $brand["Brand"]["logo"], array('class' => 'event_img', 'width' => '120px', 'height' => '100px')); ?>
            <div class="event-short-des">
                <h2><?php echo $brand["Brand"]["name"]; ?></h2>
                <p> <?php echo $brand["Brand"]["sub_title"]; ?></p>
                <p>
                <?php echo "Brand Category : ".$this->Common->getbrandCat($brand["Brand"]["id"]);?>
                </p>
                <p><?php echo "Brand Vibe : ".$this->Common->getbrandVibe($brand["Brand"]["id"]);?></p>
            
                
            </div>
            <div class="clear"></div>
            <div class="event-dv-content">
                <div class="para-ed-brand"><?php echo substr($brand["Brand"]["description"], 0, 150) . '...'; ?></div>
                 
            </div>
              <?php echo $this->html->link("View Brand", array("controller" => "Brands", "action" => "viewBrand", base64_encode($brand["Brand"]["id"])), array("class" => "btn-addToCal mar_left")); ?>
                <a class="btn-addToCal" href="javascript:void(0);" onclick="suscribe_to_brand(<?php echo $brand['Brand']['id']; ?>,'<?php echo $brand["Brand"]["name"];?>')">
                <span id = "<?php echo $brand["Brand"]["id"]; ?>">
                <?php
                    if (in_array($brand["Brand"]["id"], $my_suscribe))
                        echo "Unsubscribe to ".$brand["Brand"]["name"]." Newsletter";
                    else
                        echo "Subscribe to ".$brand["Brand"]["name"]." Newsletter";
                ?>
                </span>
                </a>
                <span class="loader" id="load_<?php echo $brand['Brand']['id']; ?>" style="display: none;"><img src="/img/admin/loader.gif" alt=""></span>
                              
                <?php    
   
                    if($k%2==0)
                        {
                            echo '<br>';
                            
                        }
                ?>
                </div>
                <?php
                    }
                    }
                    else
                        {
                            echo '<div class="no-found">No Brand Found.</div>';
                        }
                ?>



            </div>
            <div class="clear"></div>
            <div class="event-pagination paginate-list">
                <span class="peginationTxt"><?php echo $this->Paginator->counter(array('format' => 'Events %start% - %end% of %count%')); ?></span>
                
                <?php
                
                    echo $this->Paginator->first('', null, null, array());
                    echo $this->Paginator->prev('', null, null, array());
                    echo $this->Paginator->next('', null, null, array());
                    echo $this->Paginator->Last('', null, null, array());
              
                ?>
                <div class="clear"></div>
            </div>
        </div><!-- /event container end -->
        <div class="clear"></div>
    </div>
</div>
            </div></div></div></section>


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
            var vtxt = $(".vibes-more-option").is('.show-more-option') ? 'More+' : 'Less-';
            $(".show-more-vibes").text(vtxt);
            $('.vibes-more-option').toggleClass('show-more-option');
        });
        $('.show-more-categories').click(function() {
            var ctxt = $(".categories-more-option").is('.show-more-option') ? 'More+' : 'Less-';
            $(".show-more-categories").text(ctxt);
            $('.categories-more-option').toggleClass('show-more-option');
        });
        
        $('#search-sub-btn').click(function() {
        var brandName = $('#BrandName').val();
        if($('.vibes-more-option input[type=checkbox]:checked').length == 0 && $('.categories-more-option input[type=checkbox]:checked').length == 0 && brandName=="")
        {
           $("#dateError").show();
            
        return false;
        }
        else
        {
            document.search_form.submit();
        }
       
        });

    });

    function setLimit(limit) {
        $("#limit").val(limit);
        document.search_form.submit();
    }
</script>

