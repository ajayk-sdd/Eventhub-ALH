<?php
App::uses('AuthComponent', 'Controller/Component');
    class BrandVibe extends AppModel {
	var $belongsTo =
	    array(
		'BrandVibe' => array(
		    'className'    => 'BrandVibe',
		    'foreignKey' => 'brand_id'
		),
		'Vibe' => array(
		    'className'    => 'Vibe',
		    'foreignKey' => 'vibe_id'
		)
	  
	);
	
    }
?>