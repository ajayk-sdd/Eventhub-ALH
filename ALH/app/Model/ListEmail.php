<?php   
    class ListEmail extends AppModel {
	var $belongsTo =
	    array(
		
                'MyList' => array(
		    'className'    => 'MyList',
		    'foreignKey' => 'my_list_id',
                    'dependent' => true
		)
	);
}
?>