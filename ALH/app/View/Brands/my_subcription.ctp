<?php
//pr($brands);
?>

<h1>My Subscription</h1>
<div class = "search_box">
    <?php echo $this->Form->create("Brand", array("action" => "mySubcription", "name" => "search_form")); ?>
    <?php echo $this->Form->input("Brand.name", array('type' => 'text', 'div' => false, 'label' => false, "placeholder" => "Enter Brand Name","required" => false)); ?>
    <?php echo $this->Form->input("limit", array('type' => 'select', 'options' => array("10" => "10", "20" => "20", "50" => "50", "100" => "100"), 'div' => false, 'label' => "Show Per Page:")); ?>
    <?php echo $this->Form->input("Brand.list", array('type' => 'select', 'options' => array("1" => "Brands I Subscribe to", "2" => "All Brands", "3" => "Brands I am not subscribed to"), 'div' => false, 'label' => "Brand List:")); ?>
   <?php echo $this->Form->submit('Search', array('label' => false, 'div' => false, "class" => "blu_btn mar_rt")); ?>
    <?php echo $this->Form->end(); ?>
</div>
<div class = "calendar_list">
    <?php
    $i = 1;
    foreach ($brands as $brand) {
        ?>
        <div class = "medium_list left">
            <div class = "calendar_img left"><?php echo $this->html->image("brand/small/" . $brand["Brand"]["logo"]); ?></div>
            <div class = "calender_detail left">
                <b><?php echo $brand["Brand"]["name"]; ?></b><br/>
                <?php echo $brand["User"]["username"]; ?><br/>
                <?php echo $brand["Brand"]["description"]; ?><br/>
                <?php //echo date("l jS \of F Y h:i:s A", strtotime($brand["Brand"]["start_date"])); ?>
            </div>
            <div class = "calendar_action right">
                <?php echo $this->html->link("View Brand", array("controller" => "Brands", "action" => "viewBrand", base64_encode($brand["Brand"]["id"])), array("class" => "action_link")); ?>
                <a class="action_link" href="javascript:void(0);" onclick="suscribe_to_brand(<?php echo $brand['Brand']['id']; ?>)"><span id = "<?php echo $brand["Brand"]["id"]; ?>">Unsubscribed
                    </span></a>
                <span class="loader" id="load_<?php echo $brand['Brand']['id']; ?>" style="display: none;"><img src="/img/admin/loader.gif" alt=""></span>
                <br/>
            </div>
        </div><div style="clear: both;"></div>
        <?php
    }
    ?>
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
</div>