<?php
$search_user = $this->Session->read("search_user");
?>
<section id="cont_wrapper">
    <section class="content">
        <h1 class="main_heading"><?php echo $this->Html->link('Add Users', '/admin/Users/add'); ?></h1>
        <section class="content_info">
            <?php echo $this->Session->flash(); ?>
            <section class="tbldata">
	     <div class="srch_box">
                <!------------------------------------ search starts --------------------->
                <?php echo $this->Form->create("User", array("action" => "list", 'enctype' => 'multipart/form-data', "novalidate" => "novalidate"));
                            echo '<div>';
			    echo $this->Form->input('User.id', array('type' => 'text','label' => "Id:", 'div' => false, 'maxlength' => '50', 'autocomplete' => 'off', 'class' => 'form_input', 'placeholder' => 'Enter Id','value'=>$search_user["User"]["id"]));
                           
			    echo $this->Form->input('User.username', array('label' => "Username:", 'div' => false, 'maxlength' => '50', 'autocomplete' => 'off', 'class' => 'input form_input', 'placeholder' => 'Enter Username','value'=>$search_user["User"]["username"]));
                           
                            echo $this->Form->input('User.email', array('label' => "Email:", 'div' => false, 'maxlength' => '50', 'autocomplete' => 'off', 'class' => 'form_input', 'placeholder' => 'Enter Email','value'=>$search_user["User"]["email"]));
                            echo $this->Form->input('User.first_name', array('label' => "First Name:", 'div' => false, 'maxlength' => '50', 'autocomplete' => 'off', 'class' => 'form_input', 'placeholder' => 'Enter First Name','value'=>$search_user["User"]["first_name"]));
                            echo $this->Form->input('User.last_name', array('label' => "Last Name:", 'div' => false, 'maxlength' => '50', 'autocomplete' => 'off', 'class' => 'form_input', 'placeholder' => 'Enter Last Name','value'=>$search_user["User"]["last_name"]));
                            echo '</div><div><br>';
			    echo $this->Form->input("User.role_id", array('type' => 'select', 'options' => array("" => "All", "1" => "Super Admin", "2" => "Member", "3" => "Super Member", "4" => "Premium Member", "7" => "Guest"), 'div' => false, 'label' => "Show:","selected"=>$search_user["User"]["role_id"]));
			    echo $this->Form->input("User.limit", array('type' => 'select', 'options' => array("10" => "10", "20" => "20", "50" => "50", "100" => "100"), 'div' => false, 'label' => "Show Per Page:","selected"=>$search_user["User"]["limit"]));
			    echo $this->Form->input("User.order", array('type' => 'select', 'options' => array("User.username" => "Username", "User.email" => "Email", "User.created" => "Date Joined"), 'div' => false, 'label' => "Order by:","selected"=>$search_user["User"]["order"]));
			    echo $this->Form->input("User.direction", array('type' => 'select', 'options' => array("ASC" => "Ascending", "DESC" => "Descending"), 'div' => false, 'label' => "Direction:","selected"=>$search_user["User"]["direction"])); 
                            echo $this->Form->submit('Search', array('label' => false, 'div' => false, "class" => "blu_btn mar_rt"));
                           
                            echo $this->html->link('Clear Search', array('controller' => 'Users', 'action' => 'list/clear'), array("class" => "blu_btn mar_rt"));
                           
                            echo $this->html->link('Download CSV', array('controller' => 'Users', 'action' => 'list_csv'), array("class" => "blu_btn mar_rt"));
                            echo '</div>';
			   echo $this->Form->end();
		?>
	     </div>
                <!--------------------------------- search ends ------------------------------->
                <?php echo $this->Form->create('Common', array('users', 'action' => 'selectMultiple?model=User'));?>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabulardata">
                    <tr>
                        <th style="width:2%"><?php echo $this->Form->checkbox('selectAll', array('type' => 'checkbox', 'class' => 'textbox', 'label' => '', 'id' => 'selectall', 'name' => 'selectAll')); ?></th>
                        <th style="width:7%;"><?php echo $this->Paginator->sort('User.id', 'User Id'); ?>
                            <span class="<?php echo ('User.id' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "id ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                       <th style="width:16%;"><?php echo $this->Paginator->sort('User.created', 'Join Date'); ?>
                            <span class="<?php echo ('User.created' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "created ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                         <th style="width:18%;"><?php echo $this->Paginator->sort('User.email', 'Email'); ?>
                            <span class="<?php echo ('User.email' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "email ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:12%;"><?php echo $this->Paginator->sort('User.username', 'User Name'); ?>
                            <span class="<?php echo ('User.username' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "username ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:10%;"><?php echo $this->Paginator->sort('User.first_name', 'First Name'); ?>
                            <span class="<?php echo ('User.first_name' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "first_name ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:10%;"><?php echo $this->Paginator->sort('User.last_name', 'Last Name'); ?>
                            <span class="<?php echo ('User.last_name' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "last_name ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:14%;"><?php echo $this->Paginator->sort('Role.name', 'Role'); ?>
                            <span class="<?php echo ('Role.name' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "name ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:2%;"><?php echo $this->Paginator->sort('User.status', 'Status'); ?>
                            <span class="<?php echo ('User.status' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "status ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>

                        <th style="width:18%;">Action</th>
                    </tr>
                    <?php
		    if(!empty($users)) {
			foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $this->Form->checkbox('checkbox', array('type' => 'checkbox', 'class' => 'textbox case', 'label' => '', 'value' => $user['User']['id'], 'name' => 'IDs[]')); ?></td>
                            <td><?php echo $user['User']['id']; ?></td>
			    <td><?php echo date("d-M-Y, g:i a",strtotime($user['User']['created'])); ?></td>
			    <td><?php echo $user['User']['email']; ?></td>
                            <td><?php echo $user['User']['username']; ?></td>
			    <td><?php echo $user['User']['first_name']; ?></td>
			    <td><?php echo $user['User']['last_name']; ?></td>
                            <td><?php echo $user['Role']['name']; ?></td>
                            <td>
                               <?php $model = "User";
                                if($user['User']['status'] == 1) {
                       
                                   echo "<img src='/img/admin/active.png' title='Change Status' id='".$user['User']['id']."' value='".$user['User']['status']."' dir='".$model."' class='statusImg' rel='".base64_encode($user['User']['id'])."'/>";?>
				   <span class="loader" id="load_<?php echo $user['User']['id'];?>"><?php echo $this->html->image('/img/admin/loader.gif');?></span>
                                <?php }else {
                                   echo "<img src='/img/admin/inactive.png' title='Change Status' id='".$user['User']['id']."' value='".$user['User']['status']."' dir='".$model."' class='statusImg' rel='".base64_encode($user['User']['id'])."'/>";?>
				   <span class="loader" id="load_<?php echo $user['User']['id'];?>"><?php echo $this->html->image('/img/admin/loader.gif');?></span>
			<?php }
                               ?>
                            </td></td>
                            <td>
                                <?php echo $this->Html->link($this->html->image('/img/admin/edit1.png'), array("controller" => "users", "action" => "add", base64_encode($user['User']['id'])), array('escape' => false, 'title' => 'Edit', 'class' => "edidlt")); ?>
                                <?php echo $this->Html->link($this->html->image('/img/admin/view.png'), array("controller" => "users", "action" => "view", base64_encode($user['User']['id'])), array('escape' => false, 'title' => 'View', 'class' => "edidlt")); ?>
                                <?php echo $this->Html->link($this->html->image('/img/admin/delete.png'), array("controller" => "Commons", "action" => "Delete", base64_encode($user['User']['id']),'User'), array('escape' => false, 'title' => 'Delete', 'class' => "edidlt", 'onclick' => "return confirm('Are you sure you want to delete this user ?');")); ?>
                            </td>    
                        </tr>
                    <?php endforeach;
		    }
		    else
		    {
			echo '<tr>
                            <td colspan="10"><center>No User Found.</center></td></tr>';
			}?>  
                </table><br/>
                <?php echo $this->Form->submit("Deactivate", array('class' => "blu_btn mar_rt", 'div' => false, 'id' => 'loginButton', 'title' => 'Deactivate', 'name' => 'deactivate', 'onclick' => "return atleastOneChecked('Are you sure deactivate selected users?');")); ?>
                <?php echo $this->Form->submit("Activate", array('class' => "blu_btn mar_rt", 'div' => false, 'id' => 'loginButton', 'title' => 'Activate', 'name' => 'activate', 'onclick' => "return atleastOneChecked('Are you sure activate selected users?');")); ?>
                <?php echo $this->Form->submit("Delete", array('class' => "blu_btn mar_rt", 'div' => false, 'id' => 'loginButton', 'title' => 'Delete', 'name' => 'delete', 'onclick' => "return atleastOneChecked('Are you sure want to delete this user?');")); ?>
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
        
        $("#UserLimit").change(function(){
            $("#UserListForm").submit();
        });
       
    });
</script>
