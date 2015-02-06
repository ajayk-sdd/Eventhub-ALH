<?php
App::uses('AuthComponent', 'Controller/Component');
    class EventCategory extends AppModel {
	//public $actsAs = array('Acl' => array('type' => 'requester'),'Containable');
	var $belongsTo =
	    array(
		
                'Category' => array(
		    'className'    => 'Category',
		    'foreignKey' => 'category_id'
		)
	);
	
	
		
      
       
//        public $validate = array(
//        'email' => array(
//            'isUnique' => array(
//                'rule' => 'isUnique',
//                'message' => 'Email must be uniuque.'
//            )
//        ),
//            'username' => array(
//            'isUnique' => array(
//                'rule' => 'isUnique',
//                'message' => 'Username must be uniuque.'
//            )
//        ),
//            'first_name' => array(
//            'required' => array(
//                'rule' => 'notEmpty',
//                'message' => 'First name is required.'
//            )
//        ),
//            'last_name' => array(
//            'required' => array(
//                'rule' => 'notEmpty',
//                'message' => 'Last name is required.'
//            )
//        ),
//            'phone_no' => array(
//            'required' => array(
//                'rule' => 'notEmpty',
//                'message' => 'Phone no is required.'
//            )
//        ),
//            'address' => array(
//            'required' => array(
//                'rule' => 'notEmpty',
//                'message' => 'Address is required.'
//            )
//        )
//            ,
//            'password' => array(
//            'required' => array(
//                'rule' => 'notEmpty',
//                'message' => 'Password is required.'
//            )
//        )
//    );

    }
?>