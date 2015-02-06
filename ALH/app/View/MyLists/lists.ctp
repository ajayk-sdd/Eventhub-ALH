<h1>My AList Calendar</h1>
<?php /* ?><div class = "search_box">
    <?php echo $this->Form->create("Event", array("action" => "calendar", "name" => "search_form")); ?>
    <?php echo $this->Form->input("Event.order", array('type' => 'select', 'empty' => 'Sort by....', 'options' => array("Event.title ASC" => "Name A-Z", "Event.title DESC" => "Name Z-A", "Event.start_date ASC" => "Date: Soonest", "Event.created DESC" => "Date: Lattest"), 'div' => false, 'label' => false, "onchange" => "javascript:document.search_form.submit();")); ?>
    <?php echo $this->Form->input("Event.date", array('type' => 'select', 'empty' => 'Select date range', 'options' => array("today" => "Today", "week" => "This week", "month" => "This month", "year" => "This year", "all" => "All"), 'div' => false, 'label' => false, "onchange" => "javascript:document.search_form.submit();")); ?>
    <?php echo $this->Form->input("Event.distance", array('type' => 'select', 'empty' => 'Select distance', 'options' => array("10" => "10", "20" => "20", "30" => "30", "50" => "50", "100" => "100"), 'div' => false, 'label' => false, "onchange" => "javascript:document.search_form.submit();")); ?>
    <?php echo $this->Form->input("Event.lat_long", array("type" => "hidden", "id" => "lat_long")); ?>
    <?php echo $this->Form->input("Event.view", array('type' => 'select', 'options' => array("detailed" => "Detailed view", "list" => "List view"), 'div' => false, 'label' => false, "onchange" => "javascript:document.search_form.submit();")); ?>
    <?php echo $this->Form->input("Event.zip", array('type' => 'text', 'div' => false, 'label' => false, "placeholder" => "Change location, enter zip.", "onchange" => "javascript:document.search_form.submit();")); ?>
    <?php echo $this->Form->end(); ?>
</div><?php */ ?>
<div class = "calendar_list">
    <?php
    $i = 1;
    foreach ($lists as $list) {
        $i++;
        if ($i % 2 == 0) {
            ?>
            <div class = "medium_list left">
                <div class = "calendar_img left"><?php echo $this->html->image("brand/small/" . $list['User']["Brand"]["logo"]); ?></div>
                <div class = "calender_detail left">
                    <b><?php echo $list["MyList"]["list_name"]; ?></b><br/>
                  
                  
                </div>
                <div class = "calendar_action right">
                    <?php echo $this->html->link("Make an Offer", array("controller" => "Events", "action" => "viewEvent", base64_encode($list["MyList"]["id"])), array("class" => "action_link")); ?>
                   
                    <br/>
                </div>
            </div>
    <?php } else { ?>
            <div class = "medium_list right">
                <div class = "calendar_img left"><?php echo $this->html->image("brand/small/" . $list["User"]["Brand"]["logo"]); ?></div>
                <div class = "calender_detail left">
                    <b><?php echo $list["MyList"]["list_name"]; ?></b><br/>
                  
                </div>
                <div class = "calendar_action right">
        <?php echo $this->html->link("Make an Offer", array("controller" => "Events", "action" => "viewEvent", base64_encode($list["MyList"]["id"])), array("class" => "action_link")); ?>
             
                    <br/>
                </div>
            </div><br/>
            <?php
        }
    }
    ?>
</div>
<script>
    // for getting latitude and longitude from IP address
    $.get("http://ipinfo.io", function(response) {
        $("#lat_long").val(response.loc);
    }, "jsonp");
</script>
