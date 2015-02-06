<!--Content Wrapper Starts-->
<section id="cont_wrapper">
    <!--Content Starts-->
    <section class="content">
        <!--Main Heading Starts-->
        <h1 class="main_heading"> <?php echo $this->Html->link('Brands', '/admin/Brands/add'); ?></h1>
        <!--Main Heading Ends-->
        <!--Conetnt Info Starts Here-->
        <section class="content_info">
            <?php
            echo $this->Session->flash();
            //echo $this->Form->create("Brand", array("action"=>"add","id"=>"addBrandForm"));
            //echo $this->Form->input("list", array("type"=>"hidden","div"=>false,"value"=>"roles"));
            //echo $this->Form->input("Brand.id", array("type"=>"hidden"));
            ?>
            <ul class="form">
                <li>
                    <label>Name :</label><label><?php echo $brand['Brand']['name']; ?></label>
                </li>
                <li>
                    <label>Username :</label><label><?php echo $brand['User']['username']; ?></label>
                </li>



                <li>
                    <label>Logo :</label><label><?php echo $this->html->image("brand/small/" . $brand['Brand']['logo']); ?></label>
                </li> 

                <li>
                    <label>Description :</label><label><?php echo $brand['Brand']['description']; ?></label>
                </li>

                <li>
                    <label>Status :</label><label><?php
                        if ($brand['Brand']['status'] == 1)
                            echo 'Active';
                        else
                            echo 'Inactive';
                        ?></label>
                </li>



                <li>
                    <span class="blu_btn_lt">
                        <input type="reset" id="BrandReset" class="blu_btn_rt" onclick="javascript:window.back();" value="Go Back"></span>
                </li>
            </ul>

            <?php //echo $this->Form->end(); ?>
            <section class="clr_bth"></section>
        </section>
        <!--Conetnt Info Ends Here-->
    </section>
    <!--Content Ends-->
</section>
<!--Content Wrapper Ends-->
<?php echo $this->Html->script('/js/admin/Brands/admin_add'); ?>