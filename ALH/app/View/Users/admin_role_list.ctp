<section id="cont_wrapper">
    <section class="content">
        <h1 class="main_heading"><?php echo $this->Html->link('Add Role', '/admin/Users/addRole'); ?></h1>
        <section class="content_info">
            <?php echo $this->Session->flash(); ?>
            <section class="tbldata">
                <?php echo $this->Form->create('User', array('users', 'action' => 'selectMultiple?model=Role')); ?>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabulardata">
                    <tr>
                        <th style="width:10%"><?php echo $this->Form->checkbox('selectAll', array('type' => 'checkbox', 'class' => 'textbox', 'label' => '', 'id' => 'selectall', 'name' => 'selectAll')); ?></th>
                        <th style="width:18%;"><?php echo $this->Paginator->sort('User.name', 'Name'); ?>
                        <th style="width:18%;">Action</th>
                    </tr>
                    <?php foreach ($roles as $user): ?>
                        <tr>
                            <td><?php 
                            if($user['Role']['id'] != 1)
                            echo $this->Form->checkbox('checkbox', array('type' => 'checkbox', 'class' => 'textbox case', 'label' => '', 'value' => $user['Role']['id'], 'name' => 'IDs[]')); ?></td>
                            <td><?php echo $user['Role']['name']; ?></td>
                            
                            <td>
                                <?php echo $this->Html->link($this->html->image('/img/admin/edit1.png'), array("controller" => "users", "action" => "addRole", base64_encode($user['Role']['id'])), array('escape' => false, 'title' => 'Edit', 'class' => "edidlt")); ?>
                               
                                <?php 
                                if($user['Role']['id'] != 1)
                                echo $this->Html->link($this->html->image('/img/admin/delete.png'), array("controller" => "Commons", "action" => "Delete", base64_encode($user['Role']['id']),'Role'), array('escape' => false, 'title' => 'Delete', 'class' => "edidlt", 'onclick' => "return confirm('Are you sure you want to delete this role ?');")); ?>
   
                            </td>    
                        </tr>
                    <?php endforeach; ?>  
                </table><br/>
                <?php echo $this->Form->submit("Delete", array('class' => "blu_btn mar_rt", 'div' => false, 'id' => 'loginButton', 'title' => 'Delete', 'name' => 'delete', 'onclick' => "return atleastOneChecked('Deleted selected users?');")); ?>
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
</section>
