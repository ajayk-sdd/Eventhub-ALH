<?php //echo base64_decode($packageID); die;   ?>                                      
<section class="inner-content">
    <div class="center-block">
        <div class="em-sec">
            <h1>Make Payment</h1>
            <?php echo $this->Session->flash(); ?>
            <div class="breadcrumb">
                <!--                <ul>
                                    <li class="active">Step 1: Event Details</li>
                                    <li>Step 1: Confirm Details</li>
                                    <li>Step 1: Event Marketing</li>
                                    <li>Step 1: Share Your Event</li>
                                </ul>-->
            </div>
            <div class="clear"></div>
            <?php
            if ($package_detail["Package"]["buy_point_id"] == 0) {
                $actual_amount = $package["Package"]["price"];
                $point_value = $ALH_point * $point_rate;
                if ($point_value > $actual_amount) {
                    $amount = 0;
                    $point_expence = $actual_amount / $point_rate;
                    $point = $ALH_point - $point_expence;
                } else {
                    $amount = $actual_amount - $point_value;
                    $point = 0;
                    $point_expence = $ALH_point;
                }
            } else {
                // for if package is for purchase points only
                $actual_amount = $package["Package"]["price"];
                $amount = $actual_amount;
                $point = $ALH_point;
                $point_expence = 0;
            }
            ?>
            <div class="em-sec-inner" style="width: 100%;">
                <form action="/Payments/payForPackage" method="POST" class="event-form" id = "payment_form">

                    <input type="hidden" name="package_id" value="<?php echo base64_decode($packageID); ?>">
                    <input type="hidden" name="user_id" value="<?php echo $userID; ?>">
                    <div class="emsi-part em-pa-lt">

                        <?php if ($amount != 0) { ?>
                            <label class="lbl-med"><strong>Select Card</strong></label>
                            <select class="sltbx-sm" name="card_id" id = "card_id" style="width: 100%;">
                                <?php foreach ($bil_info as $key => $value) { ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php } ?>
                                <option value="">Add New Card</option>
                            </select>
                        <?php } ?>
                        <?php if (!empty($bil_info)) { ?>
                            <div id = "card_detail" style="display: none;">
                            <?php } else {
                                ?>
                                <div id = "card_detail" style="display: block;">
                                <?php }
                                ?>
                                <label><strong>First Name</strong></label>
                                <input class="txtbx-bg validate[required,custom[onlyLetterSp]]" type=text  maxlength=32 name=firstname value="" placeholder="First Name">
                                <br> <br>

                                <label><strong>Last Name</strong></label>
                                <input class="txtbx-bg validate[required,custom[onlyLetterSp]]" type=text  maxlength=32 name=lastname value="" placeholder="Last Name">
                                <br> <br>

                                <label class="lbl-med"><strong>Card Type:</strong>&nbsp; &nbsp;</label>
                                <select class="sltbx-sm" name="credit_card_type" style="width: 100%;">
                                    <option value=Visa selected>Visa</option>
                                    <option value=MasterCard>Master Card</option>
                                    <option value=Discover>Discover</option>
                                    <option value=Amex>American Express</option>
                                </select>


                                <label><strong>Card Number:</strong></label>
                                <input class="txtbx-bg validate[required,custom[integer],custom[creditCard]]" type=text  maxlength=16 name=creditCardNumber value="" placeholder="Credit Card Number">
                                <br> <br>

                                <label class="lbl-med"><strong>Expiration Date:</strong>&nbsp; &nbsp;</label> 
                                <select class="sltbx-sm" name="expDateMonth">
                                    <option value=1>01</option>
                                    <option value=2>02</option>
                                    <option value=3>03</option>
                                    <option value=4>04</option>
                                    <option value=5>05</option>
                                    <option value=6>06</option>
                                    <option value=7>07</option>
                                    <option value=8>08</option>
                                    <option value=9>09</option>
                                    <option value=10>10</option>
                                    <option value=11>11</option>
                                    <option value=12>12</option>
                                </select>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <select class="" name="expDateYear">
                                    <option value=2014>2014</option>
                                    <option value=2015>2015</option>
                                    <option value=2016>2016</option>
                                    <option value=2017>2017</option>
                                    <option value=2018>2018</option>
                                    <option value=2019>2019</option>
                                    <option value=2020>2020</option>
                                    <option value=2021>2021</option>
                                    <option value=2022>2022</option>
                                    <option value=2023>2023</option>
                                    <option value=2024>2024</option>
                                </select>

                                <br> <br>
                            </div>
                            <?php if ($amount != 0) { ?>
                                <label><strong>Card Verification Number(CVV):</strong></label>
                                <input class="txtbx-bg validate[required,custom[integer]]" type=password maxlength=4 name=cvv2Number value="" placeholder="CVV Number">
                                <br> <br>
                            <?php } ?>
                            <label><strong>Package Amount USD:</strong></label>
                            <input class="txtbx-bg" type=text maxlength=100 name="actual_amount" value='<?php echo $package["Package"]["price"]; ?>' readonly="readonly">
                            <br> <br>

                            <input type="hidden" value="<?php echo $point; ?>" name = "point">
                            <input type="hidden" value="<?php echo $point_expence; ?>" name = "point_expence">
                        </div>
                        <div class="emsi-part em-pa-rt">

                            <label class="lbl-med"><strong>Select Address</strong></label>
                            <select class="sltbx-sm" name="address_id" id = "address_id" style="width: 100%;">
                                <?php foreach ($address as $key => $value) { ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php } ?>
                                <option value="">Add New Address</option>
                            </select>
                            <?php if (!empty($address)) { ?>
                                <div id = "address_detail" style="display: none;">
                                <?php } else { ?>
                                    <div id = "address_detail" style="display: block;">
                                    <?php } ?>
                                    <label><strong>Address Name:</strong></label>
                                    <input class="txtbx-bg validate[required,custom[onlyLetterSp]]" type=text maxlength=100 name=add_name value="" placeholder="Give this address a name for saving for future use">
                                    <br> <br>
                                    <label><strong>Address 1:</strong></label>
                                    <input class="txtbx-bg validate[required]" type=text maxlength=100 name=billing_address_1 value="" placeholder="Billing Address One">
                                    <br> <br>
                                    <label><strong>Address 2:</strong></label>
                                    <input class="txtbx-bg validate[required]" type=text maxlength=100 name=billing_address_2 value="" placeholder="Billing Address Two">
                                    <br> <br>
                                    <label><strong>City:</strong></label>
                                    <input class="txtbx-bg validate[required,custom[onlyLetterSp]]" type=text maxlength=100 name=city value="" placeholder="City">
                                    <br> <br>
                                    <label class="lbl-med"><strong>State:</strong>&nbsp; &nbsp;</label>
                                    <?php //pr($zip);die;?>
                                    <select class="sltbx-sm" id="state" name=state style="width: 100%;">
                                        <?php foreach ($zip as $key => $value) { ?>
                                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                        <?php } ?>

                                    </select>

                                    <label><strong>ZIP Code:</strong></label>
                                    <input class="txtbx-bg validate[required,custom[integer]]" type=text maxlength=6 name="zip" value="" placeholder="Zip">
                                    <br> <br>
                                    <label><strong>Country:</strong></label>
                                    <input class="txtbx-bg" type=text maxlength=100 name="country" value="United States" readonly="readonly">
                                    <br> <br>         
                                </div>

                                <label><strong>You need to pay(Adjusted price) USD:</strong></label>
                                <input class="txtbx-bg" type=text maxlength=100 name="amount" value='<?php echo $amount; ?>' readonly="readonly">
                                <br><br> 
                                <label><strong>&nbsp;&nbsp;</strong></label>
                                <input type=Submit value="Pay Now">
                                <br> <br>
                            </div>
                            </form>
                        </div>
                        <div class="clear"></div>
                    </div></div></section>
            <script>
                $("#payment_form").validationEngine();
                $("#card_id").change(function() {
                    var val = $("option:selected", this).val();
                    if (val.trim() == "") {
                        $("#card_detail").show();
                    } else {
                        $("#card_detail").hide();
                    }
                });
                $("#address_id").change(function() {
                    var val = $("option:selected", this).val();
                    if (val.trim() == "") {
                        $("#address_detail").show();
                    } else {
                        $("#address_detail").hide();
                    }
                });
            </script>
