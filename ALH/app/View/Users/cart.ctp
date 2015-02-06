<section class="inner-content">
    <div class="center-block makeanoffer-whole">
        <div class="em-sec profile-whole">
            <h1>Your Cart</h1>

            <div class="checkout-whole">
                <?php
                $totalPrice = array();
                $packageId = "";
                foreach ($carts as $cart):
                    $totalPrice[] = $cart['Package']['price'];
                    ?>
                    <div class="checkout-prtclr" id="cart_<?php echo $cart['Cart']['id']; ?>">
                        <h3><span>
                        <?php echo $cart['Package']['name']; ?></span>
                       
                        
                        
                                <label>Package Price: $<?php echo $cart['Package']['price'];?></label>
                        </h3>
                        <div class="checkout-prtclr-cntnt">
                            <div class="checkout-prtclr-cntnt-lt">
                                <?php foreach ($cart['Package']['Service'] as $service): ?>
                                    <p>
                                        <b>Name :</b>  <span><?php echo $service['name']; ?></span>
                                    </p>
                                <?php endforeach; ?>   
                            <!--<p>
                                <b>Email per Week :</b>  <span>45</span>
                            </p>
                            
                            <p>
                                <b>Campaign Started :</b>  <span>10 June 2014</span>
                            </p>
                            
                            <p>
                                <b>Campaign Completed :</b>  <span>In Progress</span>
                            </p>-->
                            </div>

                            <div class="checkout-prtclr-cntnt-rt">
                                <a href="javascript:void(0);" class="smlpink_button rmove-cart" dir="<?php echo $cart['Cart']['id']; ?>" rel="<?php echo $cart['Package']['price']; ?>"> Remove from Cart</A>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>  

                <div class="total-checkout">
                    <b>Total :</b>  $<span class="totl" price="<?php echo array_sum($totalPrice); ?>"><?php echo array_sum($totalPrice); ?></span>
                </div>
                <?php echo $this->Form->create("Payment", array("action" => "payForPackage", "id" => "payCart")); ?>
                <input type="hidden" name="data[Package][price]" value="<?php echo array_sum($totalPrice); ?>" class="forwardPrice">
                <input type="hidden" name="cartIds" value="" id="cartIds">
                <div class="btn-rt-btm">
                    <a href="javascript:void(0);" class="violet_button cntue-checkout">Continue to Checkout</a>
                </div>
                <?php $this->Form->end(); ?>
            </div>


            <div class="clear"></div>
        </div>
    </div>
</section>
<script type="text/javascript">
    $(document).ready(function() {
        $(".rmove-cart").click(function() {
            var cart_id = $(this).attr("dir");
            var packagePrice = $(this).attr("rel");
            var totalPrice = $(".totl").attr("price");
            jQuery.ajax({
                url: '/Users/removeItem/' + cart_id,
                success: function(data) {
                    if (data == 0) {
                        $("#cart_" + cart_id).remove();
                        var finalPrice = totalPrice - packagePrice;
                        $(".totl").attr("price", finalPrice);
                        $(".forwardPrice").val(finalPrice);
                        $(".totl").text(finalPrice);
                    }
                }
            });
        });

        $(".cntue-checkout").click(function() {
            jQuery.ajax({
                url: '/Users/getPackageIds/',
                success: function(data) {
                    if (data != "") {
                        $("#cartIds").val(data);
                        $("#payCart").submit();
                        //$(".cntue-checkout").attr("href", "/Payments/payForPackage/"+data);
                        //$(".cntue-checkout").trigger("click");
                    }
                }
            });
        });

    });
</script>