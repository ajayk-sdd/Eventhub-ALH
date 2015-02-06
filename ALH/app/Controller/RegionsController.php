<?php
App::uses('AppController', 'Controller');
class RegionsController extends AppController
{
	public $name = 'Regions';
	var $components = array();
	public $uses = array();
	
	/** @Created:     30-April-2014
	* @Method :     beforeFilter
	* @Author:      Sachin Thakur
	* @Modified :   ---
	* @Purpose:     Rendered before any function
	* @Param:       none
	* @Return:      none
	*/
	function beforeFilter() {
		parent::beforeFilter();
	    //$this->Auth->allow();
	}
	
	/** @Created:   02-May-2014
	* @Method :     admin_list
	* @Author:      Sachin Thakur
	* @Modified :   ---
	* @Purpose:     Listing of Regions
	* @Param:       none
	* @Return:      none
	*/
	function admin_list() {
		$this->layout = 'admin/admin';
		$this->set('title_for_layout', 'ALIST Hub :: Regions Listing');
		$this->loadModel('Region');
		$this->loadModel('Event');
		$this->paginate = array('conditions' => "", 'limit' => 10, 'order' => array('Region.id' => 'DESC'));
		$regionsList = $this->paginate('Region');
		$this->set('regionsList',$regionsList);
		
	}
	/** @Created:   02-May-2014
	* @Method :     admin_add
	* @Author:      Sachin Thakur
	* @Modified :   ---
	* @Purpose:     Add Regions 
	* @Param:       $id (Region Id)
	* @Return:      none
	*/
	function admin_add($id = NULL) {
		$this->layout = 'admin/admin';
		$this->set('title_for_layout', 'ALIST Hub :: Add Regions');
		$this->loadModel('Region');
		if ($this->data) {
		if(empty($this->data['Region']["id"]))
                {
                    $upMsg = "Added Successfully"; 
                }
                else
                {
                    $upMsg = "Updated Successfully";
                }
			if ($this->Region->save($this->data['Region'])) {
			    $this->Session->setFlash($upMsg, 'default', array('class' => 'green'));
			    $this->redirect(array('controller' => 'Regions', 'action' => 'list'));
			}
		}
		if ($id) {
		    $id = base64_decode($id);
		    $this->request->data = $this->Region->findById($id);
		}
		
	}
	
}
?>