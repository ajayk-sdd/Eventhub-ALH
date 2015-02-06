<h2>Events in this Email:</h2><br>
<?php
//pr($this->Session->read("CampaignEvent"));

if ($this->Session->check("CampaignEvent")) {
    $arr = $this->Session->read("CampaignEvent");
          echo "<ul  class='sp-hide-content show-hide-panel'>";
    foreach ($arr as $key => $value) {
  
        ?>
        <li><?php echo $value; ?><a href="javascript:void(0);" onclick="javascript:selectThisEvent(<?php echo $key; ?>, '<?php echo $value; ?>');">Remove</a></li>
        <?php
        
    }
    echo "</ul>";
    echo '<div align="right" style="width: 100%; float: right; margin-top: 25px;">';
    echo $this->Html->link("Save & Continue",array("controller"=>"Campaigns","action"=>"chooseTemplate"),array("class"=>"clear-search"));
    echo '</div>';
    
} else {
    echo "<b style='color:red;'>No Event Selected Yet</b>";
}
?>