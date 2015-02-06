<?php

App::uses('AuthComponent', 'Controller/Component');

class MyList extends AppModel {

    //public $actsAs = array('Containable');
    var $hasMany = array(
        'ListCategory' => array(
            'className' => 'ListCategory',
            'foreignKey' => 'my_list_id',
            'dependent' => true
        ),
        'ListVibe' => array(
            'className' => 'ListVibe',
            'foreignKey' => 'my_list_id',
            'dependent' => true
        ),
        'ListEmail' => array(
            'className' => 'ListEmail',
            'foreignKey' => 'my_list_id',
            'dependent' => true
        ),
        'ListRegion' => array(
            'className' => 'ListRegion',
            'foreignKey' => 'my_list_id',
            'dependent' => true
        ),
        'OpenRate' => array(
            'className' => 'OpenRate',
            'foreignKey' => 'my_list_id',
            'dependent' => true
        )
    );
    var $hasOne = array(
        "MakeOffer"=>array(
            'className' => 'MakeOffer',
            'foreignKey' => 'my_list_id',
            'dependent' => true
        )
    );
    var $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
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