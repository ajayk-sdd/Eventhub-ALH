<?php

App::uses('AuthComponent', 'Controller/Component');

class EventDate extends AppModel {

    var $belongsTo = array(
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'event_id'
        )
    );

}

?>