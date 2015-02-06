<?php
//pr($listdetail);
?>
<section class="inner-content">
    <div class="center-block">
        <div class="repo-list-wrap">
            <h1>Reporting for <?php echo $listdetail['MyList']['list_name']; ?></h1>
            <div class="repo-list reli-II">

                <dl>
                    <dt>Date of Last Send:</dt>
                    <dd><?php
                        if (isset($listdetail["MyList"]["last_send"])) {
                            echo date("m/d/y", strtotime($listdetail["MyList"]["last_Send"]));
                        } else {
                            echo "N/A";
                        }
                        ?></dd>

                    <dt># Contacts on List: </dt>
                    <dd><?php echo $listdetail['MyList']["total_mail"]; ?>
                    </dd>

                    <dt>Opens: </dt>
                    <dd><?php
                        if ($listdetail["MyList"]["total_mail"] > 0) {
                            echo round((($listdetail["MyList"]["open_mail"] / $listdetail["MyList"]["total_mail"]) * 100), 2) . "%";
                        } else {
                            echo "0%";
                        }
                        ?>
                    </dd>

                    <dt>Clicks: </dt>
                    <dd><?php
                        if ($listdetail["MyList"]["total_mail"] > 0) {
                            echo round((($listdetail["MyList"]["click_mail"] / $listdetail["MyList"]["total_mail"]) * 100), 2) . "%";
                        } else {
                            echo "0%";
                        }
                        ?>
                    </dd>
                    
                    <dt>Bounce: </dt>
                    <dd><?php
                        if ($listdetail["MyList"]["total_mail"] > 0) {
                            echo round((($listdetail["MyList"]["bounce_mail"] / $listdetail["MyList"]["total_mail"]) * 100), 2) . "%";
                        } else {
                            echo "0%";
                        }
                        ?>
                    </dd>
                    
                    <dt>Sent: </dt>
                    <dd><?php
                        if ($listdetail["MyList"]["total_mail"] > 0) {
                            echo round((($listdetail["MyList"]["sent_mail"] / $listdetail["MyList"]["total_mail"]) * 100), 2) . "%";
                        } else {
                            echo "0%";
                        }
                        ?>
                    </dd>

                </dl>
                <div style="text-align:center;">

                    <input type="button" onclick="javascript:history.back();" value="Go Back"> 
                </div>
                <div class="clear"></div>

            </div>
        </div>
    </div>
</section>

