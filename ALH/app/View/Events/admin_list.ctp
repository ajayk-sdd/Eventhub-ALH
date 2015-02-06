<style>
    .form_input, .textarea {
        border: 1px solid #CCCCCC;
        border-radius: 3px;
        box-shadow: 6px 7px 3px -1px #DDDDDD;
        color: #333333;
        font-style: italic;
        margin-bottom: 14px;
        margin-left: 30px;
        outline: medium none;
        padding: 5px 6px;
    }
    select {
        border: 1px solid #CCCCCC !important;
        border-radius: 3px;
        box-shadow: 6px 7px 3px -1px #DDDDDD;
        color: #333333;
        font-style: italic;
        margin-left: 18px;
        outline: medium none;
        padding: 5px 6px;
    }
</style>
<section id="cont_wrapper">
    <section class="content">
        <!--<h1 class="main_heading"><?php echo $this->Html->link('Add Event', '/admin/Events/createEvent'); ?></h1>-->
        <section class="content_info">
            <?php echo $this->Session->flash(); ?>
            <section class="tbldata">
                <div class="srch_box">
                    <!------------------------------------ search starts --------------------->
                    <?php
                    echo $this->Form->create("Event", array("action" => "list", 'enctype' => 'multipart/form-data', "novalidate" => "novalidate"));
                    echo $this->Form->input('Event.title', array('label' => FALSE, 'div' => FALSE, 'maxlength' => '50', 'autocomplete' => 'off', 'class' => 'form_input', 'placeholder' => 'Enter Event Title'));
                    echo $this->Form->input('Event.sub_title', array('label' => FALSE, 'div' => FALSE, 'maxlength' => '50', 'autocomplete' => 'off', 'class' => 'form_input', 'placeholder' => 'Enter Event Sub-Title'));

                    echo $this->Form->input('Event.event_date', array('label' => FALSE, 'div' => FALSE, 'maxlength' => '50', 'autocomplete' => 'off', 'class' => 'form_input', 'placeholder' => 'Enter Event Date'));
                    echo $this->Form->input('Event.main_info_phone_no', array('label' => FALSE, 'div' => FALSE, 'maxlength' => '50', 'autocomplete' => 'off', 'class' => 'form_input', 'placeholder' => 'Enter Phone No'));
                    echo $this->Form->input('Event.main_info_email', array('label' => FALSE, 'div' => FALSE, 'maxlength' => '50', 'autocomplete' => 'off', 'class' => 'form_input', 'placeholder' => 'Enter Email Address'));
                    echo $this->Form->input('Event.created', array('label' => FALSE, "type" => "text", 'div' => FALSE, 'maxlength' => '50', 'autocomplete' => 'off', 'class' => 'form_input', 'placeholder' => 'Enter Created Date'));
                    //echo $this->Form->input('Event.keyword', array('label' => "Keyword:", 'div' => FALSE, 'maxlength' => '50', 'autocomplete' => 'off', 'class' => 'form_input', 'placeholder' => 'Enter Keyword'));
                    echo "<br>";
                    echo $this->Form->input("Event.limit", array('type' => 'select', 'options' => array("10" => "10", "20" => "20", "50" => "50", "100" => "100"), 'div' => FALSE, 'label' => "Show Per Page:"));
                    echo $this->Form->input("Event.order", array('type' => 'select', 'options' => array("Event.title" => "Event Title", "Event.sub_title" => "Event Sub-Title", "TicketGiveaway.email" => "Email", "Event.title" => "Event Title", "Event.created" => "Created", "Event.start_date" => "Event Date"), 'div' => FALSE, 'label' => "Order by:"));
                    echo $this->Form->input("Event.direction", array('type' => 'select', 'options' => array("ASC" => "Ascending", "DESC" => "Descending"), 'div' => FALSE, 'label' => FALSE));
                    echo $this->Form->input("Event.event_from", array('type' => 'select', 'options' => array("all" => "All", "ALH" => "ALH", "eventful" => "Eventful", "facebook" => "Facebook"), 'div' => FALSE, 'label' => FALSE));

                    echo $this->Form->submit('Search', array('label' => FALSE, 'div' => FALSE, "class" => "blu_btn mar_rt"));

                    echo $this->html->link('Clear', array('controller' => 'Events', 'action' => 'list'), array("class" => "blu_btn mar_rt"));

                    echo $this->html->link('CSV', array('controller' => 'Events', 'action' => 'list_csv'), array("class" => "blu_btn mar_rt"));

                    echo $this->html->link('Add Event', array('controller' => 'Events', 'action' => 'createEvent'), array("class" => "blu_btn mar_rt"));
                    echo $this->Form->end();
                    ?>
                </div>
                <!--------------------------------- search ends ------------------------------->
                <?php echo $this->Form->create('Common', array('users', 'action' => 'selectMultiple?model=Event')); ?>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabulardata">
                    <tr>
                        <th style="width:2%"><?php echo $this->Form->checkbox('selectAll', array('type' => 'checkbox', 'class' => 'textbox', 'label' => '', 'id' => 'selectall', 'name' => 'selectAll')); ?></th>
                        <th style="width:16%;"><?php echo $this->Paginator->sort('Event.title', 'Title'); ?>
                            <span class="<?php echo ('Event.title' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "title ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:16%;"><?php echo $this->Paginator->sort('Event.sub_title', 'Sub Title'); ?>
                            <span class="<?php echo ('Event.sub_title' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "sub_title ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:8%;"><?php echo $this->Paginator->sort('Event.main_info_phone_no', 'Phone No'); ?>
                            <span class="<?php echo ('Event.main_info_phone_no' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "main_info_phone_no ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:10%;"><?php echo $this->Paginator->sort('Event.main_info_email', 'Email Add'); ?>
                            <span class="<?php echo ('Event.main_info_email' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "main_info_email ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:10%;"><?php echo $this->Paginator->sort('Event.event_from', 'Event From'); ?>
                            <span class="<?php echo ('Event.event_from' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "event_from ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:12%;">Event Date</th>
                        <th style="width:12%;"><?php echo $this->Paginator->sort('Event.created', 'Created Date'); ?>
                            <span class="<?php echo ('Event.created' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "created ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:2%;"><?php echo $this->Paginator->sort('Event.status', 'Status'); ?>
                            <span class="<?php echo ('Event.status' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "status ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:2%;"><?php echo $this->Paginator->sort('Event.is_feature', 'Feature'); ?>
                            <span class="<?php echo ('Event.is_feature' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "is_feature ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>

                        <th style="width:8%;">Action</th>
                    </tr>
                    <?php 

 if(!empty($events))
                    {
foreach ($events as $event): ?>
                        <tr>
                            <td><?php echo $this->Form->checkbox('checkbox', array('type' => 'checkbox', 'class' => 'textbox case', 'label' => '', 'value' => $event['Event']['id'], 'name' => 'IDs[]')); ?></td>
                            <td><?php echo $event['Event']['title']; ?></td>
                            <td><?php echo $event['Event']['sub_title']; ?></td>
                            <td><?php echo $event['Event']['main_info_phone_no']; ?></td>
                            <td><?php echo $event['Event']['main_info_email']; ?></td>
                            <td><?php echo $event['Event']['event_from']; ?></td>
                            <td><?php
                                $n = 1;
                                sort($event["EventDate"]);

                                $now = strtotime(date('Y-m-d'));
                                foreach ($event["EventDate"] as $ed) {
                                    //echo strtotime($ed["date"])."@@@@@@@@@";
                                    if ($now <= strtotime($ed["date"])) {
                                        $datePast = '';
                                        if ($n == 1) {
                                            echo date('l, F d, Y', strtotime($ed["date"]));
                                            if (!empty($ed["start_time"])) {
                                                echo "  " . date('h:i A', strtotime($ed['start_time']));
                                                echo " - " . date('h:i A', strtotime($ed['end_time']));
                                            }
                                            echo "<br>";
                                            break;
                                        }
                                        $n++;
                                    } else {
                                        $datePast = date('l, F d, Y', strtotime($ed["date"]));

                                        if (!empty($ed["start_time"])) {
                                            $datePast .= "  " . date('h:i A', strtotime($ed['start_time']));
                                            $datePast .= " - " . date('h:i A', strtotime($ed['end_time']));
                                        }
                                    }
                                }

                                echo $datePast;
                                ?></td>
                            <td><?php echo date("l, F d, Y h:i A", strtotime($event['Event']['created'])); ?></td>
                            <td>
                                <?php
                                $model = "Event";
                                if ($event['Event']['status'] == 1) {

                                    echo "<img src='/img/admin/active.png' title='Change Status' id='" . $event['Event']['id'] . "' value='" . $event['Event']['status'] . "' dir='" . $model . "' class='statusImg' rel='" . base64_encode($event['Event']['id']) . "'/>";
                                    ?>
                                    <span class="loader" id="load_<?php echo $event['Event']['id']; ?>"><?php echo $this->html->image('/img/admin/loader.gif'); ?></span>
                                    <?php
                                } else {
                                    echo "<img src='/img/admin/inactive.png' title='Change Status' id='" . $event['Event']['id'] . "' value='" . $event['Event']['status'] . "' dir='" . $model . "' class='statusImg' rel='" . base64_encode($event['Event']['id']) . "'/>";
                                    ?>
                                    <span class="loader" id="load_<?php echo $event['Event']['id']; ?>"><?php echo $this->html->image('/img/admin/loader.gif'); ?></span>
                                <?php }
                                ?>
                            </td>
                            <td>
                                <?php
                                $model = "Event";
                                if ($event['Event']['is_feature'] == 1) {

                                    echo "<img src='/img/admin/active.png' title='Remove Feature' id='feature_" . $event['Event']['id'] . "' value='" . $event['Event']['is_feature'] . "' dir='" . $model . "' class='featureImg' rel='" . base64_encode($event['Event']['id']) . "'/>";
                                    ?>
                                    <span class="loader" id="load_feature_<?php echo $event['Event']['id']; ?>"><?php echo $this->html->image('/img/admin/loader.gif'); ?></span>
                                    <?php
                                } else {
                                    echo "<img src='/img/admin/inactive.png' title='Make Feature' id='feature_" . $event['Event']['id'] . "' value='" . $event['Event']['is_feature'] . "' dir='" . $model . "' class='featureImg' rel='" . base64_encode($event['Event']['id']) . "'/>";
                                    ?>
                                    <span class="loader" id="load_feature_<?php echo $event['Event']['id']; ?>"><?php echo $this->html->image('/img/admin/loader.gif'); ?></span>
                                <?php }
                                ?>
                            </td>
                            <td>
                                <?php echo $this->Html->link($this->html->image('/img/admin/edit1.png'), array("controller" => "Events", "action" => "createEvent", base64_encode($event['Event']['id'])), array('escape' => FALSE, 'title' => 'Edit', 'class' => "edidlt")); ?>
                                <?php echo $this->Html->link($this->html->image('/img/admin/view.png'), array("controller" => "Events", "action" => "view", base64_encode($event['Event']['id'])), array('escape' => FALSE, 'title' => 'View', 'class' => "edidlt")); ?>
                                <?php echo $this->Html->link($this->html->image('/img/admin/delete.png'), array("controller" => "Commons", "action" => "Delete", base64_encode($event['Event']['id']), 'Event'), array('escape' => FALSE, 'title' => 'Delete', 'class' => "edidlt", 'onclick' => "return confirm('Are you sure you want to delete this event ?');")); ?>
                            </td>    
                        </tr>
                    <?php endforeach;
 }
                    else
                    {
                    echo '<tr><td colspan="11"><center>No result found</center></td></tr>';
                    }
 ?>  
                </table><br/>
                <?php echo $this->Form->submit("Deactivate", array('class' => "blu_btn mar_rt", 'div' => FALSE, 'id' => 'loginButton', 'title' => 'Deactivate', 'name' => 'deactivate', 'onclick' => "return atleastOneChecked('Deactivate selected users?');")); ?>
                <?php echo $this->Form->submit("Activate", array('class' => "blu_btn mar_rt", 'div' => FALSE, 'id' => 'loginButton', 'title' => 'Activate', 'name' => 'activate', 'onclick' => "return atleastOneChecked('Activate selected users?');")); ?>
                <?php echo $this->Form->submit("Delete", array('class' => "blu_btn mar_rt", 'div' => FALSE, 'id' => 'loginButton', 'title' => 'Activate', 'name' => 'delete', 'onclick' => "return atleastOneChecked('Deleted selected users?');")); ?>
                <?php echo $this->Form->end(); ?>
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
<script type="text/javascript" src="/js/masked.js"></script>
<script>
    $(document).ready(function() {
        
        $("#EventLimit").change(function(){
            $("#EventListForm").submit();
        });
        $('#EventMainInfoPhoneNo').mask('(999) 999-9999');
        $("#EventEventDate").datepicker({
            dateFormat: 'yy-mm-dd'
        });
        $("#EventCreated").datepicker({
            dateFormat: 'yy-mm-dd'
        });
    });
</script>
