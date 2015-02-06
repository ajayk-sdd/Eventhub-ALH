<!--Content Wrapper Starts-->
<section id="cont_wrapper">
    <!--Content Starts-->
    <section class="content">
        <!--Main Heading Starts-->
        <h1 class="main_heading"><?php echo $this->Html->link('List Banner', '/admin/CmsPages/listMainBanner'); ?></h1>
        <!--Main Heading Ends-->
        <!--Conetnt Info Starts Here-->
        <section class="content_info">
            <?php echo $this->Form->create("CmsPages", array("action" => "addMainBanner", "id" => "AddRegionForm", 'enctype' => 'multipart/form-data')); ?>
            <?php
            echo $this->Form->input("Banner.id", array("type" => "hidden"));
            echo $this->Form->input("Banner.type", array("type" => "hidden", "value" => 1));
            ?>
            <ul class="form">
                <?php if (isset($this->data["Banner"]["banner"])) {
                    ?>
                    <li>
                        <?php echo $this->html->image("MainBanner/small/" . $this->data["Banner"]["banner"]); ?>
                    </li>
                <?php }
                ?>
                <li>
                    <?php echo $this->Form->input("Banner.heading", array("type" => "text", "div" => false, "label" => "Heading :*", "class" => "validate[required] form_input", "id" => "heading")); ?>
                </li>
                <li>
                    <?php echo $this->Form->input("Banner.sub_heading", array("type" => "text", "div" => false, "label" => "Sub Heading :*", "class" => "validate[required] form_input", "id" => "sub_heading")); ?>
                </li>
                <li>
                    <?php 
                    if(!isset($this->data["Banner"]["id"]))
                    echo $this->Form->input("Banner.banner", array("type" => "file", "div" => false, "label" => "Banner :*", "class" => "validate[required] form_input", "id" => "banner"));
                    else
                    echo $this->Form->input("Banner.banner", array("type" => "file", "div" => false, "label" => "Banner :*", "class" => "form_input", "id" => "banner"));
                    ?>
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
<?php echo $this->Html->script('/js/admin/Regions/admin_add'); ?>