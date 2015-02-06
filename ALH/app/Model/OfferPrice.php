<?php

App::uses('AuthComponent', 'Controller/Component');

class OfferPrice extends AppModel {

    var $belongsTo = array(
        'MyList' => array(
            'className' => 'MyList',
            'foreignKey' => 'my_list_id'
        )
    );
    public $validate = array(
        'my_list_id' => array(
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'You have already set price for this list.'
            )
        ),
        'dedicated_email_to_send' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'This field is required.'
            )
        ),
        'multi_event_to_send' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'This field is required.'
            )
        ),
        'ticket_offered_for_trade' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'This field is required.'
            )
        )
    );

}

?>