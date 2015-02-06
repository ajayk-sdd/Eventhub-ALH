<!--Content Wrapper Starts-->
<section id="cont_wrapper">
    <!--Content Starts-->
    <section class="content">
        <!--Main Heading Starts-->
        <h1 class="main_heading"> <?php echo $this->Html->link('Event Template', '/admin/Templates/list'); ?></h1>
        <!--Main Heading Ends-->
        <!--Content Info Starts Here-->
        <section class="content_info">
            <?php
            echo $this->Session->flash();
            echo $this->Form->create("Template", array("action" => "create", "id" => "editEmailForm", 'enctype' => 'multipart/form-data'));
            echo $this->Form->input("EventTemplate.id", array("type" => "hidden"));
            ?>
            <ul class="form">
                <li>
                    <?php echo $this->Form->input("EventTemplate.name", array("class" => "validate[required,maxSize[40]] form_input", "div" => false, "label" => "Name:*")); ?>
                </li>
                <?php if ($this->data) { ?>
                    <li>
                        <label>Old Image</label>
                        <?php echo $this->html->image("template/large/" . $this->data["EventTemplate"]["image"]); ?>
                    </li>
                    <li>
                        <?php echo $this->Form->input("EventTemplate.image", array('class' => 'validate[checkFileType[jpg|jpeg|gif|JPG|png|PNG],checkFileSize[3000]] form_input', 'type' => 'file', "div" => false, 'label' => "Image:*")); ?>
                    </li>
                <?php } else { ?>
                    <li>
                        <?php echo $this->Form->input("EventTemplate.image", array('class' => 'validate[required,checkFileType[jpg|jpeg|gif|JPG|png|PNG],checkFileSize[3000]] form_input', 'type' => 'file', "div" => false, 'label' => "Image:*")); ?>
                    </li>
                <?php } ?>
                <li>
                    <?php echo $this->Form->input("EventTemplate.html", array('class' => 'validate[required] form_input', 'type' => 'textarea', "div" => false, 'label' => "Html:*","style"=>"height:400px; width:800px;")); ?>
                </li>
                <li>
                    <section class="select_new">

                        <?php
                        $status = array("0" => "Email Template", "2"=>"Multiple Event", "1" => "Single Event");
                        echo $this->Form->input("EventTemplate.type", array("type" => "select", "options" => $status, "class" => "", "div" => false, "label" => "Type:*"));
                        ?>
                    </section>
                </li>
                <li>
                    <section class="select_new">

                        <?php
                        $status = array("0" => "InActive", "1" => "Active");
                        echo $this->Form->input("EventTemplate.status", array("type" => "select", "empty" => "Select Status", "options" => $status, "class" => "", "div" => false, "label" => "Status:*", "default" => 1));
                        ?>
                    </section>
                </li>
                <section class="login_btn">
                    <span class="blu_btn_lt">
                        <?php echo $this->Form->input("Reset", array("type" => "reset", "label" => false, "div" => false, "class" => "blu_btn_rt")); ?>
                    </span>
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
<?php
//echo $this->Html->script("/js/admin/tiny_mce/tiny_mce");
echo $this->Html->script('/js/admin/CmsPages/admin_add');
?>

