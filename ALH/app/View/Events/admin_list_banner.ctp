<section id="cont_wrapper">
    <section class="content">
        <h1 class="main_heading"><?php echo $this->Html->link('Add Banner', '/admin/Events/addBanner'); ?></h1>
        <section class="content_info">
            <?php echo $this->Session->flash(); ?>
            <section class="tbldata">
                <?php echo $this->Form->create('Common', array('Regions', 'action' => 'selectMultiple?model=BannerAdd')); ?>

                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabulardata">
                    <tr>
                        <th style="width:10%"><?php echo $this->Form->checkbox('selectAll', array('type' => 'checkbox', 'class' => 'textbox', 'label' => '', 'id' => 'selectall', 'name' => 'selectAll')); ?></th>
                        <th style="width:18%;"><?php echo $this->Paginator->sort('Event.title', 'Event Title'); ?>
                            <span class="<?php echo ('Event.title' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "title ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:18%;"><?php echo $this->Paginator->sort('User.username', 'Add By'); ?>
                            <span class="<?php echo ('User.username' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "username ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:18%;"><?php echo $this->Paginator->sort('BannerAdd.banner', 'Banner Image'); ?>
                            <span class="<?php echo ('BannerAdd.banner' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "banner ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:18%;"><?php echo $this->Paginator->sort('BannerAdd.status', 'Status'); ?>
                            <span class="<?php echo ('BannerAdd.status' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "status ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>

                        <th style="width:18%;">Action</th>
                    </tr>
                    <?php foreach ($banners as $data): ?>
                        <tr>
                            <td><?php echo $this->Form->checkbox('checkbox', array('type' => 'checkbox', 'class' => 'textbox case', 'label' => '', 'value' => $data['BannerAdd']['id'], 'name' => 'IDs[]')); ?></td>
                            <td><?php echo $data['Event']['title']; ?></td>
                            <td><?php echo $data['User']['username']; ?></td>
                            <td><?php echo $this->html->image("BannerAdd/small/".$data['BannerAdd']['banner']); ?></td>
                            <td>
                                <?php
                                $model = "BannerAdd";
                                if ($data['BannerAdd']['status'] == 1) {

                                    echo "<img src='/img/admin/active.png' title='Change Status' id='" . $data['BannerAdd']['id'] . "' value='" . $data['BannerAdd']['status'] . "' dir='" . $model . "' class='statusImg' rel='" . base64_encode($data['BannerAdd']['id']) . "'/>";
                                    ?>
                                    <span class="loader" id="load_<?php echo $data['Region']['id']; ?>"><?php echo $this->html->image('/img/admin/loader.gif'); ?></span>
                                <?php
                                } else {
                                    echo "<img src='/img/admin/inactive.png' title='Change Status' id='" . $data['BannerAdd']['id'] . "' value='" . $data['BannerAdd']['status'] . "' dir='" . $model . "' class='statusImg' rel='" . base64_encode($data['BannerAdd']['id']) . "'/>";
                                    ?>
                                    <span class="loader" id="load_<?php echo $data['Region']['id']; ?>"><?php echo $this->html->image('/img/admin/loader.gif'); ?></span>
                                <?php }
                                ?>
                            </td></td>
                            <td>
                                <?php echo $this->Html->link($this->html->image('/img/admin/edit1.png'), array("controller" => "Regions", "action" => "add", base64_encode($data['BannerAdd']['id'])), array('escape' => false, 'title' => 'Edit', 'class' => "edidlt")); ?>
    <?php echo $this->Html->link($this->html->image('/img/admin/delete.png'), array("controller" => "Commons", "action" => "Delete", base64_encode($data['BannerAdd']['id']), 'BannerAdd'), array('escape' => false, 'title' => 'Delete', 'class' => "edidlt", 'onclick' => "return confirm('Are you sure you want to delete this banner ?');")); ?>
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

