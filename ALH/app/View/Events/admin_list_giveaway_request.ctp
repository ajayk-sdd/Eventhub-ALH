<?php
//pr($datas);
//die;
?>
<script>
    function go_to_csv() {
        window.location.assign("/admin/events/ticketGiveawayCsv");
    }
</script>
<?php //pr($datas);die;   ?>
<section id="cont_wrapper">
    <section class="content">
        <h1 class="main_heading"><?php echo "Ticket Giveaway Request Listing"; ?></h1>
        <section class="content_info">
            <?php echo $this->Session->flash(); ?>
            <section class="tbldata">
                <div class="srch_box">
                    <!------------------------------------ search starts --------------------->
                    <?php
                    echo $this->Form->create("Event", array("action" => "listGiveawayRequest/" . $event_id, 'enctype' => 'multipart/form-data', "novalidate" => "novalidate"));
                    echo $this->Form->input('TicketGiveaway.first_name', array('label' => "Name", 'div' => false, 'maxlength' => '50', 'autocomplete' => 'off', 'class' => 'form_input', 'placeholder' => 'Enter First Or Last Name'));
                    echo $this->Form->input('TicketGiveaway.email', array('label' => "Email", 'div' => false, 'maxlength' => '50', 'autocomplete' => 'off', 'class' => 'form_input', 'placeholder' => 'Enter Email'));
                    echo $this->Form->input('TicketGiveaway.zip', array('label' => "Zip", 'div' => false, 'maxlength' => '50', 'autocomplete' => 'off', 'class' => 'form_input', 'placeholder' => 'Enter Zip'));
                    echo $this->Form->input("TicketGiveaway.limit", array('type' => 'select', 'options' => array("10" => "10", "20" => "20", "50" => "50", "100" => "100"), 'div' => false, 'label' => "Show Per Page:"));
                    ?>
                    <?php
                    echo $this->Form->input("TicketGiveaway.order", array('type' => 'select', 'options' => array("TicketGiveaway.first_name" => "First Name", "TicketGiveaway.last_name" => "Last Name", "TicketGiveaway.email" => "Email", "Event.title" => "Event Title", "TicketGiveaway.zip" => "Zip", "TicketGiveaway.created" => "Created"), 'div' => false, 'label' => "Order by:"));
                    echo $this->Form->input("TicketGiveaway.direction", array('type' => 'select', 'options' => array("ASC" => "Ascending", "DESC" => "Descending"), 'div' => false, 'label' => "Direction:"));
                    echo "<br/><br/>" .$this->Form->submit('Search', array('label' => false, 'div' => false, "class" => "blu_btn mar_rt"));
                    echo $this->html->link('Clear Search', array('controller' => 'Events', 'action' => 'listGiveawayRequest/' . $event_id), array("class" => "blu_btn mar_rt"));
                    echo $this->html->link('Download CSV', array('controller' => 'Events', 'action' => 'ticketGiveawayCsv'), array("class" => "blu_btn mar_rt"));
                    echo $this->Form->end();
                    ?>
                </div>
                <!--------------------------------- search ends ------------------------------->
                <!--------------------------------- to be developed below ------------------------------->
                <h1>Ticket Giveaway Submissions</h1>
                <?php 
                if(empty($datas)){
                    echo "<br/><h2 style='color:red;'>No data found</h2>"; 
                }
                foreach ($datas as $data) { ?>
                    <div class = "giveaway_div">
                        <table style="border:none;width:100%;">
                            <tr>
                                <td><label>User Email : </label><?php echo $data["TicketGiveaway"]["email"]; ?></td>
                                <td><label>Name : </label><?php echo $data["TicketGiveaway"]["first_name"] . " " . $data["TicketGiveaway"]["last_name"]; ?></td>
                                <td><label>Phone : </label><?php echo $data["TicketGiveaway"]["phone"]; ?></td>
                            </tr>
                            <tr>
                                <td><label>Date Requested : </label><?php echo date("m/d/Y", strtotime($data["TicketGiveaway"]["created"])); ?></td>
                                <td><label>For Events : </label><?php echo $data["Event"]["title"]; ?></td>
                                <td><label>Zip : </label><?php echo $data["TicketGiveaway"]["zip"]; ?></td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <label>User Message</label><br/>
                                    <?php echo $data["TicketGiveaway"]["reason"]; ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <?php
                                    $model = "TicketGiveaway";
                                    if ($data['TicketGiveaway']['is_give'] == 1) {

                                        echo "<img src='/img/admin/winner.png' title='Not Give Free Ticket' id='isGive_" . $data['TicketGiveaway']['id'] . "' event_id='" . $data['TicketGiveaway']['id'] . "' value='" . $data['TicketGiveaway']['is_give'] . "' dir='" . $model . "' class='giveawayIsGive' rel='" . base64_encode($data['TicketGiveaway']['id']) . "'/>";
                                        ?>
                                        <span class="loader" id="isGive_load_<?php echo $data['TicketGiveaway']['id']; ?>"><?php echo $this->html->image('/img/admin/loader.gif'); ?></span>
                                        <?php
                                    } else {
                                        echo "<img src='/img/admin/notwinner.png' title='Give Free Ticket' id='isGive_" . $data['TicketGiveaway']['id'] . "' event_id='" . $data['TicketGiveaway']['id'] . "' value='" . $data['TicketGiveaway']['is_give'] . "' dir='" . $model . "' class='giveawayIsGive' rel='" . base64_encode($data['TicketGiveaway']['id']) . "'/>";
                                        ?>
                                        <span class="loader" id="isGive_load_<?php echo $data['TicketGiveaway']['id']; ?>"><?php echo $this->html->image('/img/admin/loader.gif'); ?></span>
                                    <?php }
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                <?php } ?>
                <!--------------------------------- to be developed above ------------------------------->

            </section>

            <div class="pagination_new">
                <?php
                echo $this->Paginator->first('<< First', null, null, array('class' => 'disabled'));
                echo $this->Paginator->prev('<< Previous', null, null, array('class' => 'disabled'));
                echo $this->Paginator->numbers(array('separator' => ''));
                echo $this->Paginator->next('Next >>', null, null, array('class' => 'disabled'));
                echo $this->Paginator->Last('Last >>', null, null, array('class' => 'disabled'));
                //echo $this->Paginator->counter(array('format' => 'Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%'));
                ?>

            </div>
            <section class="clr_bth"></section>
        </section>
    </section>
</section>

