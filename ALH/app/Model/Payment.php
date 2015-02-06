<?php
    class Payment extends AppModel {
	var $actsAs = array('Containable');
	var $belongsTo =
	    array(
		'Package' => array(
		    'className'    => 'Package',
		    'foreignKey' => 'package_id'
		),
		'User' => array(
		    'className'    => 'User',
		    'foreignKey' => 'user_id'
		)
	  
	);

    }
?>