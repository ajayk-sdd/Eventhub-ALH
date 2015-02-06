<?php

App::uses('AuthComponent', 'Controller/Component');

class Event extends AppModel {

    //public $actsAs = array('Acl' => array('type' => 'requester'),'Containable');
    public $actsAs = array('Containable');
    var $hasMany = array(
        'EventImages' => array(
            'className' => 'EventImages',
            'foreignKey' => 'event_id',
            'dependent' => true
        ),
        'EventCategory' => array(
            'className' => 'EventCategory',
            'foreignKey' => 'event_id',
            'dependent' => true
        ),
        'EventVibe' => array(
            'className' => 'EventVibe',
            'foreignKey' => 'event_id',
            'dependent' => true
        ),
        'TicketPrice' => array(
            'className' => 'TicketPrice',
            'foreignKey' => 'event_id',
            'dependent' => true
        ),
        'EventEditedUser' => array(
            'className' => 'EventEditedUser',
            'foreignKey' => 'event_id',
            'dependent' => true
        ),
        'EventDate' => array(
            'className' => 'EventDate',
            'foreignKey' => 'event_id',
            'dependent' => true
        ),
        'EventFbattendUser' => array(
            'className' => 'EventFbattendUser',
            'foreignKey' => 'event_id',
            'dependent' => true
        )
    );
    var $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'dependent' => true
        ),
        'MyWpplugin' => array(
            'className' => 'MyWpplugin',
            'foreignKey' => 'user_id',
            'dependent' => true
        )
    );
    var $hasOne = array(
        'Giveaway' => array(
            'className' => 'Giveaway',
            'forignKey' => 'event_id',
            'dependent' => true
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