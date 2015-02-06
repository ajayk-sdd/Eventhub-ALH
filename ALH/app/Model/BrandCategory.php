<?php
App::uses('AuthComponent', 'Controller/Component');
    class BrandCategory extends AppModel {
	var $belongsTo =
	    array(
		'BrandCategory' => array(
		    'className'    => 'BrandCategory',
		    'foreignKey' => 'brand_id'
		),
		'Category' => array(
		    'className'    => 'Category',
		    'foreignKey' => 'category_id'
		)
	  
	);
	
    }
?>