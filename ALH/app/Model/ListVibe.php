<?php

App::uses('AuthComponent', 'Controller/Component');

class ListVibe extends AppModel {

    var $belongsTo = array(
        'Vibe' => array(
            'className' => 'Vibe',
            'foreignKey' => 'vibe_id',
            'dependent' => true
        )
    );

    //);
}

?>