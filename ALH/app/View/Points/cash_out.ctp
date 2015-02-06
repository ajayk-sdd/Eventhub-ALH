<section class="inner-content">
    <div class="center-block">
        <div class="repo-list-wrap" style="max-width:none">
            <h1>Cashout Points</h1>
            <div class="repo-list reli-II em-sec-inner" style="width: 100%;">
                <?php
               
                echo $this->Form->create("Point", array("action" => "cashOut/".base64_encode($point_price), "id" => "cashOut", "class" => "event-form"));
                echo $this->Form->input("price", array("type" => "hidden", "div" => false,"label" => false, "value" => $point_price));
                echo $this->Form->input("point", array("type" => "hidden", "div" => false,"label" => false, "value" => $points));
                echo $this->Form->input("Userpoint.user_id", array("type" => "hidden", "div" => false,"label" => false, "value" => AuthComponent::User("id")));
                ?>
                <div class="emsi-part em-pa-lt">
                 <label><strong>My Points: <?php echo $points; ?> , Price: <?php echo "$".$point_price; ?></strong></label>
               
                 
                 <label><strong>Bank Account Detail:</strong></label>
                 <div style="margin-top: 10px; border: 1px solid rgb(225, 225, 225); padding: 12px;">
                 <label><strong>First Name<span class="red-star">*</span>:</strong></label>
                 <input class="txtbx-bg validate[required]" type=text  maxlength=32 name="bank_firstname" value="" placeholder="First Name">
                 <br> <br>
    
                 <label><strong>Last Name<span class="red-star">*</span>:</strong></label>
                 <input class="txtbx-bg validate[required]" type=text  maxlength=32 name="bank_lastname" value="" placeholder="Last Name">
                 <br> <br>
                                   
                 <label><strong>Bank Name<span class="red-star">*</span>:</strong></label>
                 <input class="txtbx-bg validate[required]" type=text maxlength=100 name="bank_name" value="" placeholder="Bank Name">
                 <br> <br>
                                    
                 <label><strong>Account Number<span class="red-star">*</span>:</strong></label>
                 <input class="txtbx-bg validate[required,custom[integer]]" type=text maxlength=100 name="account_number" value="" placeholder="Account Number">
                 <br> <br>
                 
                 <label><strong>IFSC Code<span class="red-star">*</span>:</strong></label>
                 <input class="txtbx-bg validate[required]" type=text maxlength=100 name="ifsc_code" value="" placeholder="IFSC Code">
                 <br> <br>
                 
                 <label><strong>Mode of Payment<span class="red-star">*</span>:</strong></label>
                 <input style="float:none;" class="validate[required]" type="radio" name="mode" id="mode1" value="check">Check &nbsp;&nbsp;&nbsp;&nbsp; <input class="validate[required]" style="float:none;" type="radio" name="mode" id="mode2" value="in account">In Account
               
                 </div>
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
                                <div id = "address_detail" style="display: none;margin-top: 10px; border: 1px solid rgb(225, 225, 225); padding: 12px;">
                                <?php } else { ?>
                                    <div id = "address_detail" style="display: block;margin-top: 10px; border: 1px solid rgb(225, 225, 225); padding: 12px;">
                                    <?php } ?>
                                    <label><strong>Address Name<span class="red-star">*</span>:</strong></label>
                                    <input class="txtbx-bg validate[required]" type=text maxlength=100 name="add_name" value="" placeholder="Give this address a name for saving for future use">
                                    <br> <br>
                                    
                                    <label><strong>First Name<span class="red-star">*</span>:</strong></label>
                                    <input class="txtbx-bg validate[required]" type=text  maxlength=32 name="firstname" value="" placeholder="First Name">
                                    <br> <br>
    
                                    <label><strong>Last Name<span class="red-star">*</span>:</strong></label>
                                    <input class="txtbx-bg validate[required]" type=text  maxlength=32 name="lastname" value="" placeholder="Last Name">
                                    <br> <br>
                                   
                                    <label><strong>Address 1<span class="red-star">*</span>:</strong></label>
                                    <input class="txtbx-bg validate[required]" type=text maxlength=100 name="billing_address_1" value="" placeholder="Billing Address One">
                                    <br> <br>
                                    
                                    <label><strong>Address 2<span class="red-star">*</span>:</strong></label>
                                    <input class="txtbx-bg validate[required]" type=text maxlength=100 name="billing_address_2" value="" placeholder="Billing Address Two">
                                    <br> <br>
                                   
                                    <label><strong>City<span class="red-star">*</span>:</strong></label>
                                    <input class="txtbx-bg validate[required]" type=text maxlength=100 name="city" value="" placeholder="City">
                                    <br> <br>
                                    
                                    <label class="lbl-med"><strong>State<span class="red-star">*</span>:</strong>&nbsp; &nbsp;</label>
                                    <?php //pr($zip);die;?>
                                    <select class="sltbx-sm" id="state" name="state" style="width: 100%;">
                                        <?php foreach ($zip as $key => $value) { ?>
                                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                        <?php } ?>

                                    </select>

                                    <label><strong>ZIP Code<span class="red-star">*</span>:</strong></label>
                                    <input class="txtbx-bg validate[required,custom[integer]]" type=text maxlength=6 name="zip" value="" placeholder="Zip">
                                    <br> <br>
                                    
                                    <label><strong>Country<span class="red-star">*</span>:</strong></label>
                                    <input class="txtbx-bg" type=text maxlength=100 name="country" value="United States" readonly="readonly">
                                    <br> <br>         
                                </div>

                               
                            </div>
                        
                <div style="float: left; padding-left: 15px;">
                    <input type="submit" value="Save Changes">
                    <input type="button" onclick="javascript:history.back();" value="Go Back"> 
                </div>
                <div class="clear"></div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</section>
 <script>
             $("#cashOut").validationEngine();
                
                $("#address_id").change(function() {
                    var val = $("option:selected", this).val();
                    if (val.trim() == "") {
                        $("#address_detail").show();
                    } else {
                        $("#address_detail").hide();
                    }
                });
            </script>
