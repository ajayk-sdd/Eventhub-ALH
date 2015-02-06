<?php

App::uses('AuthComponent', 'Controller/Component');

class ListRegion extends AppModel {

    var $belongsTo = array(
        'Region' => array(
            'className' => 'Region',
            'foreignKey' => 'region_id',
            'dependent' => true
        )
    );

}

?>