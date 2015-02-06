<!--Content Wrapper Starts-->
<section id="cont_wrapper">
    <!--Content Starts-->
    <section class="content">
        <!--Main Heading Starts-->
        <h1 class="main_heading"> <?php echo $this->Html->link('List', '/admin/MyLists/listOffer'); ?></h1>
        <!--Main Heading Ends-->
        <!--Conetnt Info Starts Here-->
        <section class="content_info">
            <?php
            echo $this->Session->flash();
            ?>
            <ul class="form">
                <li>
                    <label>List Name :</label><label><?php echo $data['MyList']['list_name']; ?></label>
                </li>
                <li>
                    <label>Dedicated Email To Send :</label><label><?php echo $data['MakeOffer']['dedicated_email_to_send']; ?></label>
                </li>
                <li>
                    <label>Multi Event To Send :</label><label><?php echo $data['MakeOffer']['multi_event_to_send']; ?></label>
                </li>
                <li>
                    <label>Ticket Offered For Trade :</label><label><?php echo $data['MakeOffer']['ticket_offered_for_trade']; ?></label>
                </li>
                <li>
                    <label>Ticket Value :</label><label><?php echo $data['MakeOffer']['ticket_value']; ?></label>
                </li>
                <li>
                    <label>Adjusted Price :</label><label><?php echo $data['MakeOffer']['adjusted_price']; ?></label>
                </li>
                <li>
                    <label>Note To List Owner :</label><label><?php echo $data['MakeOffer']['note_to_list_owner']; ?></label>
                </li>
                <li>
                    <label>Created Date :</label><label><?php echo date("m/d/Y",  strtotime($data['MakeOffer']['created'])); ?></label>
                </li>
                <li>
                    <label>Status :</label><label><?php
                        if ($data['MakeOffer']['status'] == 1)
                            echo 'Active';
                        else
                            echo 'Inactive';
                        ?></label>
                </li>
                


                <li>
                    <span class="blu_btn_lt">
                        <input type="reset" id="MyListReset" class="blu_btn_rt" onclick="javascript:history.back();" value="Go Back"></span>
                </li>
            </ul>

        </section>
        <!--Conetnt Info Ends Here-->
    </section>
    <!--Content Ends-->
</section>
<!--Content Wrapper Ends-->
<?php echo $this->Html->script('/js/admin/MyList/admin_add'); ?>