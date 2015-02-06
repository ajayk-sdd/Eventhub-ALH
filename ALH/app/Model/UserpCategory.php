<?php
App::uses('AuthComponent', 'Controller/Component');
    class UserpCategory extends AppModel {
	var $belongsTo =
	    array(
		'UserpCategory' => array(
		    'className'    => 'UserpCategory',
		    'foreignKey' => 'user_id'
		),
		'Category' => array(
		    'className'    => 'Category',
		    'foreignKey' => 'category_id'
		)
	  
	);
	
    }
?>