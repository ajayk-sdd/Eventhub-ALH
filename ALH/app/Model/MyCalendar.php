<?php

App::uses('AuthComponent', 'Controller/Component');

class MyCalendar extends AppModel {

    //public $actsAs = array('Acl' => array('type' => 'requester'),'Containable');

    var $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        ), 'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'event_id'
        )
    );

}

?>