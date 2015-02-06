<?php
App::uses('AuthComponent', 'Controller/Component');

    class Service extends AppModel {
        var $actsAs = array('Containable');
	//public $actsAs = array('Acl' => array('type' => 'requester'),'Containable');
    }
?>