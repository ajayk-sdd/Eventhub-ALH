<?php

class Role extends AppModel {

    public $actsAs = array('Acl' => array('type' => 'requester'), 'Containable');

    function parentNode() {
        return null;
    }

    /* public $validate = array(
      'name' => array(
      'rule' => 'notEmpty',
      //'required'   => true,
      ),
      'description' => array(
      'rule' => 'notEmpty',
      //'required'   => true,
      )
      ); */

    public $validate = array(
        'name' => array(
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'Role name must be uniuque.'
            )
        )
    );

}
