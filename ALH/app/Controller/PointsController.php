<?php

class PointsController extends AppController {

    var $helpers = array('Html', 'Form', 'Cache');
    public $components = array('Cookie', 'Email');
    public $paginate = array(
        'limit' => 10
    );

    /** @Created:     23-Jun-2014
     * @Method :     function beforeFilter
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Rendered before any function
     * @Param:       none
     * @Return:      none
     */
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('postEventsOnFacebook');
    }

    /** @Created:     23-Jun-2014
     * @Method :     function admin_setPoint
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Manage point price
     * @Param:       none
     * @Return:      none
     */
    public function admin_setPoint() {
        $this->layout = "admin/admin";
        $this->set('title_for_layout', 'ALIST Hub :: Set Point');
        $this->set("point", $this->Point->find("first"));
    }

    /** @Created:     23-Jun-2014
     * @Method :     function admin_changePrice
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     change point price
     * @Param:       price
     * @Return:      0/1
     */
    public function admin_changePrice($price = NULL) {
        $this->layout = FALSE;
        $this->set('title_for_layout', 'ALIST Hub :: Change Point Price');
        $this->autoRender = FALSE;
        if ($price) {
            $data["Point"]["id"] = 1;
            $data["Point"]["price"] = $price;
            if ($this->Point->save($data)) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    /* @Created:     31-July-2014
     * @Method :     function admin_list
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     To list points rate
     * @Param:       none
     * @Return:      none
     */

    public function admin_list() {
        $this->layout = "admin/admin";
        $this->set('title_for_layout', 'ALIST Hub :: Points List');
        $this->loadModel("BuyPoint");
        $datas = $this->paginate('BuyPoint');
        $this->set("datas", $datas);
    }
    
    
    /* @Created:     31-July-2014
     * @Method :     function admin_add
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     To list points rate
     * @Param:       $buy_point_id/none
     * @Return:      none
     */
    
    public function admin_add($buy_point_id = NULL){
        $this->layout = "admin/admin";
        $this->set('title_for_layout', 'ALIST Hub :: Add Points');
        $this->loadModel("BuyPoint");
        if($this->data){
            if($this->BuyPoint->save($this->data)){
                 if(empty($this->data['BuyPoint']["id"]))
                {
                    $upMsg = "Added Successfully"; 
                }
                else
                {
                    $upMsg = "Updated Successfully";
                }
                $this->Session->setFlash($upMsg,"default",array("class"=>"green"));
                $this->redirect(array("controller"=>"Points","action"=>"list"));
            } else {
                $this->Session->setFlash("Points could not be saved, try again","default",array("class"=>"red"));
            }
        }
        if($buy_point_id){
            $buy_point_id = base64_decode($buy_point_id);
            $this->request->data = $this->BuyPoint->findById($buy_point_id);
        }
    }
    
     /*@Created:     1-Aug-2014
     * @Method :     function getPointPrice
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Function for get Point Price by id
     * @Param:       buyPoint_id
     * @Return:      0/$price
     */
    
    public function getPointPrice($buyPoint_id = NULL){
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $this->loadModel("BuyPoint");
        if($buyPoint_id){
            $detail = $this->BuyPoint->findById($buyPoint_id);
            if(!empty($detail)){
                $price = $detail["BuyPoint"]["price"];
                return $price;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
    
     /*@Created:     1-Aug-2014
     * @Method :     function buyNow
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Function for create package for buy no of points
     * @Param:       none
     * @Return:      none
     */
    
    public function buyNow(){
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $this->loadModel("BuyPoint");
        $this->loadModel("Package");
        if($this->data){
            $point_detail = $this->BuyPoint->findById($this->data["BuyPoint"]["id"]);
            if($point_detail){
                $package["Package"]["id"] = "";
                $package["Package"]["user_id"] = AuthComponent::user("id");
                $package["Package"]["buy_point_id"] = $point_detail["BuyPoint"]["id"];
                $package["Package"]["name"] = $point_detail["BuyPoint"]["no_of_point"]." Points";
                $package["Package"]["price"] = $point_detail["BuyPoint"]["price"];
                $package["Package"]["description"] = $point_detail["BuyPoint"]["no_of_point"]." Points in $".$point_detail["BuyPoint"]["price"];
                
                if($this->Package->save($package)){
                    $package_id = $this->Package->getLastInsertID();
                    $this->redirect(array("controller"=>"Payments","action"=>"payForPackage/".base64_encode($package_id)));
                }
                
                
            } else {
                $this->redirect(array("controller"=>"Users","action"=>"viewProfile"));
            }
        } else {
            $this->redirect(array("controller"=>"Users","action"=>"viewProfile"));
        }
    }
    
          /*@Created:     1-Sep-2014
     * @Method :     function cashOut
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     Function for get user info for cashout Points
     * @Param:       $point_price
     * @Return:      0/$price
     */
    
    public function cashOut($point_price = NULL){
        $this->layout = "front/home";
        $this->loadModel("Address");
        $this->loadModel("BillingInfo");
        $this->loadModel("Zip");
        $this->loadModel("Point");
        $this->loadModel("CashOutPoint");
        $this->set('title_for_layout', 'ALIST Hub :: CashOut Points');

        $this->set("zip", $this->Zip->find("list", array("group" => array("Zip.state"), "fields" => array("Zip.state", "Zip.state_name"))));
       
        $address = $this->Address->find("list", array("conditions" => array("Address.user_id" => AuthComponent::user("id")), "fields" => array("Address.id", "Address.name")));
        $this->set("address", $address);

        $billing_info = $this->BillingInfo->find("all", array("conditions" => array("BillingInfo.user_id" => AuthComponent::user("id")), "recursive" => "-1", "fields" => array("BillingInfo.id", "BillingInfo.card_type", "BillingInfo.card_no")));
        if (!empty($billing_info)) {
            foreach ($billing_info as $bi) {
                $bil_info[$bi["BillingInfo"]["id"]] = $bi["BillingInfo"]["card_type"] . " ending with " . substr($bi["BillingInfo"]["card_no"], -4);
            }
        } else {
            $bil_info = "";
        }
        $this->set("bil_info", $bil_info);
        
         $point_detail = $this->Point->findById('1');
        
            if(!empty($point_detail)){
               $price = $point_detail["Point"]["price"];
               $points = base64_decode($point_price)/$price;
               $this->set("points", $points);
            }
        
        $this->set("point_price", base64_decode($point_price));
      
        if (isset($this->data["Point"]["price"]) && !empty($this->data["Point"]["price"])) {
          
            // save address detail
            if (isset($this->data['address_id']) && !empty($this->data['address_id'])) {
                $address = $this->Address->findById($this->data['address_id']);
                $this->request->data['firstname'] = $address["Address"]["first_name"];
                $this->request->data['lastname'] = $address["Address"]["last_name"];
                $this->request->data['billing_address_1'] = $address["Address"]["billing_address_1"];
                $this->request->data['billing_address_2'] = $address["Address"]["billing_address_2"];
                $this->request->data['city'] = $address["Address"]["city"];
                $this->request->data['state'] = $address["Address"]["state"];
                $this->request->data['zip'] = $address["Address"]["zip"];
            } else {
                $address["Address"]["user_id"] = AuthComponent::user('id');
                $address["Address"]["name"] = $this->data['add_name'];
                $address["Address"]["first_name"] = $this->data['firstname'];
                $address["Address"]["last_name"] = $this->data['lastname'];
                $address["Address"]["billing_address_1"] = $this->data['billing_address_1'];
                $address["Address"]["billing_address_2"] = $this->data['billing_address_2'];
                $address["Address"]["city"] = $this->data['city'];
                $address["Address"]["state"] = $this->data['state'];
                $address["Address"]["zip"] = $this->data['zip'];
                $this->Address->save($address);
            }   
                $cashoutpoint["CashOutPoint"]["id"] = '';
                $cashoutpoint["CashOutPoint"]["user_id"] = AuthComponent::user('id');
                $cashoutpoint["CashOutPoint"]["price"] = $this->data["Point"]["price"];
                $cashoutpoint["CashOutPoint"]["point"] = $this->data["Point"]["point"];
                $cashoutpoint["CashOutPoint"]["bank_user_fname"] = $this->data['bank_firstname'];
                $cashoutpoint["CashOutPoint"]["bank_user_lname"] = $this->data['bank_lastname'];
                $cashoutpoint["CashOutPoint"]["bank_name"] = $this->data['bank_name'];
                $cashoutpoint["CashOutPoint"]["account_no"] = $this->data['account_number'];
                $cashoutpoint["CashOutPoint"]["ifsc_code"] = $this->data['ifsc_code'];
                $cashoutpoint["CashOutPoint"]["pay_mode"] = $this->data['mode'];
                $cashoutpoint["CashOutPoint"]["address_fname"] = $this->data['firstname'];
                $cashoutpoint["CashOutPoint"]["address_lname"] = $this->data['lastname'];
                $cashoutpoint["CashOutPoint"]["address1"] = $this->data['billing_address_1'];
                $cashoutpoint["CashOutPoint"]["address2"] = $this->data['billing_address_2'];
                $cashoutpoint["CashOutPoint"]["city"] = $this->data['city'];
                $cashoutpoint["CashOutPoint"]["state"] = $this->data['state'];
                $cashoutpoint["CashOutPoint"]["zip_code"] = $this->data['zip'];
                $cashoutpoint["CashOutPoint"]["country"] = $this->data['country'];
                $cashoutpoint["CashOutPoint"]["date"] = date("Y-m-d");
                $cashoutpoint["CashOutPoint"]["status"] = "In Progress";
               // echo "<pre>";
               // print_r($cashoutpoint); die;
                 if($this->CashOutPoint->save($cashoutpoint))
                 { 
                     $usr["User"]["id"] = AuthComponent::user('id');
                     $usr["User"]["ALH_point"] = '0';
                     $this->User->save($usr);
                     
                     $this->redirect(array("controller"=>"points","action"=>"cashoutThanks"));
                 }
        }
      
    }
    
       /*@Created:     2-Sep-2014
     * @Method :     function cashoutThanks
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     Function for thankyou message for cashout process
     * @Param:       
     * @Return:      0/$price
     */
    
    public function cashoutThanks(){
         $this->layout = "front/home";
         $this->set('title_for_layout', 'ALIST Hub :: CashOut Points Process Completed.');
    
    }
    
       /*@Created:     4-Sep-2014
     * @Method :     function cashoutList
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     Function for cashout process requesting list
     * @Param:       
     * @Return:      0/$price
     */
    
    public function admin_cashoutList(){
         $this->layout = "admin/admin";
         $this->set('title_for_layout', 'ALIST Hub :: CashOut Points');
         $this->loadModel("CashOutPoint");
         $datas = $this->paginate('CashOutPoint');
         $this->set("datas", $datas);
    
    }
    
      /*@Created:     4-Sep-2014
     * @Method :     function editRequest
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     Function for edit cashout request
     * @Param:       
     * @Return:      0/$price
     */
    
    public function admin_editRequest($id = NULL){
         $this->layout = "admin/admin";
         $this->set('title_for_layout', 'ALIST Hub :: Edit Request');
         $this->loadModel("CashOutPoint");
         
        if($this->data){
            if($this->CashOutPoint->save($this->data)){
                $this->Session->setFlash("Changes has been saved on cashout request","default",array("class"=>"green"));
                $this->redirect(array("controller"=>"Points","action"=>"cashoutList"));
            } else {
                $this->Session->setFlash("Changes could not be saved, try again","default",array("class"=>"red"));
            }
        }
        if($id){
            $id = base64_decode($id);
            $this->request->data = $this->CashOutPoint->findById($id);
        }
    
    }
}

?> 