<?php

App::uses('AuthComponent', 'Controller/Component');

class MakeOffer extends AppModel {

    var $belongsTo = array(
        'MyList' => array(
            'className' => 'MyList',
            'foreignKey' => 'my_list_id'
        )
    );

}

?>