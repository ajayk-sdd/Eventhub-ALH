<section id="cont_wrapper">
    <section class="content">
        <h1 class="main_heading"><?php echo $this->Html->link('Add Newsletter', '/admin/Newsletters/addEmail'); ?>
        <?php echo $this->Html->link('List Emails', '/admin/Newsletters/list',array("class"=>"blu_btn mar_rt;","style"=>"float:right;")); ?></h1>
        <section class="content_info">
            <?php echo $this->Session->flash(); ?>
            <section class="tbldata">
                <div class="srch_box">
                    <!------------------------------------ search starts --------------------->
                    <?php
                    echo $this->Form->create("Newsletter", array("action" => "listEmail", 'enctype' => 'multipart/form-data', "novalidate" => "novalidate"));
                    echo $this->Form->input('NewsletterEmail.subject', array('label' => "Subject:", 'div' => false, 'maxlength' => '50', 'autocomplete' => 'off', 'class' => 'form_input', 'placeholder' => 'Enter Subject'));
                    echo $this->Form->input("NewsletterEmail.limit", array('type' => 'select', 'options' => array("10" => "10", "20" => "20", "50" => "50", "100" => "100"), 'div' => false, 'label' => "Show Per Page:"));
                    echo $this->Form->input("NewsletterEmail.order", array('type' => 'select', 'options' => array("NewsletterEmail.subject" => "Subject", "NewsletterEmail.created" => "Created"), 'div' => false, 'label' => "Order by:"));
                    echo $this->Form->input("NewsletterEmail.direction", array('type' => 'select', 'options' => array("ASC" => "Ascending", "DESC" => "Descending"), 'div' => false, 'label' => "Direction:"));
                    echo $this->Form->submit('Search', array('label' => false, 'div' => false, "class" => "blu_btn mar_rt"));
                    echo $this->html->link('Clear Search', array('controller' => 'Newsletters', 'action' => 'listEmail'), array("class" => "blu_btn mar_rt"));
                    //echo $this->html->link('Download CSV', array('controller' => 'Newsletters', 'action' => 'list_csv'), array("class" => "blu_btn mar_rt"));
                    echo $this->Form->end();
                    ?>
                </div>
                <!--------------------------------- search ends ------------------------------->
                <?php echo $this->Form->create('Common', array('users', 'action' => 'selectMultiple?model=NewsletterEmail')); ?>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabulardata">
                    <tr>
                        <th style="width:10%"><?php echo $this->Form->checkbox('selectAll', array('type' => 'checkbox', 'class' => 'textbox', 'label' => '', 'id' => 'selectall', 'name' => 'selectAll')); ?></th>
                        <th style="width:30%;"><?php echo $this->Paginator->sort('NewsletterEmail.subject', 'Subject'); ?>
                            <span class="<?php echo ('NewsletterEmail.subject' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "subject ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:30%;"><?php echo $this->Paginator->sort('NewsletterEmail.created', 'Created Date-Time'); ?>
                            <span class="<?php echo ('NewsletterEmail.created' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "created ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:10%;"><?php echo $this->Paginator->sort('NewsletterEmail.status', 'Status'); ?>
                            <span class="<?php echo ('NewsletterEmail.status' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "status ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>

                        <th style="width:20%;">Action</th>
                    </tr>
                    <?php if(!empty($newsletters))
                    {
foreach ($newsletters as $newsletter): ?>
                        <tr>
                            <td><?php echo $this->Form->checkbox('checkbox', array('type' => 'checkbox', 'class' => 'textbox case', 'label' => '', 'value' => $newsletter['NewsletterEmail']['id'], 'name' => 'IDs[]')); ?></td>
                            <td><?php echo $newsletter['NewsletterEmail']['subject']; ?></td>
                            <td><?php echo date("l jS \of F Y h:i:s A", strtotime($newsletter['NewsletterEmail']['created'])); ?></td>
                            <td>
                                <?php
                                $model = "NewsletterEmail";
                                if ($newsletter['NewsletterEmail']['status'] == 1) {

                                    echo "<img src='/img/admin/active.png' title='Change Status' id='" . $newsletter['NewsletterEmail']['id'] . "' value='" . $newsletter['NewsletterEmail']['status'] . "' dir='" . $model . "' class='statusImg' rel='" . base64_encode($newsletter['NewsletterEmail']['id']) . "'/>";
                                    ?>
                                    <span class="loader" id="load_<?php echo $newsletter['Newsletter']['id']; ?>"><?php echo $this->html->image('/img/admin/loader.gif'); ?></span>
                                    <?php
                                } else {
                                    echo "<img src='/img/admin/inactive.png' title='Change Status' id='" . $newsletter['NewsletterEmail']['id'] . "' value='" . $newsletter['NewsletterEmail']['status'] . "' dir='" . $model . "' class='statusImg' rel='" . base64_encode($newsletter['NewsletterEmail']['id']) . "'/>";
                                    ?>
                                    <span class="loader" id="load_<?php echo $newsletter['Newsletter']['id']; ?>"><?php echo $this->html->image('/img/admin/loader.gif'); ?></span>
                                <?php }
                                ?>
                            </td></td>
                            <td>
                                <?php echo $this->Html->link($this->html->image('/img/admin/edit1.png'), array("controller" => "Newsletters", "action" => "addEmail", base64_encode($newsletter['NewsletterEmail']['id'])), array('escape' => false, 'title' => 'Edit', 'class' => "edidlt")); ?>
                                <?php echo $this->Html->link($this->html->image('/img/admin/delete.png'), array("controller" => "Commons", "action" => "Delete", base64_encode($newsletter['NewsletterEmail']['id']), $model), array('escape' => false, 'title' => 'Delete', 'class' => "edidlt", 'onclick' => "return confirm('Are you sure you want to delete this newsletter email ?');")); ?>
                                <?php echo $this->Html->link($this->html->image('/img/admin/send.png'), array("controller" => "Newsletters", "action" => "send", base64_encode($newsletter['NewsletterEmail']['id'])), array('escape' => false, 'title' => 'Send', 'class' => "edidlt", 'onclick' => "return confirm('Are you sure you want to send this newsletter email ?');")); ?>
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

