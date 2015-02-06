<?php
//pr($mylist);
//die;
?>
<!--Content Wrapper Starts-->
<section id="cont_wrapper">
    <!--Content Starts-->
    <section class="content">
        <!--Main Heading Starts-->
        <h1 class="main_heading"> <?php echo $this->Html->link('List', '/admin/MyList/list'); ?></h1>
        <!--Main Heading Ends-->
        <!--Conetnt Info Starts Here-->
        <section class="content_info">
            <?php
            echo $this->Session->flash();
            //echo $this->Form->create("MyList", array("action"=>"add","id"=>"addMyListForm"));
            //echo $this->Form->input("list", array("type"=>"hidden","div"=>false,"value"=>"roles"));
            //echo $this->Form->input("MyList.id", array("type"=>"hidden"));
            ?>
            <ul class="form">
                <li>
                    <label>List Name :</label><label><?php echo $mylist['MyList']['list_name']; ?></label>
                </li>
                <li>
                    <label>List Owner :</label><label><?php echo $mylist['User']['username']; ?></label>
                </li>
                <li>
                    <label>Dedicated Send Points :</label><label><?php echo $mylist['MyList']['dedicated_send_points']; ?></label>
                </li>
                <li>
                    <label>Multi Event Points :</label><label><?php echo $mylist['MyList']['multi_event_points']; ?></label>
                </li>
                <li>
                    <label>Max Email Per Week :</label><label><?php echo $mylist['MyList']['max_email_per_week']; ?></label>
                </li>
                <li>
                    <label>Status :</label><label><?php
                        if ($mylist['MyList']['status'] == 1)
                            echo 'Active';
                        else
                            echo 'Inactive';
                        ?></label>
                </li>
                <li><label>Email :</label></li>
                <?php foreach ($mylist["ListEmail"] as $listemail) { ?>
                    <li><label></label>
                        <label><?php
                            echo $listemail["email"];
                            ?></label>
                    </li>
                <?php } ?>
                <li><label>Category :</label></li>
                <?php foreach ($mylist["ListCategory"] as $listcategory) { ?>
                    <li><label></label>
                        <label><?php
                            echo $listcategory["Category"]["name"];
                            ?></label>
                    </li>
                <?php } ?>
                <li><label>Vibe :</label></li>
                <?php foreach ($mylist["ListVibe"] as $listvibe) { ?>
                    <li><label></label>
                        <label><?php
                            echo $listvibe["Vibe"]["name"];
                            ?></label>
                    </li>
                <?php } ?>
                <li><label>Region :</label></li>
                <?php foreach ($mylist["ListRegion"] as $listregion) { ?>
                    <li><label></label>
                        <label><?php
                            echo $listregion["Region"]["name"];
                            ?></label>
                    </li>
                <?php } ?>


                <li>
                    <span class="blu_btn_lt">
                        <input type="reset" id="MyListReset" class="blu_btn_rt" onclick="javascript:history.back();" value="Go Back"></span>
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
<?php echo $this->Html->script('/js/admin/MyList/admin_add'); ?>