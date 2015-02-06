<?php
//pr($services);die;
?>

<div class="center-block">
        <div class="em-sec">
			<h1>A la Carte Promotion Services</h1>
            <?php

    foreach ($services as $service) {
       
            ?>
           
            <div class="col-3">
            	<div class="package-box">
                	<h3><?php echo $service["Service"]["name"]; ?></h3>
                    <div>
                        <p><?php echo $service["Service"]["description"]; ?><br><br></p>
                       
                        <div class="clear"></div>
                        <input type="text" readonly class="rate-box" value="$<?php echo $service["Service"]["price"]; ?>">
                         <?php echo $this->html->link("+Add to Promo Package", 'javascript:void(0)', array("class" => "btn-addToCart addSer","alt"=>$service["Service"]["name"],"price"=>$service["Service"]["price"],"dir"=>$service["Service"]["id"])); ?>
                    </div>
                </div>
            </div>
               <?php  } ?>
           <div class="col-3">
            	<div class="package-box"><h3>Services Chosen</h3>
                    <div>
                    <?php echo $this->Form->create("Payments", array("action"=>"customPackagePayment","id"=>"custom"));?>
                	
                                          
                        <div class="clear"></div>
                        <div class = "medium_list_new" style="width:99.8%;">
        <div class="services"></div>     
          
</div>
                        Total : $<span id="total">0.00</span>
                        <?php echo $this->html->link("Continue",'javascript:void(0)', array("class" => "btn-addToCart","id"=>"cstmPkgesPayment")); ?><br/><br/>
                    
                    <?php echo $this->Form->end();?>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
<div class="col-3">
            	<div class="package-box"><h3>Other Services</h3>
                   <div><b>Don't see what you're looking for?</b><br><br>
                   

AList Hub's talented team can also offer a range of other services. Get in touch with us, and we'll see what we can do for you!
<br><br>
 

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et viverra justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam fermentum, nulla luctus pharetra vulputate, felis tellus mollis orci, sed rhoncus sapien nunc eget odio.
</div>    <?php echo $this->html->link("Contact Us",array("controller"=>"CmsPages","action"=>"contactUs"), array("class" => "btn-addToCart cont")); ?><br/><br/> <br/>
                   
                </div>
            </div>
            <div class="clear"></div>
           
           
        </div>
    </div>
<div class="clear"></div>
           
           
        </div>
    </div>
<?php echo $this->Html->script('/js/Front/Services/alacartePromotionalService');?>