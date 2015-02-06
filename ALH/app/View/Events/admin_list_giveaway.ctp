<section id="cont_wrapper">
    <section class="content">
        <h1 class="main_heading"><?php echo $this->Html->link('Add Giveaway', '/admin/Events/addGiveaway'); ?></h1>
        <section class="content_info">
            <?php echo $this->Session->flash(); ?>
            <section class="tbldata">
                <div class="srch_box">
                    <!------------------------------------ search starts --------------------->
                    <?php
                    echo $this->Form->create("Event", array("action" => "listGiveaway", 'enctype' => 'multipart/form-data', "novalidate" => "novalidate"));
                    echo $this->Form->input('Event.title', array('label' => "Title:", 'div' => false, 'maxlength' => '50', 'autocomplete' => 'off', 'class' => 'form_input', 'placeholder' => 'Enter Event Title'));
                    echo $this->Form->input("Giveaway.limit", array('type' => 'select', 'options' => array("10" => "10", "20" => "20", "50" => "50", "100" => "100"), 'div' => false, 'label' => "Show Per Page:"));
                    echo $this->Form->input("Giveaway.order", array('type' => 'select', 'options' => array("Event.title" => "Event Title", "Giveaway.created" => "Created"), 'div' => false, 'label' => "Order by:"));
                    echo $this->Form->input("Giveaway.direction", array('type' => 'select', 'options' => array("ASC" => "Ascending", "DESC" => "Descending"), 'div' => false, 'label' => "Direction:"));
                    echo $this->Form->submit('Search', array('label' => false, 'div' => false, "class" => "blu_btn mar_rt"));
                    echo $this->html->link('Clear Search', array('controller' => 'Events', 'action' => 'listGiveaway'), array("class" => "blu_btn mar_rt"));
                    echo $this->Form->end();
                    ?>
                </div>
                <!--------------------------------- search ends ------------------------------->
                <?php echo $this->Form->create('Common', array('users', 'action' => 'selectMultiple?model=Giveaway')); ?>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabulardata">
                    <tr>
                        <th style="width:4%"><?php echo $this->Form->checkbox('selectAll', array('type' => 'checkbox', 'class' => 'textbox', 'label' => '', 'id' => 'selectall', 'name' => 'selectAll')); ?></th>
                        <th style="width:16%;"><?php echo $this->Paginator->sort('Event.title', 'Title'); ?>
                            <span class="<?php echo ('Event.title' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "title ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:16%;"><?php echo $this->Paginator->sort('Giveaway.no_of_ticket', 'No Of Ticket'); ?>
                            <span class="<?php echo ('Giveaway.no_of_ticket' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "no_of_ticket ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:16%;"><?php echo $this->Paginator->sort('User.username', 'Add By'); ?>
                            <span class="<?php echo ('User.username' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "username ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:16%;"><?php echo $this->Paginator->sort('Giveaway.created', 'Created'); ?>
                            <span class="<?php echo ('Giveaway.created' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "created ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:16%;"><?php echo $this->Paginator->sort('Giveaway.status', 'Status'); ?>
                            <span class="<?php echo ('Giveaway.status' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "status ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:16%;">Action</th>
                    </tr>
                    <?php 
 if(!empty($giveaways))
                    {
foreach ($giveaways as $giveaway): ?>
                        <tr>
                            <td><?php echo $this->Form->checkbox('checkbox', array('type' => 'checkbox', 'class' => 'textbox case', 'label' => '', 'value' => $giveaway['Giveaway']['id'], 'name' => 'IDs[]')); ?></td>
                            <td><?php echo $giveaway['Event']['title']; ?></td>
                            <td><?php echo $giveaway['Giveaway']['no_of_ticket']; ?></td>
                            <td><?php echo $giveaway['User']['username']; ?></td>
                            <td><?php echo Date("d F y",strtotime($giveaway['Giveaway']['created'])); ?></td>
                            <td>
                                <?php
                                $model = "Giveaway";
                                if ($giveaway['Giveaway']['status'] == 1) {

                                    echo "<img src='/img/admin/active.png' title='Change Status' id='" . $giveaway['Giveaway']['id'] . "' value='" . $giveaway['Giveaway']['status'] . "' dir='" . $model . "' class='statusImg' rel='" . base64_encode($giveaway['Giveaway']['id']) . "'/>";
                                    ?>
                                    <span class="loader" id="load_<?php echo $giveaway['Giveaway']['id']; ?>"><?php echo $this->html->image('/img/admin/loader.gif'); ?></span>
                                    <?php
                                } else {
                                    echo "<img src='/img/admin/inactive.png' title='Change Status' id='" . $giveaway['Giveaway']['id'] . "' value='" . $giveaway['Giveaway']['status'] . "' dir='" . $model . "' class='statusImg' rel='" . base64_encode($giveaway['Giveaway']['id']) . "'/>";
                                    ?>
                                    <span class="loader" id="load_<?php echo $giveaway['Giveaway']['id']; ?>"><?php echo $this->html->image('/img/admin/loader.gif'); ?></span>
                                <?php }
                                ?>
                            </td></td>
                            <td>
                                <?php echo $this->Html->link($this->html->image('/img/admin/edit1.png'), array("controller" => "Events", "action" => "addGiveaway", base64_encode($giveaway['Giveaway']['id'])), array('escape' => false, 'title' => 'Edit', 'class' => "edidlt")); ?>
                                <?php echo $this->Html->link($this->html->image('/img/admin/delete.png'), array("controller" => "Commons", "action" => "Delete", base64_encode($giveaway['Giveaway']['id']), 'Giveaway'), array('escape' => false, 'title' => 'Delete', 'class' => "edidlt", 'onclick' => "return confirm('Are you sure you want to delete this Giveaway ?');")); ?>
                                <?php echo $this->Html->link($this->html->image('/img/admin/view.png'), array("controller" => "Events", "action" => "listGiveawayRequest", base64_encode($giveaway['Event']['id'])), array('escape' => false, 'title' => 'View Request', 'class' => "edidlt")); ?>
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
                <?php echo $this->Form->submit("Deactivate", array('class' => "blu_btn mar_rt", 'div' => false, 'id' => 'loginButton', 'title' => 'Deactivate', 'name' => 'deactivate', 'onclick' => "return atleastOneChecked('Deactivate selected users?');")); ?>
                <?php echo $this->Form->submit("Activate", array('class' => "blu_btn mar_rt", 'div' => false, 'id' => 'loginButton', 'title' => 'Activate', 'name' => 'activate', 'onclick' => "return atleastOneChecked('Activate selected users?');")); ?>
                <?php echo $this->Form->submit("Delete", array('class' => "blu_btn mar_rt", 'div' => false, 'id' => 'loginButton', 'title' => 'Activate', 'name' => 'delete', 'onclick' => "return atleastOneChecked('Deleted selected users?');")); ?>
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
<script>
    $(document).ready(function() {
        
        $("#GiveawayLimit").change(function(){
            $("#EventListGiveawayForm").submit();
        });
       
    });
</script>
