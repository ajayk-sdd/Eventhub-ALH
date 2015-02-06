<?php
$contain = array();
if ($item == "campaign_activity") {
    $contain = array(
        'opened' => array(
            'Open All Recent Campaigns' => 'All Recent Campaigns',
            'Open Sent Campaigns' => 'Sent Campaigns'
        ),
        'clicked' => array(
            'Click All Recent Campaigns' => 'All Recent Campaigns',
            'Click Sent Campaigns' => 'Sent Campaigns'
        ),
        'was sent' => array(
            "was sent Sent Campaigns" => "Sent Campaigns"
        ),
        "did not open" => array(
            'did not open All Recent Campaigns' => 'All Recent Campaigns',
            'did not open Sent Campaigns' => 'Sent Campaigns'
        ),
        'did not clicked' => array(
            'did not All Recent Campaigns' => 'All Recent Campaigns',
            'did not Sent Campaigns' => 'Sent Campaigns'
        ),
        'was not sent' => array(
            "was not sent Sent Campaigns" => "Sent Campaigns"
        )
    );
} else if ($item == "date_Added") {
    $contain = array("is after" => array(
            "after the last campaign was sent" => "the last campaign was sent",
            "after a specific campaign was sent" => "a specific campaign was sent",
            "after a specific date" => "a specific date"
        ),
        "is before" => array(
            "before the last campaign was sent" => "the last campaign was sent",
            "before a specific campaign was sent" => "a specific campaign was sent",
            "before a specific date" => "a specific date"
        ),
        "is" => array(
            "is the last campaign was sent" => "the last campaign was sent",
            "is a specific campaign was sent" => "a specific campaign was sent",
            "is a specific date" => "a specific date"
        )
    );
} else if ($item == "info_changed") {
    $contain = array("is after" => array(
            "after the last campaign was sent" => "the last campaign was sent",
            "after a specific campaign was sent" => "a specific campaign was sent",
            "after a specific date" => "a specific date"
        ),
        "is before" => array(
            "before the last campaign was sent" => "the last campaign was sent",
            "before a specific campaign was sent" => "a specific campaign was sent",
            "before a specific date" => "a specific date"
        ),
        "is" => array(
            "is the last campaign was sent" => "the last campaign was sent",
            "is a specific campaign was sent" => "a specific campaign was sent",
            "is a specific date" => "a specific date"
        )
    );
} else if ($item == "location") {
    $contain = array("is within" => "is within", "is not within" => "is not within", "is in country" => "is in country", "is not in country" => "is not in country", "is in US state" => "is in US state", "is zip" => "is zip", "is not zip"=>"is not zip", "is within distance of zip" => "is within distance of zip", "is unknown" => "is unknown"
    );
} else if ($item == "member_rating") {
    $contain = array("is"=>"is","is not"=>"is not","is greater then"=>"is greater then","is less then","is less then");
}
echo $this->Form->input("contain",array("type"=>"select","options"=>$contain,"div"=>FALSE,"label"=>FALSE,"onchange"=>"javascript:selectContains();"));
?>