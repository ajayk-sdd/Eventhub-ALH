<section class="inner-content">
    <div class="center-block">
        <div class="em-sec profile-whole">
            <h1>My Account</h1>
            <ul class="tabs profile-tabs">
                <li><a href="/Users/viewProfile">Profile & Preferences</a></li>
                <li><a href="/Events/MyEventList">My Added Events</a></li>
                <li><a href="/brands/brandList">My Subscriptions</a></li>
                <li><a href="/Users/BillingInfo">Billing Info</a></li>
                <li><a href="/Sales/orderList">Order History</a></li>
                <li><a href="/Users/myAddress">ADDRESSES</a></li>
                <li><a href="/Users/track">Track</a></li>
                <li class="active"><a href="/Users/track">Point</a></li>
            </ul>
            <div class="content_outer">
                <div id="div1" class="content">
                    <!------------------------------------------------------------------------------------------>
                    <!----------------------- points starts below------------------>

                    <div class="clm-wrap">
                        <h2 style="color:#7900A0;">Buy Points</h2>
                        <table style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Points</th>
                                    <th>Price</th>
                                    <th>Buy Now</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                foreach ($datas as $data):
                                    $i = 1;
                                    ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $data['VirtualCurrency']['points']; ?></td>
                                        <td><?php echo "$".$data['VirtualCurrency']['price']; ?></td>
                                        <td><?php //echo $this->html->link('Buy Now', array("javascript:void(0);"), array('escape' => false, "class" => "","onclick"=>"javascript:buyNow(".$data['VirtualCurrency']['price'].",".$data['VirtualCurrency']['points'].")")); ?>
                                            <a href="javascript:void(0);" onclick="javascript:buyNow(<?php echo $data['VirtualCurrency']['price'].",".$data['VirtualCurrency']['points'];?>)">Buy Now</a>
                                        </td>    
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div style="clear:both;"></div>
                    <!----------------------- Payment starts below------------------>
                    <div class="clm-wrap" style="display: none;">
                        <h2 style="color:#7900A0;">Upcoming Campaigns</h2>
                        
                    </div>
                    
                    <!------------------------------------------------------------------------------------------>
                </div>

            </div>


        </div>
    </div>
</section>
<?php echo $this->Html->script("Front/ajaxform"); ?>
<script type="text/javascript">

//    $(document).ready(function() {
//        //$('li:first-child').addClass('active');
//        $('.content').hide();
//        $('.content:first-child').show();
//        $('.tabs li').click(function() {
//            $('.tabs li').removeClass('active');
//            $(this).addClass('active');
//            var divid = $(this).find('a').attr('href');
//            $('.content').hide();
//            $(divid).fadeIn(500);
//            return false;
//        });
//    });


function buyNow(price, point){
    alert(price+"-"+point);
}
</script>