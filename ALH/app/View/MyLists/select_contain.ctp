<?php

//pr($campaigns);
if (isset($campaigns) && !empty($campaigns)) {
    echo $this->Form->input("campaign", array("type" => "select", "options" => $campaigns, "div" => FALSE, "label" => FALSE, "id" => "text_value"));
} else if (isset($location) && !empty($location)) {
    echo $this->Form->input("distance", array("type" => "select", "options" => array("25" => "25", "50" => "50", "75" => "75", "100" => "100", "125" => "125", "150" => "150", "175" => "175", "200" => "200"), "div" => FALSE, "label" => FALSE, "id" => "text_value"));
    echo $this->Form->input("zip", array("type" => "text", "div" => FALSE, "label" => FALSE, "id" => "zip_value"));
} else if (isset($country) && !empty($country)) {
    echo $this->Form->input("country", array("type" => "select", "options" => array("IN" => "India", "US" => "United States"), "div" => FALSE, "label" => FALSE, "id" => "text_value"));
} else if (isset($us) && !empty($us)) {
    echo $this->Form->input("country", array("type" => "select", "options" => array("US" => "United States"), "div" => FALSE, "label" => FALSE, "id" => "text_value"));
} else if (isset($zip) && !empty($zip)) {
    echo $this->Form->input("zip", array("type" => "text", "div" => FALSE, "label" => FALSE, "id" => "text_value"));
} else if(isset ($unknown) && !empty ($unknown)){
    echo $this->Form->input("zip", array("type" => "hidden", "div" => FALSE, "label" => FALSE, "id" => "text_value"));
}else {
    echo $this->Form->input("date", array("type" => "text", "div" => FALSE, "label" => FALSE, "id" => "text_value"));
}
?>