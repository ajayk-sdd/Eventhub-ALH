<?php

App::uses('AuthComponent', 'Controller/Component');

class CampaignList extends AppModel {

   
    var $belongsTo = array(
        'MyList' => array(
            'className' => 'MyList',
            'forignKey' => 'my_list_id'
        )
    );

}

?>