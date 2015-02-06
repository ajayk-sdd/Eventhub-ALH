<?php

class BannerImage extends AppModel {

    var $belongsTo = array(
        'Brand' => array(
            'className' => 'Brand',
            'foreignKey' => 'to_brand',
            'dependent' => true
        )
    );

}

?>