<section id="cont_wrapper">
    <section class="content">
        <h1 class="main_heading"><?php echo $this->Html->link('List Offer', '/admin/MyLists/listOffer'); ?></h1>
        <section class="content_info">
            <?php echo $this->Session->flash(); ?>
            <section class="tbldata">
                <div class="srch_box">

                    <?php echo $this->Form->create('Common', array('users', 'action' => 'selectMultiple?model=MakeOffer')); ?>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabulardata">
                        <tr>
                            <th style="width:2%"><?php echo $this->Form->checkbox('selectAll', array('type' => 'checkbox', 'class' => 'textbox', 'label' => '', 'id' => 'selectall', 'name' => 'selectAll')); ?></th>

                            <th style="width:14%;"><?php echo $this->Paginator->sort('MyList.list_name', 'List Name'); ?>
                                <span class="<?php echo ('MyList.list_name' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "list_name ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>

                            <th style="width:14%;"><?php echo $this->Paginator->sort('MakeOffer.dedicated_email_to_send', 'Dedicated Email To Send'); ?>
                                <span class="<?php echo ('MakeOffer.dedicated_email_to_send' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "dedicated_email_to_send ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>

                            <th style="width:14%;"><?php echo $this->Paginator->sort('MakeOffer.multi_event_to_send', 'Multi Event To Send'); ?>
                                <span class="<?php echo ('MakeOffer.multi_event_to_send' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "multi_event_to_send ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>

                            <th style="width:14%;"><?php echo $this->Paginator->sort('MakeOffer.ticket_offered_for_trade', 'Ticket Offered For Trade'); ?>
                                <span class="<?php echo ('MakeOffer.ticket_offered_for_trade' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "ticket_offered_for_trade ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>

                            <th style="width:14%;"><?php echo $this->Paginator->sort('MakeOffer.ticket_value', 'Ticket Value'); ?>
                                <span class="<?php echo ('MakeOffer.ticket_value' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "ticket_value ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>

                            <th style="width:14%;"><?php echo $this->Paginator->sort('MakeOffer.adjusted_price', 'Adjusted Price'); ?>
                                <span class="<?php echo ('MakeOffer.adjusted_price' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "adjusted_price ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>

                            <th style="width:6%;"><?php echo $this->Paginator->sort('MakeOffer.status', 'Status'); ?>
                                <span class="<?php echo ('MakeOffer.status' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "status ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>


                            <th style="width:8%;">Action</th>
                        </tr>
                        <?php foreach ($makeOffers as $makeOffer): ?>
                            <tr>
                                <td><?php echo $this->Form->checkbox('checkbox', array('type' => 'checkbox', 'class' => 'textbox case', 'label' => '', 'value' => $makeOffer['MyList']['id'], 'name' => 'IDs[]')); ?></td>

                                <td><?php echo $makeOffer['MyList']['list_name']; ?></td>

                                <td><?php echo $makeOffer['MakeOffer']['dedicated_email_to_send']; ?></td>

                                <td><?php echo $makeOffer['MakeOffer']['multi_event_to_send']; ?></td>

                                <td><?php echo $makeOffer['MakeOffer']['ticket_offered_for_trade']; ?></td>

                                <td><?php echo $makeOffer['MakeOffer']['ticket_value']; ?></td>

                                <td><?php echo $makeOffer['MakeOffer']['adjusted_price']; ?></td>

                                <td>
                                    <?php
                                    $model = "MakeOffer";
                                    if ($makeOffer['MakeOffer']['status'] == 1) {

                                        echo "<img src='/img/admin/active.png' title='Change Status' id='" . $makeOffer['MakeOffer']['id'] . "' value='" . $makeOffer['MakeOffer']['status'] . "' dir='" . $model . "' class='statusImg' rel='" . base64_encode($makeOffer['MakeOffer']['id']) . "'/>";
                                        ?>
                                        <span class="loader" id="load_<?php echo $makeOffer['MakeOffer']['id']; ?>"><?php echo $this->html->image('/img/admin/loader.gif'); ?></span>
                                        <?php
                                    } else {
                                        echo "<img src='/img/admin/inactive.png' title='Change Status' id='" . $makeOffer['MakeOffer']['id'] . "' value='" . $makeOffer['MakeOffer']['status'] . "' dir='" . $model . "' class='statusImg' rel='" . base64_encode($makeOffer['MakeOffer']['id']) . "'/>";
                                        ?>
                                        <span class="loader" id="load_<?php echo $makeOffer['MakeOffer']['id']; ?>"><?php echo $this->html->image('/img/admin/loader.gif'); ?></span>
                                    <?php }
                                    ?>
                                </td>


                                <td>
                                    <?php echo $this->Html->link($this->html->image('/img/admin/view.png'), array("controller" => "MyLists", "action" => "offerView", base64_encode($makeOffer['MakeOffer']['id'])), array('escape' => false, 'title' => 'View', 'class' => "edidlt")); ?>
                                    <!--?php echo $this->Html->link($this->html->image('/img/admin/view.png'), array("controller" => "MyLists", "action" => "view", base64_encode($makeOffer['MyList']['id'])), array('escape' => false, 'title' => 'View', 'class' => "edidlt")); ?-->

                                    <?php echo $this->Html->link($this->html->image('/img/admin/delete.png'), array("controller" => "Commons", "action" => "Delete", base64_encode($makeOffer['MakeOffer']['id']), 'MakeOffer'), array('escape' => false, 'title' => 'Delete', 'class' => "edidlt", 'onclick' => "return confirm('Are you sure you want to delete this offer request ?');")); ?>

                                </td>    
                            </tr>
                        <?php endforeach; ?>  
                    </table><br/>
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
