<?php

App::uses('AuthComponent', 'Controller/Component');

class Segment extends AppModel {

    var $hasMany = array(
        'SegmentEmail' => array(
            'className' => 'SegmentEmail',
            'foreignKey' => 'segment_id'
        )
    );

}

?>