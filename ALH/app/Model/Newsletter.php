<?php
App::uses('AuthComponent', 'Controller/Component');
    class Newsletter extends AppModel {
	
        public $validate = array(
        'email' => array(
            'isUnique' => array(
                'rule' => array('isUnique','notEmpty','email'),
                'message' => 'Email must be uniuque.'
            )
        )
    );

    }
?>