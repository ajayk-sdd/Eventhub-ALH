
<section id="cont_wrapper">
    <!--Content Starts-->
    <section class="content">
        <!--Main Heading Starts-->
        <h1 class="main_heading"> <?php echo $this->Html->link('List Virtual Currency', '/admin/Packages/virtualCurrencyPackages'); ?></h1>
        <!--Main Heading Ends-->
        <!--Conetnt Info Starts Here-->
        <section class="content_info">
            <?php
            echo $this->Session->flash();
            echo $this->Form->create("Package", array("action" => "addVirtualCurrency", "id" => "addVirtualCurrency"));
            echo $this->Form->input("VirtualCurrency.id", array("type" => "hidden"));
            ?>
            <ul class="form">

                <li>
                    <?php echo $this->Form->input("VirtualCurrency.points", array("type" => "text", "label" => "Points:*", "div" => false, "class" => "validate[required] form_input", "id" => "Points")); ?>
                </li>
                <li>
                    <?php echo $this->Form->input("VirtualCurrency.price", array("type" => "text", "label" => "Price:*", "div" => false, "class" => "validate[required] form_input", "id" => "Price")); ?>
                </li>
                <li>
                    <section class="select_new">

                        <?php
                        $status = array("0" => "InActive", "1" => "Active");
                        echo $this->Form->input("status", array("type" => "select", "empty" => "Select Status", "options" => $status, "class" => "validate[required]", "div" => false, "label" => "Status :*", "default" => 1));
                        ?>
                    </section>
                </li>
                <li>
                    <section class="login_btn">

                        <span class="blu_btn_lt">
                            <?php echo $this->Form->input("Submit", array("type" => "submit", "label" => false, "div" => false, "class" => "blu_btn_rt")); ?>
                        </span>
                    </section>
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
<script type="text/javascript">
    $(document).ready(function() {
        $("#addPackage").validationEngine();
    });
</script>
