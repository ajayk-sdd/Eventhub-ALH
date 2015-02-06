<section id="cont_wrapper">
    <section class="content">
        <h1 class="main_heading"><?php echo $this->Html->link('Add List', '/admin/MyLists/add'); ?></h1>
        <section class="content_info">
            <?php echo $this->Session->flash(); ?>
            <section class="tbldata">
	     <div class="srch_box">
                 <!------------------------------------ search starts --------------------->
                <?php echo $this->Form->create("MyList", array("action" => "list", 'enctype' => 'multipart/form-data', "novalidate" => "novalidate")); 
                            echo $this->Form->input('MyList.list_name', array('label' => "List Name:", 'div' => false, 'maxlength' => '50', 'autocomplete' => 'off', 'class' => 'form_input', 'placeholder' => 'Enter List Name'));
                            
                            echo $this->Form->input('User.username', array('label' => "List Owner:", 'div' => false, 'maxlength' => '50', 'autocomplete' => 'off', 'class' => 'form_input', 'placeholder' => 'Enter Owner Name'));
                            echo $this->Form->input("MyList.limit", array('type' => 'select', 'options' => array("10" => "10", "20" => "20", "50" => "50", "100" => "100"), 'div' => false, 'label' => "Show Per Page:"));
			    echo $this->Form->input("MyList.order", array('type' => 'select', 'options' => array("MyList.list_name" => "List Name", "User.username" => "List Owner", "MyList.created" => "Created"), 'div' => false, 'label' => "Order by:"));
			    echo $this->Form->input("MyList.direction", array('type' => 'select', 'options' => array("ASC" => "Ascending", "DESC" => "Descending"), 'div' => false, 'label' => "Direction:")); 
                            echo $this->Form->submit('Search', array('label' => false, 'div' => false, "class" => "blu_btn mar_rt"));
                           
                            echo $this->html->link('Clear Search', array('controller' => 'MyLists', 'action' => 'list'), array("class" => "blu_btn mar_rt"));
                            
                            //echo $this->html->link('Download CSV', array('controller' => 'MyLists', 'action' => 'list_csv'), array("class" => "blu_btn mar_rt"));
                            echo $this->Form->end();
		?>
	     </div>
                <!--------------------------------- search ends ------------------------------->
                <?php echo $this->Form->create('Common', array('users', 'action' => 'selectMultiple?model=MyList'));?>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabulardata">
                    <tr>
                        <th style="width:2%"><?php echo $this->Form->checkbox('selectAll', array('type' => 'checkbox', 'class' => 'textbox', 'label' => '', 'id' => 'selectall', 'name' => 'selectAll')); ?></th>
                        <th style="width:14%;"><?php echo $this->Paginator->sort('MyList.list_name', 'List Name'); ?>
                            <span class="<?php echo ('MyList.list_name' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "list_name ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:12%;"><?php echo $this->Paginator->sort('User.username', 'List Owner'); ?>
                            <span class="<?php echo ('User.username' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "username ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:16%;">Categories</th>
			<th style="width:16%;">Vibes</th>
			<th style="width:16%;">Regions</th> 
                        <!--th style="width:4%;"><?php //echo $this->Paginator->sort('MyList.dedicated_send_points', 'Dedicated Send Points'); ?>
                            <span class="<?php //echo ('MyList.dedicated_send_points' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php //if ($this->Paginator->sortKey() == "dedicated_send_points ASC") { ?> asc <?php //} ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:4%;"><?php //echo $this->Paginator->sort('MyList.multi_mylist_points', 'Multi Event Points'); ?>
                            <span class="<?php //echo ('MyList.multi_event_points' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php //if ($this->Paginator->sortKey() == "multi_mylist_points ASC") { ?> asc <?php //} ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:4%;"><?php //echo $this->Paginator->sort('MyList.max_email_per_week', 'Max Email Per Week'); ?>
                            <span class="<?php //echo ('MyList.max_email_per_week' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php //if ($this->Paginator->sortKey() == "max_email_per_week ASC") { ?> asc <?php //} ?>">&nbsp;&nbsp;&nbsp;</span></th-->
                        <th style="width:4%;"><?php echo $this->Paginator->sort('MyList.status', 'Status'); ?>
                            <span class="<?php echo ('MyList.status' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "status ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>

                        <th style="width:24%;">Action</th>
                    </tr>
                    <?php  if(!empty($mylists))
                    {
foreach ($mylists as $mylist):?>
                        <tr>
                            <td><?php echo $this->Form->checkbox('checkbox', array('type' => 'checkbox', 'class' => 'textbox case', 'label' => '', 'value' => $mylist['MyList']['id'], 'name' => 'IDs[]')); ?></td>
                            <td><?php echo $mylist['MyList']['list_name']; ?></td>
                            <td><?php echo $mylist['User']['username']; ?></td>
			    <td><?php foreach($mylist['ListCategory'] as $listcat) {
				echo $listcat['Category']['name'].", "; }
				?>
			    </td>
			     <td><?php foreach($mylist['ListVibe'] as $listvib) {
				echo $listvib['Vibe']['name'].", "; }
				?>
			    </td>
			      <td><?php foreach($mylist['ListRegion'] as $listreg) {
				echo $listreg['Region']['name'].", "; }
				?>
			    </td>
                            <!--td><?php //echo $mylist['MyList']['dedicated_send_points']; ?></td>
                            <td><?php //echo $mylist['MyList']['multi_event_points']; ?></td>
                            <td><?php //echo $mylist['MyList']['max_email_per_week']; ?></td-->
                            <td>
                              <?php $model = "MyList";
                                if($mylist['MyList']['status'] == 1) {
                       
                                   echo "<img src='/img/admin/active.png' title='Change Status' id='".$mylist['MyList']['id']."' value='".$mylist['MyList']['status']."' dir='".$model."' class='statusImg' rel='".base64_encode($mylist['MyList']['id'])."'/>";?>
				   <span class="loader" id="load_<?php echo $mylist['MyList']['id'];?>"><?php echo $this->html->image('/img/admin/loader.gif');?></span>
                                <?php }else {
                                   echo "<img src='/img/admin/inactive.png' title='Change Status' id='".$mylist['MyList']['id']."' value='".$mylist['MyList']['status']."' dir='".$model."' class='statusImg' rel='".base64_encode($mylist['MyList']['id'])."'/>";?>
				   <span class="loader" id="load_<?php echo $mylist['MyList']['id'];?>"><?php echo $this->html->image('/img/admin/loader.gif');?></span>
			<?php }
                               ?>
                            </td>
                            <td>
				<?php
				$mylist_id = base64_encode($mylist["MyList"]["id"]);
                                echo $this->Form->input('view', array('options' => array("addContact" => "Add Contact", "exportCsv" => "Export Contacts", "importForList" => "Import Contacts"), 'type' => 'select', "div" => FALSE, "label" => FALSE, "empty" => "Choose Action", "onchange" => "javascript:viewAction(this.value,'$mylist_id');"));
                                ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo $this->Html->link($this->html->image('/img/admin/edit1.png'), array("controller" => "MyLists", "action" => "add", base64_encode($mylist['MyList']['id'])), array('escape' => false, 'title' => 'Edit', 'class' => "edidlt")); ?>
                                <!--?php echo $this->Html->link($this->html->image('/img/admin/view.png'), array("controller" => "MyLists", "action" => "view", base64_encode($mylist['MyList']['id'])), array('escape' => false, 'title' => 'View', 'class' => "edidlt")); ?-->
				<?php echo $this->Html->link($this->html->image('/img/admin/view.png'), array("controller" => "MyLists", "action" => "listView", base64_encode($mylist['MyList']['id'])), array('escape' => false, 'title' => 'View', 'class' => "edidlt")); ?>
                                <?php echo $this->Html->link($this->html->image('/img/admin/delete.png'), array("controller" => "Commons", "action" => "Delete", base64_encode($mylist['MyList']['id']),'MyList'), array('escape' => false, 'title' => 'Delete', 'class' => "edidlt", 'onclick' => "return confirm('Are you sure you want to delete this mylist ?');")); ?>
				
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
      function viewAction(selectedAction, myListId) {
        if (selectedAction.trim() != "") {
            if (selectedAction == "delete") {
                if (confirm("Are you sure you want to delete this list.")) {
                    window.location = "<?php echo "http://" . $_SERVER["HTTP_HOST"]; ?>/admin/MyLists/" + selectedAction + "/" + myListId;
                } else {

                }
            } else {
                window.location = "<?php echo "http://" . $_SERVER["HTTP_HOST"]; ?>/admin/MyLists/" + selectedAction + "/" + myListId;
            }

        }
    }
    $(document).ready(function() {
        
        $("#MyListLimit").change(function(){
            $("#MyListListForm").submit();
        });
       
    });
</script>
