<?php
App::uses('AuthComponent', 'Controller/Component');
    class UserpRegion extends AppModel {
	var $belongsTo =
	    array(
		'UserpRegion' => array(
		    'className'    => 'UserpRegion',
		    'foreignKey' => 'user_id'
		),
		'Region' => array(
		    'className'    => 'Region',
		    'foreignKey' => 'region_id'
		)
	  
	);
	
    }
?>