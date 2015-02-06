<?php   
    class Vibe extends AppModel {
	public $hasAndBelongsToMany = array(
	    'Event' =>
		array(
		    'className' => 'Event',
		    'joinTable' => 'event_vibes',
		    'foreignKey' => 'vibe_id',
		    'associationForeignKey' => 'event_id',
		    'unique' => true,
		)
	    );
}
?>