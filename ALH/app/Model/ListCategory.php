<?php

App::uses('AuthComponent', 'Controller/Component');

class ListCategory extends AppModel {

    var $belongsTo = array(
        'Category' => array(
            'className' => 'Category',
            'foreignKey' => 'category_id',
            'dependent' => true
        )
    );

}

?>