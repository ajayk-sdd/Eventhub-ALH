
<section id="cont_wrapper">
    <section class="content">
        <h1 class="main_heading"><?php echo $this->Html->link('Add Points', '/admin/Packages/addVirtualCurrency'); ?></h1>
        <section class="content_info">
            <?php echo $this->Session->flash(); ?>
            <section class="tbldata">

               <!-- <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabulardata">
                    <tr>
                        <td></td>
                    </tr>
                </table>-->
                <?php echo $this->Form->create('Common', array('VirtualCurrencys', 'action' => 'selectMultiple?model=VirtualCurrency')); ?>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabulardata">
                    <tr>
                        <th style="width:10%"><?php echo $this->Form->checkbox('selectAll', array('type' => 'checkbox', 'class' => 'textbox', 'label' => '', 'id' => 'selectall', 'name' => 'selectAll')); ?></th>

                        <th style="width:15%;"><?php echo $this->Paginator->sort('VirtualCurrency.points', 'Points'); ?>
                            <span class="<?php echo ('VirtualCurrency.points' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "points ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:15%;"><?php echo $this->Paginator->sort('VirtualCurrency.price', 'Price(USD)'); ?>
                            <span class="<?php echo ('VirtualCurrency.price' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "price ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:15%;"><?php echo $this->Paginator->sort('VirtualCurrency.status', 'Status'); ?>
                            <span class="<?php echo ('VirtualCurrency.status' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "status ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th> 
                        <th style="width:15%;"><?php echo $this->Paginator->sort('VirtualCurrency.created', 'Date Created'); ?>
                            <span class="<?php echo ('VirtualCurrency.created' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "created ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:15%;"><?php echo $this->Paginator->sort('VirtualCurrency.modified', 'Date Modified'); ?>
                            <span class="<?php echo ('VirtualCurrency.modified' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "modified ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>


                        <th style="width:18%;">Action</th>
                    </tr>
                    <?php foreach ($datas as $data): ?>
                        <tr>
                            <td><?php echo $this->Form->checkbox('checkbox', array('type' => 'checkbox', 'class' => 'textbox case', 'label' => '', 'value' => $data['VirtualCurrency']['id'], 'name' => 'IDs[]')); ?></td>

                            <td><?php echo $data['VirtualCurrency']['points']; ?></td>
                            <td><?php echo "$" . $data['VirtualCurrency']['price']; ?></td>
                            <td>
                                <?php
                                $model = "VirtualCurrency";
                                if ($data['VirtualCurrency']['status'] == 1) {

                                    echo "<img src='/img/admin/active.png' title='Change Status' id='" . $data['VirtualCurrency']['id'] . "' value='" . $data['VirtualCurrency']['status'] . "' dir='" . $model . "' class='statusImg' rel='" . base64_encode($data['VirtualCurrency']['id']) . "'/>";
                                    ?>
                                    <span class="loader" id="load_<?php echo $data['VirtualCurrency']['id']; ?>"><?php echo $this->html->image('/img/admin/loader.gif'); ?></span>
                                <?php
                                } else {
                                    echo "<img src='/img/admin/inactive.png' title='Change Status' id='" . $data['VirtualCurrency']['id'] . "' value='" . $data['VirtualCurrency']['status'] . "' dir='" . $model . "' class='statusImg' rel='" . base64_encode($data['VirtualCurrency']['id']) . "'/>";
                                    ?>
                                    <span class="loader" id="load_<?php echo $data['VirtualCurrency']['id']; ?>"><?php echo $this->html->image('/img/admin/loader.gif'); ?></span>
                                <?php }
                                ?>
                            </td>
                            <td><?php echo date("l, F d Y",  strtotime($data['VirtualCurrency']['created'])); ?></td>
                            <td><?php echo date("l, F d Y",  strtotime($data['VirtualCurrency']['modified'])); ?></td>
                            <td>
                                <?php echo $this->Html->link($this->html->image('/img/admin/edit1.png'), array("controller" => "Packages", "action" => "addVirtualCurrency", base64_encode($data['VirtualCurrency']['id'])), array('escape' => false, 'title' => 'Edit', 'class' => "edidlt")); ?>
                                <?php echo $this->Html->link($this->html->image('/img/admin/delete.png'), array("controller" => "Commons", "action" => "Delete", base64_encode($data['VirtualCurrency']['id']), 'VirtualCurrency'), array('escape' => false, 'title' => 'Delete', 'class' => "edidlt", 'onclick' => "return confirm('Are you sure you want to delete this ?');")); ?>
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

