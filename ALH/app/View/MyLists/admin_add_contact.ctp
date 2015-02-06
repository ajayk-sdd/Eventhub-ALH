<?php //pr($this->data);            ?>
<!--Content Wrapper Starts-->
<section id="cont_wrapper">
    <!--Content Starts-->
    <section class="content">
        <!--Main Heading Starts-->
        <h1 class="main_heading"> <?php echo $this->Html->link('Lists', '/admin/MyLists/list'); ?></h1>
        <!--Main Heading Ends-->
        <!--Conetnt Info Starts Here-->
        <section class="content_info">
            <?php
            echo $this->Session->flash();
            echo $this->Form->create("MyList", array("action" => "addContact/".  base64_encode($list_id)."/".base64_encode($listemail), "id" => "addListForm", 'enctype' => 'multipart/form-data'));
            echo $this->Form->input("ListEmail.my_list_id", array("type" => "hidden","value" => $list_id));
            echo $this->Form->input("ListEmail.id", array("type" => "hidden"));
            ?>
            <ul class="form">
                <li>
                    <?php echo $this->Form->input("ListEmail.email", array("type" => "text", "label" => "Email-id:*", "div" => FALSE, "class" => "validate[required,custom[email]] form_input", "id" => "email")); ?>
                </li>
                <li>
                    <?php echo $this->Form->input("ListEmail.first_name", array("type" => "text", "label" => "First Name:*", "div" => false, "class" => "validate[required] form_input ", "id" => "fname")); ?>
                </li>
                <li>
                    <?php echo $this->Form->input("ListEmail.last_name", array("type" => "text", "label" => "Last Name:*", "div" => false, "class" => "validate[required] form_input ", "id" => "lname")); ?>
                </li>
                <li>
                    <?php echo $this->Form->input("ListEmail.phone", array("type" => "text", "label" => "Phone No.:*", "div" => false, "class" => "validate[required] form_input ", "id" => "phone_no")); ?>
                </li>
                
           
            <section class="login_btn">
                <span class="blu_btn_lt">
                    <input type="button" onclick="javascript:history.back();" value="Go Back" class="blu_btn_rt">
                </span>
                <span class="blu_btn_lt">
                    <?php echo $this->Form->input("Reset", array("type" => "reset", "label" => false, "div" => false, "class" => "blu_btn_rt")); ?>
                </span>
                <span class="blu_btn_lt">
                    <?php echo $this->Form->input("Submit", array("type" => "submit", "label" => false, "div" => false, "class" => "blu_btn_rt")); ?>
                </span>
            <?php echo $this->Form->end(); ?>
            </section>
            </ul>


            <section class="clr_bth"></section>
        </section>
        <!--Conetnt Info Ends Here-->
    </section>
    <!--Content Ends-->
</section>
<!--Content Wrapper Ends-->
<?php
echo $this->Html->script("/js/admin/tiny_mce/tiny_mce");
echo $this->Html->script('/js/admin/List/admin_add');
?>
