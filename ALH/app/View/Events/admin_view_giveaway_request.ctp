<!--Content Wrapper Starts-->
<section id="cont_wrapper">
    <!--Content Starts-->
    <section class="content">
        <!--Main Heading Starts-->
        <h1 class="main_heading"> <?php echo "View Event Detail"; ?></h1>
        <!--Main Heading Ends-->
        <!--Conetnt Info Starts Here-->
        <section class="content_info">
            <?php
            echo $this->Session->flash();
            ?>
            <ul class="form">
                <li>
                    <label>Event Title :</label><label><?php echo $data['Event']['title']; ?></label>
                </li>
                <li>
                    <label>Event Sub-title :</label><label><?php echo $data['Event']['sub_title']; ?></label>
                </li>
                <li>
                    <label>Start Date :</label><label><?php echo $data['Event']['start_date']; ?></label>
                </li> <li>
                    <label>End Date :</label><label><?php echo $data['Event']['end_date']; ?></label>
                </li> 

                <li>
                    <label>First Name :</label><label><?php echo $data['TicketGiveaway']['first_name']; ?></label>
                </li>
                <li>
                    <label>Last Name :</label><label><?php echo $data['TicketGiveaway']['last_name']; ?></label>
                </li>
                <li>
                    <label>Email :</label><label><?php echo $data['TicketGiveaway']['email']; ?></label>
                </li>
                <li>
                    <label>Zip :</label><label><?php echo $data['TicketGiveaway']['zip']; ?></label>
                </li> 
                <li>
                    <label>Phone :</label><label><?php echo $data['TicketGiveaway']['phone']; ?></label>
                </li> 
                <li>
                    <label>Why I want to join this event :</label><label><?php echo $data['TicketGiveaway']['reason']; ?></label>
                </li> <li>
                    <label>Status :</label><label><?php
                        if ($data['TicketGiveaway']['status'] == 1)
                            echo 'Active';
                        else
                            echo 'Inactive';
                        ?></label>
                </li>
                <li>
                    <span class="blu_btn_lt">
                        <input type="reset" id="EventReset" class="blu_btn_rt" onclick="javascript:history.back();" value="Go Back"></span>
                </li>
            </ul>

            
            <section class="clr_bth"></section>
        </section>
        <!--Conetnt Info Ends Here-->
    </section>
    <!--Content Ends-->
</section>
<!--Content Wrapper Ends-->