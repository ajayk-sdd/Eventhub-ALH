<?php
//pr($detail);
//die;
?>
<!--Content Wrapper Starts-->
<section id="cont_wrapper">
    <!--Content Starts-->
    <section class="content">
        <!--Main Heading Starts-->
        <h1 class="main_heading"> <?php echo $this->Html->link('List', '/admin/Sales/list'); ?></h1>
        <!--Main Heading Ends-->
        <!--Conetnt Info Starts Here-->
        <section class="content_info">
            <?php
            echo $this->Session->flash();
            //echo $this->Form->create("Payment", array("action"=>"add","id"=>"addPaymentForm"));
            //echo $this->Form->input("list", array("type"=>"hidden","div"=>false,"value"=>"roles"));
            //echo $this->Form->input("Payment.id", array("type"=>"hidden"));
            ?>
            <ul class="form">
                <li>
                    <label>Package Name :</label><label><?php echo $detail['Package']['name']; ?></label>
                </li>
                <li>
                    <label>Package Description :</label><label><?php echo $detail['Package']['description']; ?></label>
                </li>
                <li>
                    <label>Package Price :</label><label><?php echo $detail['Payment']['amount']; ?></label>
                </li>
                <li>
                    <?php
                    ?>
                    <label>Package Services :</label><label><?php
                        foreach ($detail['Package']['Service'] as $package_service) {
                            echo $package_service['name'] . '<br>';
                        };
                        ?></label>
                </li>
                <li>
                    <label>Transaction Id :</label><label><?php echo $detail['Payment']['transaction_id']; ?></label>
                </li>
                <li>
                    <label>First Name :</label><label><?php echo $detail['Payment']['first_name']; ?></label>
                </li>
                <li>
                    <label>Last Name :</label><label><?php echo $detail['Payment']['last_name']; ?></label>
                </li>
                <li>
                    <label>Billing Address :</label><label><?php echo $detail['Payment']['billing_address_1'] . "," . $detail['Payment']['billing_address_1']; ?></label>
                </li>
                <li>
                    <label>City :</label><label><?php echo $detail['Payment']['city']; ?></label>
                </li>
                <li>
                    <label>State :</label><label><?php echo $detail['Payment']['state']; ?></label>
                </li>
                <li>
                    <label>Zip :</label><label><?php echo $detail['Payment']['zip']; ?></label>
                </li>
                <li>
                    <label>Order Date-Time :</label><label><?php echo date('l, F d, Y H:i A', strtotime($detail['Payment']['created'])); ?></label>
                </li>
                <li>
                    <label>Username :</label><label><?php echo $detail['User']['username']; ?></label>
                </li>
                <li>
                    <label>Email :</label><label><?php echo $detail['User']['email']; ?></label>
                </li>






                <li>
                    <span class="blu_btn_lt">
                        <input type="reset" id="PaymentReset" class="blu_btn_rt" onclick="javascript:history.back();" value="Go Back"></span>
                </li>
            </ul>

            <?php echo $this->Form->end(); ?>
            <section class="clr_bth"></section>
        </section>
        <!--Conetnt Info Ends Here-->
    </section>
    <!--Content Ends-->
</section>
<!--Content Wrapper Ends-->
<?php echo $this->Html->script('/js/admin/Payment/admin_add'); ?>