<?php
App::uses('AuthComponent', 'Controller/Component');
    class TicketGiveaway extends AppModel {
	//public $actsAs = array('Acl' => array('type' => 'requester'),'Containable');
	var $belongsTo =
	    array(
		'Event' => array(
		    'className'    => 'Event',
		    'foreignKey' => 'event_id'
		),'User' => array(
		    'className'    => 'User',
		    'foreignKey' => 'user_id'
		)
	);
	

        
        public $validate = array(
        'email' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'Email required.'
            )
        ),
            'first_name' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'First name is required.'
            )
        ),
            'last_name' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'Last name is required.'
            )
//        ),
//            'zip' => array(
//            'required' => array(
//                'rule' => 'notEmpty',
//                'message' => 'Zip code is required.'
//            )
        ),
            'phone' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'Phone code is required.'
            )
        )
    );

    }
?>