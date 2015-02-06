<?php
App::uses('AppController', 'Controller');
class CategoriesController extends AppController
{
	public $name = 'Categories';
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
	* @Method :     admin_listVibes
	* @Author:      Sachin Thakur
	* @Modified :   ---
	* @Purpose:     Listing of Vibes
	* @Param:       none
	* @Return:      none
	*/
	function admin_listVibes() {
		$this->layout = 'admin/admin';
		$this->set('title_for_layout', 'ALIST Hub :: Vibes Listing');
		$this->loadModel('Vibe');
		$this->loadModel('EventVibe');
		$this->paginate = array('conditions' => "", 'limit' => 10, 'order' => array('Vibe.id' => 'DESC'));
		$vibesList = $this->paginate('Vibe');
		$this->set('vibesList',$vibesList);
		
		
	}
	/** @Created:   02-May-2014
	* @Method :     admin_addVibes
	* @Author:      Sachin Thakur
	* @Modified :   ---
	* @Purpose:     Add Vibes
	* @Param:       $id (Vibe Id)
	* @Return:      none
	*/
	function admin_addVibes($id = NULL) {
		$this->layout = 'admin/admin';
		$this->set('title_for_layout', 'ALIST Hub :: Add Vibes');
		$this->loadModel('Vibe');
		if ($this->data) {
			if ($this->Vibe->save($this->data['Vibe'])) {
			    $this->Session->setFlash('Vibe Updated', 'default', array('class' => 'green'));
			    $this->redirect(array('controller' => 'Categories', 'action' => 'listVibes'));
			}
		}
		if ($id) {
		    $id = base64_decode($id);
		    $this->request->data = $this->Vibe->findById($id);
		}
		
	}
	/** @Created:   02-May-2014
	* @Method :     admin_list
	* @Author:      Sachin Thakur
	* @Modified :   ---
	* @Purpose:     Listing of Categories
	* @Param:       none
	* @Return:      none
	*/
	function admin_list() {
		$this->layout = 'admin/admin';
		$this->set('title_for_layout', 'ALIST Hub :: Categories Listing');
		$this->loadModel('Category');
		$this->paginate = array('conditions' => "", 'limit' => 10, 'order' => array('Category.id' => 'DESC'));
		$categoriesList = $this->paginate('Category');
		
		$this->set('categoriesList',$categoriesList);
		
	}
	/** @Created:   02-May-2014
	* @Method :     admin_add
	* @Author:      Sachin Thakur
	* @Modified :   ---
	* @Purpose:     Add Categories 
	* @Param:       $id (Category Id)
	* @Return:      none
	*/
	function admin_add($id = NULL) {
		$this->layout = 'admin/admin';
		$this->set('title_for_layout', 'ALIST Hub :: Add Categories');
		$this->loadModel('Category');
		if ($this->data) {
			if ($this->Category->save($this->data['Category'])) {
			    $this->Session->setFlash('Category Updated', 'default', array('class' => 'green'));
			    $this->redirect(array('controller' => 'Categories', 'action' => 'list'));
			}
		}
		if ($id) {
		    $id = base64_decode($id);
		    $this->request->data = $this->Category->findById($id);
		}
		
	}
	
}
?>