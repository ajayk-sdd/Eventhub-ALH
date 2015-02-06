<?php   
    class Category extends AppModel {
	public $hasAndBelongsToMany = array(
	    'Event' =>
		array(
		    'className' => 'Event',
		    'joinTable' => 'event_categories',
		    'foreignKey' => 'category_id',
		    'associationForeignKey' => 'event_id',
		    'unique' => true,
		)
	    );
}
?>