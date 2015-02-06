
<section id="cont_wrapper">
    <!--Content Starts-->
    <section class="content">
        <!--Main Heading Starts-->
        <h1 class="main_heading"> <?php echo $this->Html->link('Point', '/admin/Points/list'); ?></h1>
        <!--Main Heading Ends-->
        <!--Conetnt Info Starts Here-->
        <section class="content_info">
            <?php
            echo $this->Session->flash();
            echo $this->Form->create("Point", array("action" => "add", "id" => "addBuyPoint"));
            echo $this->Form->input("BuyPoint.id", array("type" => "hidden"));
            ?>
            <ul class="form">
                <li>
                    <?php echo $this->Form->input("BuyPoint.no_of_point", array("type" => "text", "label" => "No Of Point:*", "div" => false, "class" => "validate[required,custom[integer]] form_input")); ?>
                </li>
                <li>
                    <?php echo $this->Form->input("BuyPoint.price", array("type" => "text", "label" => "Price:*", "div" => false, "class" => "validate[required,custom[price]] form_input")); ?>
                </li>
                <li>
                    <section class="select_new">

                        <?php
                        $status = array("0" => "InActive", "1" => "Active");
                        echo $this->Form->input("BuyPoint.status", array("type" => "select", "empty" => "Select Status", "options" => $status, "class" => "", "div" => false, "label" => "Status :*", "default" => 1));
                        ?>
                    </section>
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
        $("#addBuyPoint").validationEngine();
    });
</script>
