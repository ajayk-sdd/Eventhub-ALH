<section id="cont_wrapper">
    <section class="content">
        <!--<h1 class="main_heading"><?php //echo $this->Html->link('Add EventTemplate', '/admin/EventTemplates/createEventTemplate');     ?></h1>-->
        <section class="content_info">
            <?php echo $this->Session->flash(); ?>
            <section class="tbldata">
                <div class="srch_box">
                    <!------------------------------------ search starts --------------------->
                    <?php
                    echo $this->Form->create("Template", array("action" => "list", 'enctype' => 'multipart/form-data', "novalidate" => "novalidate"));
                    echo $this->Form->input('EventTemplate.name', array('label' => "Name:", 'div' => false, 'maxlength' => '50', 'class' => 'form_input', 'placeholder' => 'Enter EventTemplate Name'));
                    echo $this->Form->input("EventTemplate.limit", array('type' => 'select', 'options' => array("10" => "10", "20" => "20", "50" => "50", "100" => "100"), 'div' => false, 'label' => "Show Per Page:"));
                    echo $this->Form->input("EventTemplate.order", array('type' => 'select', 'options' => array("EventTemplate.name" => "EventTemplate Name", "EventTemplate.created" => "created", "EventTemplate.modified" => "Modified"), 'div' => false, 'label' => "Order by:"));
                    echo $this->Form->input("EventTemplate.direction", array('type' => 'select', 'options' => array("ASC" => "Ascending", "DESC" => "Descending"), 'div' => false, 'label' => "Direction:"));
                    echo $this->Form->submit('Search', array('label' => false, 'div' => false, "class" => "blu_btn mar_rt"));
                    echo $this->html->link('Clear', array('controller' => 'Templates', 'action' => 'list'), array("class" => "blu_btn mar_rt"));
                    echo $this->Html->link("Add New", array("controller" => "Templates", "action" => "create"), array("class" => "blu_btn mar_rt", "style" => "float:right;"));
                    echo $this->Form->end();
                    ?>
                </div>
                <!--------------------------------- search ends ------------------------------->
                <?php echo $this->Form->create('Common', array('users', 'action' => 'selectMultiple?model=EventTemplate')); ?>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabulardata">
                    <tr>
                        <th style="width:4%"><?php echo $this->Form->checkbox('selectAll', array('type' => 'checkbox', 'class' => 'textbox', 'label' => '', 'id' => 'selectall', 'name' => 'selectAll')); ?></th>
                        <th style="width:16%;"><?php echo $this->Paginator->sort('EventTemplate.name', 'Name'); ?>
                            <span class="<?php echo ('EventTemplate.name' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "title ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:24%;"><?php echo $this->Paginator->sort('EventTemplate.image', 'Image'); ?>
                            <span class="<?php echo ('EventTemplate.image' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "image ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:8%;"><?php echo $this->Paginator->sort('EventTemplate.status', 'Status'); ?>
                            <span class="<?php echo ('EventTemplate.status' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "status ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:8%;"><?php echo $this->Paginator->sort('EventTemplate.type', 'Type'); ?>
                            <span class="<?php echo ('EventTemplate.type' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "type ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:16%;"><?php echo $this->Paginator->sort('EventTemplate.created', 'Created'); ?>
                            <span class="<?php echo ('EventTemplate.created' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "created ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:16%;"><?php echo $this->Paginator->sort('EventTemplate.modified', 'Last Modified'); ?>
                            <span class="<?php echo ('EventTemplate.modified' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "modified ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:8%;">Action</th>
                    </tr>
                    <?php 
			if(!empty($templates)) {
			foreach ($templates as $template): ?>
                        <tr>
                            <td><?php echo $this->Form->checkbox('checkbox', array('type' => 'checkbox', 'class' => 'textbox case', 'label' => '', 'value' => $template['EventTemplate']['id'], 'name' => 'IDs[]')); ?></td>
                            <td><?php echo $template['EventTemplate']['name']; ?></td>
                            <td><?php echo $this->html->image("template/small/" . $template["EventTemplate"]["image"]); ?></td>
                            <td>
                                <?php
                                $model = "EventTemplate";
                                if ($template['EventTemplate']['status'] == 1) {

                                    echo "<img src='/img/admin/active.png' title='Change Status' id='" . $template['EventTemplate']['id'] . "' value='" . $template['EventTemplate']['status'] . "' dir='" . $model . "' class='statusImg' rel='" . base64_encode($template['EventTemplate']['id']) . "'/>";
                                    ?>
                                    <span class="loader" id="load_<?php echo $template['EventTemplate']['id']; ?>"><?php echo $this->html->image('/img/admin/loader.gif'); ?></span>
                                    <?php
                                } else {
                                    echo "<img src='/img/admin/inactive.png' title='Change Status' id='" . $template['EventTemplate']['id'] . "' value='" . $template['EventTemplate']['status'] . "' dir='" . $model . "' class='statusImg' rel='" . base64_encode($template['EventTemplate']['id']) . "'/>";
                                    ?>
                                    <span class="loader" id="load_<?php echo $template['EventTemplate']['id']; ?>"><?php echo $this->html->image('/img/admin/loader.gif'); ?></span>
                                <?php }
                                ?>
                            </td>
                            <td><?php
                                if ($template["EventTemplate"]["type"] == 0) {
                                    echo "Email Template";
                                } else if ($template["EventTemplate"]["type"] == 1) {
                                    echo "Single Event";
                                } else {
                                    echo "Multiple Event";
                                }
                                ?></td>
                            <td><?php echo date("l, F d, Y", strtotime($template['EventTemplate']['created'])); ?></td>
                            <td><?php echo date("l, F d, Y", strtotime($template['EventTemplate']['modified'])); ?></td>
                            <td>
                                <?php echo $this->Html->link($this->html->image('/img/admin/edit1.png'), array("controller" => "Templates", "action" => "create", base64_encode($template['EventTemplate']['id'])), array('escape' => false, 'title' => 'Edit', 'class' => "edidlt")); ?>
    <?php echo $this->Html->link($this->html->image('/img/admin/view.png'), array("controller" => "Templates", "action" => "view", base64_encode($template['EventTemplate']['id'])), array('escape' => false, 'title' => 'View', 'class' => "edidlt")); ?>
                        <?php echo $this->Html->link($this->html->image('/img/admin/delete.png'), array("controller" => "Commons", "action" => "Delete", base64_encode($template['EventTemplate']['id']), 'EventTemplate'), array('escape' => false, 'title' => 'Delete', 'class' => "edidlt", 'onclick' => "return confirm('Are you sure you want to Delete this Event Template?');")); ?>
                            </td>    
                        </tr>
                	<?php endforeach;
			}
			else
			{
			echo '<tr><td colspan="8"><center>No Result Found.</center></td></tr>';
			} ?>  
                </table><br/>
                <?php echo $this->Form->submit("Deactivate", array('class' => "blu_btn mar_rt", 'div' => false, 'id' => 'loginButton', 'title' => 'Deactivate', 'name' => 'deactivate', 'onclick' => "return atleastOneChecked('Deactivate selected template?');")); ?>
                <?php echo $this->Form->submit("Activate", array('class' => "blu_btn mar_rt", 'div' => false, 'id' => 'loginButton', 'title' => 'Activate', 'name' => 'activate', 'onclick' => "return atleastOneChecked('Activate selected template?');")); ?>
<?php echo $this->Form->submit("Delete", array('class' => "blu_btn mar_rt", 'div' => false, 'id' => 'loginButton', 'title' => 'Activate', 'name' => 'delete', 'onclick' => "return atleastOneChecked('Deleted selected Template?');")); ?>
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
