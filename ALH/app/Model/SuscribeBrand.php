<?php

App::uses('AuthComponent', 'Controller/Component');

class SuscribeBrand extends AppModel {

    //public $actsAs = array('Acl' => array('type' => 'requester'),'Containable');

    var $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        ), 'Brand' => array(
            'className' => 'Brand',
            'foreignKey' => 'brand_id'
        )
    );

}

?>