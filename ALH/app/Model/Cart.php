<?php
App::uses('AuthComponent', 'Controller/Component');
    class Cart extends AppModel {
	var $belongsTo =
	    array(
		'Package' => array(
		    'className'    => 'Package',
		    'foreignKey' => 'package_id'
		),'User' => array(
		    'className'    => 'User',
		    'foreignKey' => 'user_id'
	)
	  
	);

    }
?>