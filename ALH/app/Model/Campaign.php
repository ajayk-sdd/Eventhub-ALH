<?php

App::uses('AuthComponent', 'Controller/Component');

class Campaign extends AppModel {

    var $hasMany = array(
        'CampaignEvent' => array(
            'className' => 'CampaignEvent',
            'foreignKey' => 'campaign_id'
        ),
        'CampaignList' => array(
            'className' => 'CampaignList',
            'foreignKey' => 'campaign_id'
        )
    );
    var $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'forignKey' => 'user_id'
        )
    );

}

?>