
<section id="cont_wrapper">
    <!--Content Starts-->
    <section class="content">
        <!--Main Heading Starts-->
        <h1 class="main_heading"> <?php echo $this->Html->link('Cashout Point Requests List', '/admin/Points/cashoutList'); ?></h1>
        <!--Main Heading Ends-->
        <!--Conetnt Info Starts Here-->
        <section class="content_info">
            <?php
            echo $this->Session->flash();
            echo $this->Form->create("Point", array("action" => "editRequest", "id" => "editRequest"));
            echo $this->Form->input("CashOutPoint.id", array("type" => "hidden"));
            ?>
            <ul class="form">
                <li>
                    <?php echo $this->Form->input("CashOutPoint.point", array("type" => "text", "label" => "No Of Point:*", "div" => false, "class" => "validate[required] form_input")); ?>
                </li>
                <li>
                    <?php echo $this->Form->input("CashOutPoint.price", array("type" => "text", "label" => "Price:*", "div" => false, "class" => "validate[required] form_input")); ?>
                </li>
                 <li>
                     <?php
                    $options = array("In Progress" => "In Progress", "Completed" => "Completed", "Declined" => "Declined");
                    echo $this->Form->input("CashOutPoint.status", array("type" => "select", "div" => false, "label" => "Status :*", "class" => "validate[required] form_input", "id" => "size", "options" => $options, "empty" => "Select Status"));
                    ?>
                 </li>
                <li>
                    <?php echo $this->Form->input("CashOutPoint.date", array("type" => "text", "label" => "Date:*", "div" => false, "class" => "validate[required] form_input")); ?>  <span>YYYY-MM-DD</span>    
                </li>
                 <div><b>Bank Account Details:</b><br><br></div>
                 <li>
                    <?php echo $this->Form->input("CashOutPoint.bank_user_fname", array("type" => "text", "label" => "User First Name:*", "div" => false, "class" => "validate[required] form_input")); ?>
                </li>
                <li>
                    <?php echo $this->Form->input("CashOutPoint.bank_user_lname", array("type" => "text", "label" => "User Last Name:*", "div" => false, "class" => "validate[required] form_input")); ?>
                </li>
                 <li>
                    <?php echo $this->Form->input("CashOutPoint.bank_name", array("type" => "text", "label" => "Bank Name:*", "div" => false, "class" => "validate[required] form_input")); ?>
                </li>
                <li>
                    <?php echo $this->Form->input("CashOutPoint.account_no", array("type" => "text", "label" => "Account Number:*", "div" => false, "class" => "validate[required] form_input")); ?>
                </li>
                 <li>
                    <?php echo $this->Form->input("CashOutPoint.ifsc_code", array("type" => "text", "label" => "IFSC Code:*", "div" => false, "class" => "validate[required] form_input")); ?>
                </li>
                <li>
                    <?php
                    $options_pay = array("check" => "check", "in account" => "in account");
                    echo $this->Form->input("CashOutPoint.pay_mode", array("type" => "select", "div" => false, "label" => "Payment Mode :*", "class" => "validate[required] form_input", "id" => "size", "options" => $options_pay, "empty" => "Select Payment Mode"));
                    ?>
                     </li>
                  <div><b>User Address Details:</b><br><br></div>
                 <li>
                    <?php echo $this->Form->input("CashOutPoint.address_fname", array("type" => "text", "label" => "First Name:*", "div" => false, "class" => "validate[required] form_input")); ?>
                </li>
                <li>
                    <?php echo $this->Form->input("CashOutPoint.address_lname", array("type" => "text", "label" => "Last Name:*", "div" => false, "class" => "validate[required] form_input")); ?>
                </li>
                 <li>
                    <?php echo $this->Form->input("CashOutPoint.address1", array("type" => "text", "label" => "Address1:*", "div" => false, "class" => "validate[required] form_input")); ?>
                </li>
                <li>
                    <?php echo $this->Form->input("CashOutPoint.address2", array("type" => "text", "label" => "Address2:*", "div" => false, "class" => "validate[required] form_input")); ?>
                </li>
                 <li>
                    <?php echo $this->Form->input("CashOutPoint.city", array("type" => "text", "label" => "City:*", "div" => false, "class" => "validate[required] form_input")); ?>
                </li>
                <li>
                    <?php echo $this->Form->input("CashOutPoint.state", array("type" => "text", "label" => "State:*", "div" => false, "class" => "validate[required] form_input")); ?>
                </li>
                 <li>
                    <?php echo $this->Form->input("CashOutPoint.zip_code", array("type" => "text", "label" => "Zip Code:*", "div" => false, "class" => "validate[required] form_input")); ?>
                </li>
                <li>
                    <?php echo $this->Form->input("CashOutPoint.country", array("type" => "text", "label" => "Country:*", "div" => false, "class" => "validate[required] form_input")); ?>
                </li>
                <section class="login_btn">

                    <span class="blu_btn_lt">
                        <?php echo $this->Form->input("Submit", array("type" => "submit", "label" => false, "div" => false, "class" => "blu_btn_rt")); ?>
                    </span>
                </section>

            </ul>

            <?php echo $this->Form->end(); ?>
            <section class="clr_bth"></section>
        </section>
        <!--Conetnt Info Ends Here-->
    </section>
    <!--Content Ends-->
</section>
<!--Content Wrapper Ends-->
<script type="text/javascript">
    $(document).ready(function() {
        $("#editRequest").validationEngine();
    });
</script>
