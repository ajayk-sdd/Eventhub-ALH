<?php
App::uses('AuthComponent', 'Controller/Component');
    class Package extends AppModel {
	public $hasAndBelongsToMany = array(
	    'Service' =>
		array(
		    'className' => 'Service',
		    'joinTable' => 'packages_services',
		    'foreignKey' => 'package_id',
		    'associationForeignKey' => 'service_id',
		    'unique' => true,
		)
	    );

    }
?>