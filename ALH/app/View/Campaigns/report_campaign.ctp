<section class="inner-content">
    <div class="center-block">
        <div class="repo-list-wrap">
            <h1>Report Campaign</h1>
            <div class="repo-list reli-II">
                <?php if (!empty($campaign)) { ?>
                    <dl>
                        <dt>Campaign ID#:  <strong><?php echo $campaign["Campaign"]["id"]; ?></strong></dt>
                        <dd>&nbsp;</dd>
                        <div class="clear"></div>

                        <dt>Subject</dt>
                        <dd><?php echo $campaign["Campaign"]["subject_line"]; ?>
                        </dd>

                        <dt>From Name</dt>
                        <dd><?php echo $campaign["Campaign"]["from_name"]; ?>
                        </dd>

                        <dt>From Email</dt>
                        <dd><?php echo $campaign["Campaign"]["from_email"]; ?>
                        </dd>

                        <dt>Reply Email</dt>
                        <dd><?php echo $campaign["Campaign"]["reply_email"]; ?>
                        </dd>

                        <dt>Date To Send</dt>
                        <dd><?php echo date('l, F d, Y', strtotime($campaign["Campaign"]["date_to_send"])); ?>
                        </dd>

                        <dt>Status</dt>
                        <dd><?php
                            $today = date("Y/m/d");
                            if ($campaign['Campaign']['date_to_send'] > $today) {
                                echo "Upcoming";
                            } else {
                                echo "Sent";
                            }
                            ?>
                        </dd>

                        <dt>Subscriber</dt>
                        <dd><?php echo $campaign["Campaign"]["total_mail"]; ?>
                        </dd>

                        <dt>Sent Mail</dt>
                        <dd><?php echo $campaign["Campaign"]["sent_mail"]; ?>
                        </dd>

                        <dt>Open Rate</dt>
                        <dd><?php
                            if ($campaign["Campaign"]["total_mail"] > 0) {
                                echo round((($campaign["Campaign"]["open_mail"] / $campaign["Campaign"]["total_mail"]) * 100), 2) . "%";
                            } else {
                                echo "0%";
                            }
                            ?>
                        </dd>

                        <dt>Bounce Rate</dt>
                        <dd><?php
                            if ($campaign["Campaign"]["total_mail"] > 0) {
                                echo round((($campaign["Campaign"]["bounce_mail"] / $campaign["Campaign"]["total_mail"]) * 100), 2) . "%";
                            } else {
                                echo "0%";
                            }
                            ?>
                        </dd>

                        <dt>Click Rate</dt>
                        <dd><?php
                            if ($campaign["Campaign"]["total_mail"] > 0) {
                                echo round((($campaign["Campaign"]["click_mail"] / $campaign["Campaign"]["total_mail"]) * 100), 2) . "%";
                            } else {
                                echo "0%";
                            }
                            ?>
                        </dd>

                    </dl>
                    <?php
                } else {
                    echo "<span style='color:red;'>Data Not Found</span>";
                }
                ?>
                <div style="text-align:center;">
                    <input type="button" onclick="javascript:history.back();" value="Go Back"> 
                </div>
                <div class="clear"></div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</section>
<?php echo $this->Html->script('/js/Front/Events/createEvent'); ?>
<script>
    function openWindow(url) {
        window.open(url, "_blank", "toolbar=no, scrollbars=yes, resizable=yes, top=200, left=400, width=640, height=400");
    }
</script>