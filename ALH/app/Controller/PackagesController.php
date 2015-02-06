<?php

class PackagesController extends AppController {

    var $helpers = array('Html', 'Form', 'Cache');
    public $components = array();
    public $paginate = array(
        'limit' => 10
    );

    /** @Created:    12-May-2014
     * @Method :     beforeFilter
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     Rendered before any function
     * @Param:       none
     * @Return:      none
     */
    function beforeFilter() {
        parent::beforeFilter();
        //$this->Auth->allow('createEvent','reviewEvent');
    }

    /** @Created:    12-May-2014
     * @Method :     admin_add
     * @Author:      add
     * @Modified :   ---
     * @Purpose:     Add Packages
     * @Param:       none
     * @Return:      none
     */
    function admin_add($id = NULL) {
        $this->layout = 'admin/admin';
        $this->set('title_for_layout', 'ALIST Hub :: Add Packages');
        $this->loadModel('PackagesService');
        $this->loadModel('Service');
        $this->set("services", $this->Package->Service->find("list"));
        $id = base64_decode($id);
        if (isset($this->data) && !empty($this->data)) {
            if(empty($this->data['Package']["id"]))
                {
                    $upMsg = "Added Successfully"; 
                }
                else
                {
                    $upMsg = "Updated Successfully";
                }
            $this->Package->saveAll($this->request->data);
            $this->Session->setFlash($upMsg, "default", array("class" => "green"));
            $this->redirect(array("controller" => "Packages", "action" => "list"));
        }
        if ($id) {
            $this->request->data = $this->Package->findById($id);
        }
    }

    /** @Created:    12-May-2014
     * @Method :     admin_list
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     Packages Listing
     * @Param:       none
     * @Return:      none
     */
    function admin_list() {
        $this->layout = 'admin/admin';
        $this->set('title_for_layout', 'ALIST Hub :: Packages');
        $this->paginate = array('limit' => 10, 'order' => array('Package.id' => 'DESC'));
        $this->set('packages', $this->paginate('Package'));
    }

    /** @Created:    13-May-2014
     * @Method :     admin_view
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     View Details of particular service
     * @Param:       $packageId
     * @Return:      none
     */
    public function admin_view($packageId = NULL) {
        $this->layout = 'admin/admin';
        $this->set('title_for_layout', 'ALIST Hub :: Package Detail');
        if ($packageId) {
            $packageId = base64_decode($packageId);
            $this->set('package', $this->Package->findById($packageId));
        } else {
            $this->Session->setFlash('Cannot access directly', 'default', array('class' => 'red'));

            $this->redirect(array('contoller' => 'Packages', 'action' => 'list'));
        }
    }

    /** @Created:    23-July-2014
     * @Method :     admin_virtualCurrencyPackages
     * @Author:      Sachin Thakur
     * @Modified :   Prateek Jadhav(24-july-2014)
     * @Purpose:     list Packages for virtual Currency
     * @Param:       none
     * @Return:      none
     */
    public function admin_virtualCurrencyPackages() {
        $this->layout = 'admin/admin';
        $this->set('title_for_layout', 'ALIST Hub :: Virtual Currency Packages');
        $this->loadModel("VirtualCurrency");
        $this->paginate = array('limit' => 10, 'order' => array('VirtualCurrency.id' => 'ASC'));
        $datas = $this->paginate('VirtualCurrency');
        $this->set('datas', $datas);
        $this->loadModel("VirtualCurrencyPackage");
        
    }
    
     /** @Created:    24-July-2014
     * @Method :     admin_addVirtualCurrency
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     View Packages for virtual Currency
     * @Param:       $id/none
     * @Return:      none
     */
    
    public function admin_addVirtualCurrency($id = NULL){
        $this->layout = 'admin/admin';
        $this->set('title_for_layout', 'ALIST Hub :: Virtual Currency Packages');
        $this->loadModel("VirtualCurrency");
        if($this->data){
             if(empty($this->data['VirtualCurrency']["id"]))
                {
                    $upMsg = "Added Successfully"; 
                }
                else
                {
                    $upMsg = "Updated Successfully";
                }
            if($this->VirtualCurrency->save($this->data)){
                $this->Session->setFlash($upMsg,"default",array("class"=>"green"));
                $this->redirect(array("controller"=>"Packages","action"=>"virtualCurrencyPackages"));
            } else {
                $this->Session->setFlash("Unable to update Virtual Currency","default",array("class"=>"red"));
            }
        }
        if($id){
            $id = base64_decode($id);
            $data = $this->VirtualCurrency->findById($id);
            if(!empty($data)){
                $this->request->data = $data;
            } else {
                $this->redirect(array("controller"=>"Packages","action"=>"virtualCurrencyPackages"));
            }
        }
    }
    
     /** @Created:    24-July-2014
     * @Method :     point
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     View points for purchase
     * @Param:       none
     * @Return:      none
     */
    
    public function point(){
        $this->layout = "front/home";
        $this->loadModel("VirtualCurrency");
        $datas = $this->VirtualCurrency->find("all",array("conditions"=>array("VirtualCurrency.status"=>1),"order"=>array("VirtualCurrency.points ASC")));
        $this->set("datas",$datas);        
    }

}

?> 