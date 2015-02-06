<section id="cont_wrapper">
    <section class="content">
        <h1 class="main_heading"><?php echo $this->Html->link('Add Price', '/admin/MyLists/addOfferPrice'); ?></h1>
        <section class="content_info">
            <?php echo $this->Session->flash(); ?>
            <section class="tbldata">
                <div class="srch_box">

                    <?php echo $this->Form->create('Common', array('users', 'action' => 'selectMultiple?model=OfferPrice')); ?>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabulardata">
                        <tr>
                            <th style="width:4%"><?php echo $this->Form->checkbox('selectAll', array('type' => 'checkbox', 'class' => 'textbox', 'label' => '', 'id' => 'selectall', 'name' => 'selectAll')); ?></th>

                            <th style="width:20%;"><?php echo $this->Paginator->sort('MyList.list_name', 'List Name'); ?>
                                <span class="<?php echo ('MyList.list_name' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "list_name ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>

                            <th style="width:20%;"><?php echo $this->Paginator->sort('OfferPrice.dedicated_email_to_send', 'Dedicated Email To Send'); ?>
                                <span class="<?php echo ('OfferPrice.dedicated_email_to_send' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "dedicated_email_to_send ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>

                            <th style="width:20%;"><?php echo $this->Paginator->sort('OfferPrice.multi_event_to_send', 'Multi Event To Send'); ?>
                                <span class="<?php echo ('OfferPrice.multi_event_to_send' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "multi_event_to_send ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>

                            <th style="width:20%;"><?php echo $this->Paginator->sort('OfferPrice.ticket_offered_for_trade', 'Ticket Offered For Trade'); ?>
                                <span class="<?php echo ('OfferPrice.ticket_offered_for_trade' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "ticket_offered_for_trade ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>


                            <th style="width:16%;">Action</th>
                        </tr>
                        <?php foreach ($offer_prices as $offer_price): ?>
                            <tr>
                                <td><?php echo $this->Form->checkbox('checkbox', array('type' => 'checkbox', 'class' => 'textbox case', 'label' => '', 'value' => $offer_price['MyList']['id'], 'name' => 'IDs[]')); ?></td>

                                <td><?php echo $offer_price['MyList']['list_name']; ?></td>

                                <td><?php echo $offer_price['OfferPrice']['dedicated_email_to_send']; ?></td>

                                <td><?php echo $offer_price['OfferPrice']['multi_event_to_send']; ?></td>

                                <td><?php echo $offer_price['OfferPrice']['ticket_offered_for_trade']; ?></td>


                                <td>
                                    <?php echo $this->Html->link($this->html->image('/img/admin/edit1.png'), array("controller" => "MyLists", "action" => "addOfferPrice", base64_encode($offer_price['OfferPrice']['id'])), array('escape' => false, 'title' => 'Edit', 'class' => "edidlt")); ?>
                                    <!--?php echo $this->Html->link($this->html->image('/img/admin/view.png'), array("controller" => "MyLists", "action" => "view", base64_encode($offer_price['MyList']['id'])), array('escape' => false, 'title' => 'View', 'class' => "edidlt")); ?-->

                                    <?php echo $this->Html->link($this->html->image('/img/admin/delete.png'), array("controller" => "Commons", "action" => "Delete", base64_encode($offer_price['OfferPrice']['id']), 'OfferPrice'), array('escape' => false, 'title' => 'Delete', 'class' => "edidlt", 'onclick' => "return confirm('Are you sure you want to delete this Offer Price?');")); ?>

                                </td>    
                            </tr>
                        <?php endforeach; ?>  
                    </table><br/>
                    <?php echo $this->Form->submit("Delete", array('class' => "blu_btn mar_rt", 'div' => false, 'id' => 'loginButton', 'title' => 'Delete', 'name' => 'delete', 'onclick' => "return atleastOneChecked('Are you sure you want to delete these selected Offer Price?');")); ?>
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
