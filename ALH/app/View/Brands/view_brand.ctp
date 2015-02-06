<section class="inner-content">
    <div class="center-block">
        <div class="repo-list-wrap">
            <h1>Brand Details</h1>
            <div class="repo-list reli-II brnd-detail">
               
                <dl>
                    <dt><span class="event-box"><?php echo $this->html->image("brand/small/" . $brand['Brand']['logo'],array("class"=>"event_img brnd-img")); ?></span></dt>
                   <dd>
                    <dl>
                        <dt>Name: </dt>
                    <dd><?php echo $brand['Brand']['name']; ?></dd>

                    <dt>Username: </dt>
                    <dd><?php echo $brand['User']['username']; ?>
                    </dd>

                    <dt>Status: </dt>
                    <dd><?php
                        if ($brand['Brand']['status'] == 1)
                            echo 'Active';
                        else
                            echo 'Inactive';
                        ?>
                    </dd>
                    </dl>
                    </dd>
                </dl><div class="clear">&nbsp;</div>
                <dl>
                    <dt>Description: </dt>
                    <dd><?php echo $brand['Brand']['description']; ?>
                    </dd>
                   
                </dl>
                <div class="go-back">
                  
                    <input type="button" onclick="javascript:history.back();" value="Go Back"> 
                </div>
                <div class="clear"></div>
               
            </div>
        </div>
    </div>
</section>

<!--Content Wrapper Ends-->
<?php echo $this->Html->script('/js/admin/Brands/admin_add'); ?>