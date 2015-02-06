<?php

class Banner extends AppModel {

    var $hasMany = array(
        'BannerImage' => array(
            'className' => 'BannerImage',
            'foreignKey' => 'banner_id',
            'dependent' => true
        )
    );
    var $belongsTo = array(
        'Brand' => array(
            'className' => 'Brand',
            'foreignKey' => 'from_brand',
            'dependent' => true
        ), 'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'event_id',
            'dependent' => true
        )
    );

}

?>