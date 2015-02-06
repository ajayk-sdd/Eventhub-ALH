<?php

App::uses('AuthComponent', 'Controller/Component');

class Region extends AppModel {
    var $hasMany =
	    array(
		'Event' => array(
		    'className'    => 'Event',
		    'foreignKey' => 'event_location'
                    
		)
        );
}

?>