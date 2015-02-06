<?php
App::uses('AuthComponent', 'Controller/Component');
    class Brand extends AppModel {
	//public $actsAs = array('Acl' => array('type' => 'requester'),'Containable');
	
        var $belongsTo =
	    array(
		
                'User' => array(
		    'className'    => 'User',
		    'foreignKey' => 'user_id'
		),
                
	);
	
	var $hasOne =
	array(
	    'SuscribeBrand' => array(
		    'className'    => 'SuscribeBrand',
		    'foreignKey' => 'brand_id'
		)
	);
        
	var $hasMany =
	array(
	    'BrandCategory' => array(
		'className'    => 'BrandCategory',
		'foreignKey' => 'brand_id'
	    ),
	    'BrandVibe' => array(
		'className'    => 'BrandVibe',
		'foreignKey' => 'brand_id'
	    )
	);	
      
       
        public $validate = array(
        'name' => array(
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'Brand name must be unique.'
            )
        ),
            'user_id' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'User is required.'
            )
        ),
            'description' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'Description is required.'
            )
        )
    );

    }
?>