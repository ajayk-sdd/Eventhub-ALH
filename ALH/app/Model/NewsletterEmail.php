<?php
App::uses('AuthComponent', 'Controller/Component');
    class NewsletterEmail extends AppModel {
	
        public $validate = array(
        'subject' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Subject should not be empty'
            )
        )
    );

    }
?>