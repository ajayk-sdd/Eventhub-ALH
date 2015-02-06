<!--Content Wrapper Starts-->
<section id="cont_wrapper">
    <!--Content Starts-->
    <section class="content">
        <!--Main Heading Starts-->
        <h1 class="main_heading"><?php echo $this->Html->link('List Banner', '/admin/Events/listBanner'); ?></h1>
        <!--Main Heading Ends-->
        <!--Conetnt Info Starts Here-->
        <section class="content_info">
            <?php echo $this->Form->create("Event", array("action" => "addBanner", "id" => "AddRegionForm", 'enctype' => 'multipart/form-data')); ?>
            <?php
            echo $this->Form->input("BannerAdd.id", array("type" => "hidden"));
            echo $this->Form->input("BannerAdd.user_id", array("type" => "hidden", "value" => AuthComponent::user("id")));
            ?>
            <ul class="form">
                <li>
                    <?php echo $this->Form->input("BannerAdd.event_id", array("type" => "select", "label" => false, "div" => false, "label" => "Select Event :*", "class" => "validate[required] form_input", "id" => "event_id", "options" => $events)); ?>
                </li>
                <?php if (isset($this->data["BannerAdd"]["banner"])) {
                    ?>
                    <li>
                        <?php echo $this->html->image("BannerAdd/small/".$this->data["BannerAdd"]["banner"]); ?>
                    </li>
                <?php }
                ?>
                <li>
                    <?php echo $this->Form->input("BannerAdd.banner", array("type" => "file", "label" => false, "div" => false, "label" => "Banner :*", "class" => "validate[required] form_input", "id" => "banner")); ?>
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