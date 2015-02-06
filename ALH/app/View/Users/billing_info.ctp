<style type="text/css">
    a.tooltips {
        position: relative;
        display: inline;
    }
    a.tooltips span {
        position: absolute;
        width:380px;
        color: #FFFFFF;
        background: #000000;
        height: 30px;
        line-height: 30px;
        text-align: center;
        visibility: hidden;
        border-radius: 6px;
    }
    a.tooltips span:after {
        content: '';
        position: absolute;
        top: 50%;
        right: 100%;
        margin-top: -8px;
        width: 0; height: 0;
        border-right: 8px solid #000000;
        border-top: 8px solid transparent;
        border-bottom: 8px solid transparent;
    }
    a:hover.tooltips span {
        visibility: visible;
        opacity: 0.8;
        left: 100%;
        top: 50%;
        margin-top: -15px;
        margin-left: 15px;
        z-index: 999;
    }

</style>

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
                <li>
                    <?php echo $this->Html->link('List Subscriptions', '/brands/brandList'); ?>
                </li>
                <li class="active">
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

                    <?php if (isset($new)) { ?>
                        <div class="repo-list-wrap">
                        <?php } else { ?>
                            <div class="repo-list-wrap" style="display:none;">
                            <?php } ?>
                            <div class="repo-list reli-II brnd-detail">
                                <?php
                                echo $this->Session->flash();
                                echo $this->Form->create("User", array("action" => "BillingInfo", "id" => "billingInfo", "class" => "event-form"));
                                echo $this->Form->input("BillingInfo.id", array("type" => "hidden"));
                                ?>
                                <dl>
                                    <dt>Card Type: </dt>
                                    <dd>
                                        <?php
                                        $card_type = array('MasterCard' => 'Master Card', 'Visa' => 'Visa', 'Discover' => 'Discover', 'Amex' => 'American Express');
                                        echo $this->Form->input('BillingInfo.card_type', array("label" => false, "div" => false, 'type' => 'select', 'options' => $card_type, 'empty' => 'Select Card', "class" => "validate[required]"));
                                        ?>
                                    </dd>

                                    <dt>Name on Card: </dt>
                                    <dd>
                                        <?php
                                        echo $this->Form->input("BillingInfo.name_on_card", array("type" => "text", "label" => false, "div" => false, "class" => "validate[required] form_input", "id" => "cardname"));
                                        ?>
                                    </dd>

                                    <dt>Card No.: </dt>
                                    <dd>
                                        <?php
                                        echo $this->Form->input("BillingInfo.card_no", array("type" => "text", "label" => false, "div" => false, "class" => "validate[required,custom[integer]] form_input", "id" => "cardno", "maxlength" => "16"));
                                        ?>
                                    </dd>
                                    <dt>CVV:</dt>
                                    <dd>
                                        <?php
                                        echo $this->Form->input("BillingInfo.security_no", array("type" => "password", "label" => false, "div" => false, "class" => "validate[required,custom[integer]] form_input", "id" => "sno", "maxlength" => "4"));
                                        ?>
                                        <a class="tooltips" href="#">What's this ?<span>Three (3) digit number on the back side of your card. </span></a>

                                    </dd>
                                    <dt>Expr. Date:</dt>
                                    <dd>
                                        <?php
                                        $options = array('01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05', '06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11', '12' => '12');
                                        echo $this->Form->input('BillingInfo.exp_month', array("label" => false, "div" => false, 'type' => 'select', 'options' => $options, 'empty' => 'Select Month', "class" => "validate[required]"));

                                        $year = array('2014' => '2014', '2015' => '2015', '2016' => '2016', '2017' => '2017', '2018' => '2018', '2019' => '2019', '2020' => '2020');
                                        echo $this->Form->input('BillingInfo.exp_year', array("label" => false, "div" => false, 'type' => 'select', 'options' => $year, 'empty' => 'Select Year', "class" => "validate[required]"));
                                        ?>
                                    </dd>
                                    <dt>Billing Address: </dt>
                                    <dd>
                                        <?php
                                        echo $this->Form->input("BillingInfo.billing_address_1", array("type" => "text", "label" => false, "div" => false, "class" => "validate[required] form_input", "id" => "b_add_1"));
                                        ?>
                                    </dd>
                                    <dt>&nbsp;</dt>
                                    <dd>
                                        <?php
                                        echo $this->Form->input("BillingInfo.billing_address_2", array("type" => "text", "label" => false, "div" => false, "class" => "validate[required] form_input", "id" => "b_add_2"));
                                        ?>
                                    </dd>
                                    <dt>Zip Code: </dt>
                                    <dd>
                                        <?php
                                        echo $this->Form->input("BillingInfo.zip_code", array("type" => "text", "label" => false, "div" => false, "class" => "validate[required,custom[integer]] form_input", "id" => "zip", "maxlength" => "6"));
                                        ?>
                                    </dd>
                                    <dt>State: </dt>
                                    <dd>
                                        <?php
                                        $state = $zip;
                                        echo $this->Form->input('BillingInfo.state_id', array("label" => false, "div" => false, 'type' => 'select', 'options' => $state, 'empty' => 'Select State', "class" => "validate[required]"));
                                        ?>
                                    </dd>
                                </dl>

                                <div class="go-back">
                                    <input type="submit" value="Submit" id="sub_cart">
                                </div>
                                <?php echo $this->Form->end(); ?>
                                <div class="clear"></div>

                            </div>
                            <?php if (isset($new)) { ?>
                            </div>
                        <?php } else { ?>
                        </div>
                    <?php } ?>
                    <div class="clear"></div>
                    <a href="javascript:void(0);" id="addNew" class="btn-all">Add New Card</a>
                    <div class="clear"></div>
                    <div class="clm-wrap">

                        <table>
                            <thead>
                                <tr>
                                    <th style="width:15%;">Card Type</th>
                                    <th style="width:20%;">Name on Card</th>
                                    <th style="width:22%;">Card Number</th>
                                    <th style="width:20%;">Expiration Date</th>
                                    <th style="width:15%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($cardData)) {
                                    foreach ($cardData as $data):
                                        ?>
                                        <tr>
                                            <td><?php echo $data['BillingInfo']['card_type']; ?></td>
                                            <td><?php echo $data['BillingInfo']['name_on_card']; ?></td>
                                            <td><?php echo "XXXX-XXXX-XXXX-" . substr($data['BillingInfo']['card_no'], -4, 4); ?></td>
                                            <td><?php echo $data['BillingInfo']['exp_month'] . ' - ' . $data['BillingInfo']['exp_year']; ?></td>
                                            <td>
                                                <?php echo $this->Html->link('Edit', array("controller" => "Users", "action" => "BillingInfo", "update",base64_encode($data['BillingInfo']['id'])), array('escape' => false, 'title' => 'Edit', 'class' => "edidlt"));

                                                echo $this->Html->link('Delete', array("controller" => "Commons", "action" => "Delete", base64_encode($data['BillingInfo']['id']), 'BillingInfo', 'admin' => true), array('escape' => false, 'title' => 'Delete', 'class' => "edidlt", 'onclick' => "return confirm('Are you sure you want to delete this card ?');"));
                                                ?>
                                            </td>    
                                        </tr>
                                    <?php endforeach;
                                }else {
                                    ?>
                                    <tr><td colspan='8' style="text-align:center;">No Card Added</td></tr>
<?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="clear"></div>
                </div>
            </div></div></div>



</section>

<script type="text/javascript">
    $(document).ready(function() {
        $("#billingInfo").validationEngine();
        $('#addNew').click(function() {
            window.location.href = "/Users/BillingInfo/new";
            // window.location.reload(true);
        });
    $('#sub_cart').click(function() {
      
        setTimeout(function () {
        if ($(".formError").length>0){
            $('#sub_cart').attr( "alt", "submit"); 
        }
        else
        {
            $('#sub_cart').attr( "disabled", "disabled"); 
                
        }
         }, 500);
        });
    });
</script>
