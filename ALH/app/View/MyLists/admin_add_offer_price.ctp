<?php //pr($this->data);          ?>
<!--Content Wrapper Starts-->
<section id="cont_wrapper">
    <!--Content Starts-->
    <section class="content">
        <!--Main Heading Starts-->
        <h1 class="main_heading"> <?php echo $this->Html->link('Offer Price List', '/admin/MyLists/listOfferPrice'); ?></h1>
        <!--Main Heading Ends-->
        <!--Conetnt Info Starts Here-->
        <section class="content_info">
            <?php
            echo $this->Session->flash();
            echo $this->Form->create("MyList", array("action" => "addOfferPrice", "id" => "addListForm", 'enctype' => 'multipart/form-data'));
            echo $this->Form->input("OfferPrice.id", array("type" => "hidden"));
            ?>
            <ul class="form" style="float: none;">
                <li>
                    <?php echo $this->Form->input("OfferPrice.my_list_id", array('type' => 'select', 'empty' => 'Select', 'options' => $lists, 'div' => false, 'label' => "List", 'class' => 'validate[required] form_input')); ?>
                </li>
              
                <li>
                    <?php echo $this->Form->input("OfferPrice.dedicated_email_to_send", array("type" => "text", "label" => "Dedicated Email To Send:*", "div" => false, "class" => "validate[required,custom[integer]] form_input", "id" => "specify")); ?>
                </li>
                <li>
                    <?php echo $this->Form->input("OfferPrice.multi_event_to_send", array("type" => "text", "label" => "Multi Event To Send:*", "div" => false, "class" => "validate[required,custom[integer]] form_input")); ?>
                </li>
                <li>
                    <?php echo $this->Form->input("OfferPrice.ticket_offered_for_trade", array("type" => "text", "label" => "Ticket Offered For Trade:*", "div" => false, "class" => "validate[required,custom[integer]] form_input")); ?>
                </li>
               

                <section class="login_btn">
                    <span class="blu_btn_lt">
                        <?php echo $this->Form->input("Reset", array("type" => "reset", "label" => false, "div" => false, "class" => "blu_btn_rt")); ?>
                    </span>
                    <span class="blu_btn_lt">
                        <?php echo $this->Form->input("Submit", array("type" => "submit", "label" => false, "div" => false, "class" => "blu_btn_rt")); ?>
                    </span>
                </section>
               
                    <?php
                    echo $this->Form->end();
                
                ?>
               
            </ul>


        <!--Conetnt Info Ends Here-->
    </section>
    <!--Content Ends-->
</section>
<!--Content Wrapper Ends-->
<?php
echo $this->Html->script("/js/admin/tiny_mce/tiny_mce");
echo $this->Html->script('/js/admin/List/admin_add');
?>