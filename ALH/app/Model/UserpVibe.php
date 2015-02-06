<?php
App::uses('AuthComponent', 'Controller/Component');
    class UserpVibe extends AppModel {
	var $belongsTo =
	    array(
		'UserpVibe' => array(
		    'className'    => 'UserpVibe',
		    'foreignKey' => 'user_id'
		),
		'Vibe' => array(
		    'className'    => 'Vibe',
		    'foreignKey' => 'vibe_id'
		)
	  
	);
	
    }
?>