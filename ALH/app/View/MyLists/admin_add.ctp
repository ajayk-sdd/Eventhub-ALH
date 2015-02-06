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
            echo $this->Form->create("MyList", array("action" => "add", "id" => "addListForm", 'enctype' => 'multipart/form-data'));
            echo $this->Form->input("MyList.id", array("type" => "hidden"));
            ?>
            <ul class="form">
                <li>
                    <?php echo $this->Form->input("MyList.user_id", array('type' => 'select', 'empty' => 'Select', 'options' => $user, 'div' => false, 'label' => "Owner", 'class' => 'validate[required] form_input')); ?>
                </li>
                <li>
                    <?php echo $this->Form->input("MyList.list_name", array("type" => "text", "label" => "List Name:*", "div" => false, "class" => "validate[required,maxSize[40]] form_input", "id" => "list_name")); ?>
                </li>
                <li>
                    <?php echo $this->Form->input("MyList.from_email", array("type" => "text", "label" => "From Email:*", "div" => false, "class" => "validate[required,custom[email],maxSize[50]] form_input", "id" => "from_email")); ?>
                </li>
                <li>
                    <?php echo $this->Form->input("MyList.from_name", array("type" => "text", "label" => "From Name:*", "div" => false, "class" => "validate[required,maxSize[40]] form_input", "id" => "from_name")); ?>
                </li>
                <li>
                    <?php echo $this->Form->input("MyList.remind_me", array("type" => "text", "label" => "Remind Me About List:*", "div" => false, "class" => "validate[required,maxSize[200]] form_input", "id" => "remind_me")); ?>
                </li>
                <li>
                    <?php echo $this->Form->input("MyList.contact_information", array("type" => "text", "label" => "Contact Information:*", "div" => false, "class" => "validate[required,maxSize[200]] form_input", "id" => "contact_information")); ?>
                </li>
                <li>
                    <?php echo $this->Form->input("MyList.list_status", array('type' => 'select', 'empty' => 'Select', 'options' => array("Public" => "Public", "Private" => "Private", "public - Pending Approval" => "public - Pending Approval"), 'div' => false, 'label' => "Status", 'class' => 'validate[required] form_input')); ?>
                </li>
            <section class="login_btn">
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
