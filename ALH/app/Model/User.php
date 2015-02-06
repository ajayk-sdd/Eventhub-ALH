<?php
App::uses('AuthComponent', 'Controller/Component');
    class User extends AppModel {
	public $actsAs = array('Acl' => array('type' => 'requester'),'Containable');
	var $belongsTo =
	    array(
		'Role' => array(
		    'className'    => 'Role',
		    'foreignKey' => 'role_id'
		)
	  
	);
	var $hasOne =
	array(
	    'Brand' => array(
		'className'    => 'Brand',
		'foreignKey' => 'user_id'
	    )
	);
	var $hasMany =
	array(
	    'UserpRegion' => array(
		'className'    => 'UserpRegion',
		'foreignKey' => 'user_id'
	    ),
	    'UserpCategory' => array(
		'className'    => 'UserpCategory',
		'foreignKey' => 'user_id'
	    ),
	    'UserpVibe' => array(
		'className'    => 'UserpVibe',
		'foreignKey' => 'user_id'
	    )
	);
	
	
	function parentNode() {
	    if (!$this->id && empty($this->data)) {
	    return null;
	    }
	    if (isset($this->data['User']['role_id'])) {
	    $groupId = $this->data['User']['role_id'];
	    } else {
	    $groupId = $this->field('role_id');
	    }
	    if (!$groupId) {
	    return null;
	    } else {
	    return array('Role' => array('id' => $groupId));
	    }
	}
		
        /*public $virtualFields = array(
	    'fullname' => 'CONCAT(User.firstname, " ", User.lastname)'
	);*/
	 
	 
         
         public function beforeSave($options = array()) {
	    if (isset($this->data[$this->alias]['password'])) {
		$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
	    }
	    return true;
	}
        
      /*  public $validate = array(
        'email' => array(
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'Email must be uniuque.'
            )
        ),
            'username' => array(
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'Username must be uniuque.'
            )
        ),
            'first_name' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'First name is required.'
            )
        ),
            'last_name' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'Last name is required.'
            )
        ),
            'phone_no' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'Phone no is required.'
            )
        ),
            'address' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'Address is required.'
            )
        )
            ,
            'password' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'Password is required.'
            )
        )
    );*/

    }
?>