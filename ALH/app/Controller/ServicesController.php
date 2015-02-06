<?php

class ServicesController extends AppController {

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
        //$this->Auth->allow('alacartePromotionalService');
    }
    /** @Created:    12-May-2014
     * @Method :     admin_add
     * @Author:      add
     * @Modified :   ---
     * @Purpose:     Add Services
     * @Param:       none
     * @Return:      none
     */
    function admin_add($id = NULL) {
       $this->layout = 'admin/admin';
       $this->set('title_for_layout', 'ALIST Hub :: Add Services');
       if(isset($this->data) && !empty($this->data)){
         if(empty($this->data['Service']["id"]))
                {
                    $upMsg = "Added Successfully"; 
                }
                else
                {
                    $upMsg = "Updated Successfully";
                }
            $this->Service->save($this->data['Service']);
            $this->Session->setFlash($upMsg, "default", array("class" => "green"));
            $this->redirect(array("controller" => "Services", "action" => "list"));
       }
       if ($id) {
            $id = base64_decode($id);
            $this->request->data = $this->Service->findById($id);
        }
    }
    /** @Created:    12-May-2014
     * @Method :     admin_list
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     Services Listing
     * @Param:       none
     * @Return:      none
     */
    function admin_list() {
       $this->layout = 'admin/admin';
       $this->set('title_for_layout', 'ALIST Hub :: Services');
       $this->paginate = array('limit' => 10, 'order' => array('Service.id' => 'DESC'));
       $this->set('services', $this->paginate('Service'));
    }
    /** @Created:    13-May-2014
    * @Method :     admin_view
    * @Author:      Sachin Thakur
    * @Modified :   ---
    * @Purpose:     View Details of particular service
    * @Param:       $serviceId
    * @Return:      none
     */
    public function admin_view($serviceId = NULL) {
        $this->layout = 'admin/admin';
        $this->set('title_for_layout', 'ALIST Hub :: Service Detail');
        if ($serviceId) {
            $serviceId = base64_decode($serviceId);
            $this->set('service', $this->Service->findById($serviceId));
        } else {
            $this->Session->setFlash('Cannot access directly', 'default', array('class' => 'red'));

            $this->redirect(array('contoller' => 'Services', 'action' => 'list'));
        }
    }
     /** @Created:    14-May-2014
    * @Method :     alacartePromotionalService
    * @Author:      Sachin Thakur
    * @Modified :   ---
    * @Purpose:     Function to select the Ala Carte Promotional Service
    * @Param:       none
    * @Return:      none
     */
    public function alacartePromotionalService() {
        $this->layout = 'front/home';
        $this->set('title_for_layout', 'ALIST Hub :: Ala Carte Promotional Service');
        $services = $this->Service->find('all',array('condition'=>array(),'fields'=>array('id','name','description','price')));
        $this->set(compact('services'));
    }
    
     /** @Created:    03-June-2014
    * @Method :     promotionalPackages
    * @Author:      Sachin Thakur
    * @Modified :   ---
    * @Purpose:     Function to select (Purchase) a promotional package
    * @Param:       none
    * @Return:      none
     */
    function promotionalPackages(){
        //pr($_SESSION);die;
        $this->layout = 'front/home';
        $this->set('title_for_layout', 'ALIST Hub :: Promotional Packages');
        $this->loadModel('Package');
        $packageData = $this->Package->find('all',array('conditions'=>array("Package.buy_point_id"=>0,"OR"=>array("Package.is_a_la_carte"=>0,"Package.user_id"=>  AuthComponent::user("id")))));
        $this->set('packageData',$packageData);
        if($this->Session->check("event_id")){
            $this->loadModel("Event");
            $event_name = $this->Event->find("first",array("conditions"=>array("Event.id"=>$this->Session->read("event_id")),"recursive"=>-1,"fields"=>array("Event.title")));
            $this->set("event_name",$event_name["Event"]["title"]);
        } else{
            $this->set("event_name","");
        }
    }
    
    
       /** @Created:    17-June-2014
    * @Method :     exchangeMarket
    * @Author:      Sachin Thakur
    * @Modified :   ---
    * @Purpose:     For Exchange market
    * @Param:       none
    * @Return:      none
     */
    function exchangeMarket(){
        $this->layout = 'front/home';
        $this->set('title_for_layout', 'ALIST Hub :: Exchange Marketplace');
        $this->loadModel('Package');
        $packageData = $this->Package->find('all',array('conditions'=>array("OR"=>array("Package.is_a_la_carte"=>0,"Package.user_id"=>  AuthComponent::user("id")))));
        $this->set('packageData',$packageData);
        
    }
       /** @Created:    17-June-2014
    * @Method :     addToCart
    * @Author:      Sachin Thakur
    * @Modified :   ---
    * @Purpose:     Function for package add into the add to cart table
    * @Param:       $pId = Package Id,$price = Package Price
    * @Return:      none
     */
    function addToCart($pId = NULL,$price = NULL){
        $this->layout = "ajax";
        $this->autoRender = false;
        if(!empty($pId)){
            $this->loadModel('Cart');
            $tmp['Cart']['package_id'] = $pId;
            $tmp['Cart']['user_id'] = $this->Auth->User('id');
            $tmp['Cart']['price'] = $price;
            $cnt = $this->Cart->find("count",array("conditions"=>array("Cart.package_id"=> $tmp['Cart']['package_id'],"Cart.user_id"=>$tmp['Cart']['user_id'])));
            if($cnt==0){
                if($this->Cart->save($tmp['Cart']))
                {
                    return 0;
                    exit;
                }else{
                    return 1;
                    exit;
                }
            }
            else{
                return 2;
                exit;
            }
        }
        
    }
    
}

?> 