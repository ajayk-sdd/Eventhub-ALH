<section class="inner-content">
    <div class="center-block">
        <div class="em-sec profile-whole">
            <h1>My Account</h1>
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
                <li>
                    <?php echo $this->Html->link('Order History', '/Sales/orderList'); ?>
                </li>
                <li class="active">
                    <?php echo $this->Html->link('Track', '/Users/track'); ?>
                </li>
            </ul>
            <div class="content_outer">
                <div id="div1" class="content">
                    <!------------------------------------------------------------------------------------------>
                    <!----------------------- events starts below------------------>

                    <div class="clm-wrap">
                        <h2 style="color:#7900A0;">Upcoming Events</h2>
                        <table style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Date</th>
                                    <th>Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($events as $event): ?>
                                    <tr>
                                        <td><?php echo $event['Event']['title']; ?></td>
                                        <td><?php
                                            echo date('l, F d, Y', strtotime($event["EventDate"]["date"])) . "<br>" . date('H:i A', strtotime($event["EventDate"]['start_time'])) . " To " . date('H:i A', strtotime($event["EventDate"]['end_time']));
                                            ?></td>
                                        <td>
                                            <?php
                                            echo $event["Event"]["event_address"] . "<br>";
                                            echo $event["Event"]["cant_find_city"] . "," . $event["Event"]["cant_find_state"] . " " . $event["Event"]["cant_find_zip_code"];
                                            ?>
                                        </td>
                                        <td>
                                            <?php echo $this->html->link('View', array("controller" => "Events", "action" => "viewEvent", base64_encode($event["Event"]["id"])), array('escape' => false, "class" => "")); ?>
                                            <?php echo $this->html->link('Edit', array("controller" => "Events", "action" => "createEvent", base64_encode($event["Event"]["id"])), array('escape' => false, "class" => "")); ?>          
                                            <?php echo $this->Html->link('Delete', array("controller" => "admin/Commons", "action" => "Delete", base64_encode($event['Event']['id']), 'Event'), array('escape' => false, 'title' => 'Delete', 'class' => "", 'onclick' => "return confirm('Are you sure you want to delete this event ?');")); ?>
                                            <span class="loader" id="load_<?php echo $event['Event']['id']; ?>" style="display: none;"><img src="/img/admin/loader.gif" alt=""></span>

                                        </td>    
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div style="clear:both;"></div>
                    <!----------------------- campaign starts below------------------>
                    <div class="clm-wrap">
                        <h2 style="color:#7900A0;">Upcoming Campaigns</h2>
                        <table style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Date</th>
                                    <th>Subject</th>
                                    <th>From</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($campaigns as $campaign): ?>
                                    <tr>
                                        <td>
                                            <?php
                                            echo $campaign['Campaign']['title'];
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo date('l, F d, Y', strtotime($event["EventDate"]["date"])) . "<br>" . date('H:i A', strtotime($event["EventDate"]['start_time'])) . " To " . date('H:i A', strtotime($event["EventDate"]['end_time']));
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo $campaign['Campaign']["subject_line"];
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo $campaign['Campaign']["from_name"] . "<br>" . $campaign['Campaign']["from_email"];
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!----------------------- orders starts below------------------>
                    <div class="clm-wrap">
                        <h2 style="color:#7900A0;">In-Progress Orders</h2>
                        <table style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Package Name</th>
                                    <th>Order Date</th>
                                    <th>Transaction Id</th>
                                    <th>Total Amount</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td>
                                            <?php
                                            echo $order['Package']['name'];
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo date('l, F d, Y H:i A', strtotime($order["Payment"]["created"]));
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo $order['Payment']["transaction_id"];
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo "$".$order['Payment']["amount"];
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!------------------------------------------------------------------------------------------>
                </div>

            </div>


        </div>
    </div>
</section>
<?php echo $this->Html->script("Front/ajaxform"); ?>