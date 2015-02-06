
<section id="cont_wrapper">
    <section class="content">
        <h1 class="main_heading"><?php echo $this->Html->link('Add Services', '/admin/Services/add'); ?></h1>
        <section class="content_info">
            <?php echo $this->Session->flash(); ?>
            <section class="tbldata">

               <!-- <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabulardata">
                    <tr>
                        <td></td>
                    </tr>
                </table>-->
                <?php echo $this->Form->create('Common', array('Services', 'action' => 'selectMultiple?model=Service'));?>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabulardata">
                    <tr>
                        <th style="width:10%"><?php echo $this->Form->checkbox('selectAll', array('type' => 'checkbox', 'class' => 'textbox', 'label' => '', 'id' => 'selectall', 'name' => 'selectAll')); ?></th>
                       
			     <th style="width:15%;"><?php echo $this->Paginator->sort('Service.name', 'Name'); ?>
                            <span class="<?php echo ('Service.name' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "type ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:15%;"><?php echo $this->Paginator->sort('Service.slug', 'Slug'); ?>
                            <span class="<?php echo ('Service.slug' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "slug ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
			<th style="width:10%;"><?php echo $this->Paginator->sort('Service.price', 'Price'); ?>
                            <span class="<?php echo ('Service.price' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "price ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
			<th style="width:10%;"><?php echo $this->Paginator->sort('Service.sku', 'SKU'); ?>
			    <span class="<?php echo ('Service.sku' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "sku ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:18%;"><?php echo $this->Paginator->sort('Service.created', 'Date Created'); ?>
                            <span class="<?php echo ('Service.created' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "created ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
			      <th style="width:10%;"><?php echo $this->Paginator->sort('User.status', 'Status'); ?>
                            <span class="<?php echo ('Service.status' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "status ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                     

                    <th style="width:18%;">Action</th>
                    </tr>
                    <?php foreach ($services as $service): ?>
                        <tr>
                            <td><?php echo $this->Form->checkbox('checkbox', array('type' => 'checkbox', 'class' => 'textbox case', 'label' => '', 'value' => $service['Service']['id'], 'name' => 'IDs[]')); ?></td>
                           
			    <td><?php echo $service['Service']['name']; ?></td>
                            <td><?php echo $service['Service']['slug']; ?></td>
			    <td><?php echo "$".$service['Service']['price']; ?></td>
		            <td><?php echo $service['Service']['sku']; ?></td>
                            <td><?php echo date("d-M-Y, g:i a",strtotime($service['Service']['created'])); ?></td>
                            <td>
                               <?php $model = "Service";
                                if($service['Service']['status'] == 1) {
                       
                                   echo "<img src='/img/admin/active.png' title='Change Status' id='".$service['Service']['id']."' value='".$service['Service']['status']."' dir='".$model."' class='statusImg' rel='".base64_encode($service['Service']['id'])."'/>";?>
				   <span class="loader" id="load_<?php echo $service['Service']['id'];?>"><?php echo $this->html->image('/img/admin/loader.gif');?></span>
                                <?php }else {
                                   echo "<img src='/img/admin/inactive.png' title='Change Status' id='".$service['Service']['id']."' value='".$service['Service']['status']."' dir='".$model."' class='statusImg' rel='".base64_encode($service['Service']['id'])."'/>";?>
				   <span class="loader" id="load_<?php echo $service['Service']['id'];?>"><?php echo $this->html->image('/img/admin/loader.gif');?></span>
			<?php }
                               ?>
                            </td></td>
                            <td>
                                <?php echo $this->Html->link($this->html->image('/img/admin/edit1.png'), array("controller" => "Services", "action" => "add", base64_encode($service['Service']['id'])), array('escape' => false, 'title' => 'Edit', 'class' => "edidlt")); ?>
                                <?php echo $this->Html->link($this->html->image('/img/admin/view.png'), array("controller" => "Services", "action" => "view", base64_encode($service['Service']['id'])), array('escape' => false, 'title' => 'View', 'class' => "edidlt")); ?>
                                <?php echo $this->Html->link($this->html->image('/img/admin/delete.png'), array("controller" => "Commons", "action" => "Delete", base64_encode($service['Service']['id']),'Service'), array('escape' => false, 'title' => 'Delete', 'class' => "edidlt", 'onclick' => "return confirm('Are you sure you want to delete this ?');")); ?>
                            </td>    
                        </tr>
                    <?php endforeach; ?>  
                </table><br/>
                <?php echo $this->Form->submit("Deactivate", array('class' => "blu_btn mar_rt", 'div' => false, 'id' => 'loginButton', 'title' => 'Deactivate', 'name' => 'deactivate', 'onclick' => "return atleastOneChecked('Deactivate selected ?');")); ?>
                <?php echo $this->Form->submit("Activate", array('class' => "blu_btn mar_rt", 'div' => false, 'id' => 'loginButton', 'title' => 'Activate', 'name' => 'activate', 'onclick' => "return atleastOneChecked('Activate selected ?');")); ?>
                <?php echo $this->Form->submit("Delete", array('class' => "blu_btn mar_rt", 'div' => false, 'id' => 'loginButton', 'title' => 'Activate', 'name' => 'delete', 'onclick' => "return atleastOneChecked('Deleted selected ?');")); ?>
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

