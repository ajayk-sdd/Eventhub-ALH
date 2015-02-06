<?php

App::uses('AuthComponent', 'Controller/Component');

class VisitEvent extends AppModel {

    var $belongsTo = array(
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'event_id'
        )
    );

}

?>