<?php

App::uses('AuthComponent', 'Controller/Component');

class Giveaway extends AppModel {

    var $belongsTo = array(
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'event_id'
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        )
    );
    public $validate = array(
        'event_id' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'Event is required.'
            )
        )
        //,
        //'no_of_ticket' => array(
        //    'required' => array(
        //        'rule' => 'notEmpty',
        //        'message' => 'No Of Ticket is required.'
        //    )
        //)
    );

}

?>