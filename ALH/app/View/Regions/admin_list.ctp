<section id="cont_wrapper">
    <section class="content">
        <h1 class="main_heading"><?php echo $this->Html->link('Add Region', '/admin/Regions/add'); ?></h1>
        <section class="content_info">
            <?php echo $this->Session->flash(); ?>
            <section class="tbldata">
                <?php echo $this->Form->create('Common', array('Regions', 'action' => 'selectMultiple?model=Region'));?>
                  
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabulardata">
                    <tr>
                        <th style="width:10%"><?php echo $this->Form->checkbox('selectAll', array('type' => 'checkbox', 'class' => 'textbox', 'label' => '', 'id' => 'selectall', 'name' => 'selectAll')); ?></th>
                        <th style="width:18%;"><?php echo $this->Paginator->sort('Region.name', 'Name'); ?>
                            <span class="<?php echo ('Region.name' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "name ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
			    <th style="width:18%;"><?php echo $this->Paginator->sort('Region.name', 'Event Tagged'); ?>
                            <span class="<?php echo ('Region.name' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "name ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:18%;"><?php echo $this->Paginator->sort('Region.status', 'Status'); ?>
                            <span class="<?php echo ('Region.status' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "status ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>

                        <th style="width:18%;">Action</th>
                    </tr>
                    <?php foreach ($regionsList as $data): //pr($data);?>
                        <tr>
                            <td><?php echo $this->Form->checkbox('checkbox', array('type' => 'checkbox', 'class' => 'textbox case', 'label' => '', 'value' => $data['Region']['id'], 'name' => 'IDs[]')); ?></td>
                            <td><?php echo $data['Region']['name']; ?></td>
			    <?php
                            if(!empty($data['Event'])){
                            $eventTagged = "";
                                foreach($data["Event"] as $event):
                                    $eventTagged .= $event['title'];
                                    $eventTagged .= ", ";
                                    
                                  endforeach;
                            ?>
                            <td><?php echo rtrim($eventTagged,", ");?></td>
                            <?php }else{?>
                            <td style="color: red;">No Region Added !</td>
                            <?php }?>
			    
                            <td>
                               <?php $model = "Region";
                                if($data['Region']['status'] == 1) {
                       
                                   echo "<img src='/img/admin/active.png' title='Change Status' id='".$data['Region']['id']."' value='".$data['Region']['status']."' dir='".$model."' class='statusImg' rel='".base64_encode($data['Region']['id'])."'/>";?>
				   <span class="loader" id="load_<?php echo $data['Region']['id'];?>"><?php echo $this->html->image('/img/admin/loader.gif');?></span>
                                <?php }else {
                                   echo "<img src='/img/admin/inactive.png' title='Change Status' id='".$data['Region']['id']."' value='".$data['Region']['status']."' dir='".$model."' class='statusImg' rel='".base64_encode($data['Region']['id'])."'/>";?>
				   <span class="loader" id="load_<?php echo $data['Region']['id'];?>"><?php echo $this->html->image('/img/admin/loader.gif');?></span>
			<?php }
                               ?>
                            </td></td>
                            <td>
                                <?php echo $this->Html->link($this->html->image('/img/admin/edit1.png'), array("controller" => "Regions", "action" => "add", base64_encode($data['Region']['id'])), array('escape' => false, 'title' => 'Edit', 'class' => "edidlt")); ?>
                                <?php echo $this->Html->link($this->html->image('/img/admin/delete.png'), array("controller" => "Commons", "action" => "Delete", base64_encode($data['Region']['id']),'Region'), array('escape' => false, 'title' => 'Delete', 'class' => "edidlt", 'onclick' => "return confirm('Are you sure you want to delete this region ?');")); ?>
                            </td>    
                        </tr>
                    <?php endforeach; ?>  
                </table><br/>
                <?php echo $this->Form->submit("Deactivate", array('class' => "blu_btn mar_rt", 'div' => false, 'id' => 'loginButton', 'title' => 'Deactivate', 'name' => 'deactivate', 'onclick' => "return atleastOneChecked('Deactivate selected Region ?');")); ?>
                <?php echo $this->Form->submit("Activate", array('class' => "blu_btn mar_rt", 'div' => false, 'id' => 'loginButton', 'title' => 'Activate', 'name' => 'activate', 'onclick' => "return atleastOneChecked('Activate selected Region ?');")); ?>
                <?php echo $this->Form->submit("Delete", array('class' => "blu_btn mar_rt", 'div' => false, 'id' => 'loginButton', 'title' => 'Activate', 'name' => 'delete', 'onclick' => "return atleastOneChecked('Deleted selected Region ?');")); ?>
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

