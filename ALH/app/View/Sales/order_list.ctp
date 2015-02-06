<section class="inner-content">
    <div class="center-block">
        <div class="em-sec">
            <div class="profile-whole">
                <h1>My Account</h1>
            </div>
            <ul class="tabs profile-tabs">
                <li>
                    <?php echo $this->Html->link('Profile & Preferences', '/Users/viewProfile'); ?>
                </li>
                <li>
                    <?php echo $this->Html->link('Added Events', '/Events/MyEventList'); ?>
                </li>
                <li>
                    <?php echo $this->Html->link('List Subscriptions', '/brands/brandList'); ?>
                </li>
                <li>
                    <?php echo $this->Html->link('Billing Info', '/Users/BillingInfo'); ?>
                </li>
                <li class="active">
                    <?php echo $this->Html->link('Order History', '/Sales/orderList'); ?>
                </li>
                <li>
                    <?php echo $this->Html->link('Track', '/Users/track'); ?>
                </li>
            </ul>
           
            <div class="content_outer">
                <div id="div3" class="content">
                    <?php echo $this->Session->flash(); ?>
                    <div class="tbldata">
                       
                        <!--------------------------------- search ends ------------------------------->
                        <?php echo $this->Form->create('Common', array('users', 'action' => 'selectMultiple?model=User')); ?>
                        <div class="clm-wrap">

                            <table>
                                <thead>
                                    <tr>
                                        <th style="width:20%;">Package</th>
                                        <th style="width:10%;">Amount</th>
                                        <th style="width:20%;">Transaction Id</th>
                                        <th style="width:20%;">Order Date</th>
                                        <th style="width:20%;">Status</th>
                                        <th style="width:10%;">Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sales as $data): ?>
                                        <tr>
                                            <td><?php echo $data['Package']['name'] ?></td>
                                            <td>$ <?php echo $data['Payment']['amount']; ?></td>
                                            <td><?php echo $data['Payment']['transaction_id']; ?></td>
                                            <td><?php echo $data['Payment']['created']; ?></td>
                                            <td><?php
                                                if ($data['Payment']['status'] == 0)
                                                    echo "In Progress";
                                                else
                                                    echo "Completed";
                                                ?></td>
                                            <td>
                                                <?php
                                                    $url = "http://" . $_SERVER["HTTP_HOST"] . "/Sales/invoice/" . base64_encode($data['Payment']['id']);
                                                    echo $this->Html->link('Invoice', 'javascript:void(0);',array("onclick"=>"javascript:openWindow('$url');"));
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="clear"></div>


                        <?php echo $this->Form->end(); ?>
                    </div>

                    <div class="event-pagination paginate-list">
                        <span class="peginationTxt"><?php echo $this->Paginator->counter(array('format' => 'Events %start% - %end% of %count%')); ?></span>
                        <?php
                        echo $this->Paginator->first('', null, null, array('class' => 'disabled'));
                        echo $this->Paginator->prev('', null, null, array('class' => 'disabled'));
                        //echo $this->Paginator->numbers(array('separator' => ''));
                        echo $this->Paginator->next('', null, null, array('class' => 'disabled'));
                        echo $this->Paginator->Last('', null, null, array('class' => 'disabled'));
                        //echo $this->Paginator->counter(array('format' => 'Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%'));
                        ?>

                    </div>

                </div></div></div></div>
</section>
<script>
function openWindow(url) {
    window.open(url, "_blank", "toolbar=no, scrollbars=yes, resizable=yes, top=200, left=400, width=640, height=400");
}
</script>