<section class="inner-content">
    <div class="center-block">
        <div class="em-sec profile-whole">
            <h1>My Account</h1>
             <ul class="tabs profile-tabs">
                <li>
                    <?php echo $this->Html->link('Profile & Preferences', '/Users/viewProfile'); ?>
                </li>
                <li  class="active">
                    <?php echo $this->Html->link('Added Events', '/Events/MyEventList'); ?>
                </li>
                <li>
                    <?php echo $this->Html->link('List Subscriptions', '/brands/brandList'); ?>
                </li>
                <li>
                    <?php echo $this->Html->link('Billing Info', '/Users/BillingInfo'); ?>
                </li>
                <li>
                    <?php echo $this->Html->link('Order History', '/Sales/orderList'); ?>
                </li>
                <li>
                    <?php echo $this->Html->link('Track', '/Users/track'); ?>
                </li>
            </ul>
           
            <div class="content_outer">
                <div id="div1" class="content">
                    <!------------------------------------------------------------------------------------------>
                    <div class="clm-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width:25%;"><?php echo $this->Paginator->sort('Event.title', 'Title'); ?></th>
                                    <th style="width:25%;"><?php echo $this->Paginator->sort('Event.start_date', 'Schedule Date'); ?></th>
                                    <th style="width:25%;"><?php echo $this->Paginator->sort('Event.status', 'Status'); ?></th>
                                    <th style="width:25%;"><?php echo $this->Paginator->sort('Event.event_from', 'Event From'); ?></th>
                                    <th style="width:25%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($events as $event): ?>
                                    <tr>
                                        <td><?php echo $event['Event']['title']; ?></td>
                                        <td>
                                        <?php
                                        $dateInc = 0;
                                        foreach ($event["EventDate"] as $ed)
                                        {
                                            echo date('l, F d, Y', strtotime($ed["date"])) . "  " . date('h:i A', strtotime($ed['start_time'])) . " - " . date('h:i A', strtotime($ed['end_time'])) . "<br>";
                                            
                                            $dateInc++;
                                            if($dateInc>3)
                                            {
                                                break;
                                            }
                                        }
                                        ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($event['Event']['status'] == 1 && isset($event["EventDate"][0]["date"])) {
                                                if (strtotime($event["EventDate"][0]["date"]) >= strtotime(date("Y-m-d h:m:s"))) {
                                                    echo "Upcoming";
                                                } else {
                                                    echo "Completed";
                                                }
                                            } else {
                                                echo "Pending ALH Approval";
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo $event['Event']['event_from']; ?></td>
                                        <td>
                                            <?php echo $this->html->link('View', array("controller" => "Events", "action" => "viewEvent", base64_encode($event["Event"]["id"])), array('escape' => false, "class" => "")); ?>
                                            <?php
                                            if($event['Event']['event_from']!="facebook")
                                            {
                                            echo $this->html->link('Edit', array("controller" => "Events", "action" => "editEvent", base64_encode($event["Event"]["id"])), array('escape' => false, "class" => ""));
                                            }
                                            ?>          
                                            <?php echo $this->Html->link('Delete', array("controller" => "admin/Commons", "action" => "Delete", base64_encode($event['Event']['id']), 'Event'), array('escape' => false, 'title' => 'Delete', 'class' => "", 'onclick' => "return confirm('Are you sure you want to delete this event ?');")); ?>
                                            <span class="loader" id="load_<?php echo $event['Event']['id']; ?>" style="display: none;"><img src="/img/admin/loader.gif" alt=""></span>

                                        </td>    
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="clear"></div>
                    <div class="event-pagination paginate-list">
                        <span class="peginationTxt"><?php echo $this->Paginator->counter(array('format' => 'Events %start% - %end% of %count%')); ?></span>
                        <?php
                        echo $this->Paginator->first('', null, null, array());
                        echo $this->Paginator->prev('', null, null, array());
                        echo $this->Paginator->next('', null, null, array());
                        echo $this->Paginator->Last('', null, null, array());
                        ?>
                        <div class="clear"></div>
                    </div>
                    <!------------------------------------------------------------------------------------------>
                </div>

            </div>


        </div>
    </div>
</section>
<?php echo $this->Html->script("Front/ajaxform"); ?>