<?php

App::uses('AuthComponent', 'Controller/Component');

class Address extends AppModel {

    var $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        )
    );

}

?>