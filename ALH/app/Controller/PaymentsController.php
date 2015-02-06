<?php
  
class PaymentsController extends AppController {

    var $helpers = array('Html', 'Form', 'Cache');
    public $components = array('Cookie', 'Email', 'Paypal');
    public $paginate = array(
        'limit' => 10
    );

    /** @Created:     25-April-2014
     * @Method :     beforeFilter
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     Rendered before any function
     * @Param:       none
     * @Return:      none
     */
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index');
    }

    public function index() {
        
    }

    public function payForPackage($packageID = NULL) {
     
        $this->layout = 'front/home';
        $this->loadModel("Address");
        $this->loadModel("BillingInfo");
        $this->loadModel("Zip");
        $this->loadModel("Point");
        $this->loadModel("Package");
        $this->set('title_for_layout', 'ALIST Hub :: Card Details');

        $this->set("zip", $this->Zip->find("list", array("group" => array("Zip.state"), "fields" => array("Zip.state", "Zip.state_name"))));
        
        $package_detail = $this->Package->findById(base64_decode($packageID));
        $this->Set("package_detail",$package_detail);
        
        if (empty($packageID) && isset($this->data['cartIds'])) {
            $this->set('packageID', base64_encode($this->data['cartIds']));
            $this->set('package', $this->data);
        } else {
            $this->set('packageID', $packageID);
            $this->loadModel("Package");
            $this->set("package", $this->Package->findById(base64_decode($packageID)));
        }
        $this->set('userID', $_SESSION['Auth']['User']['id']);


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

        $points = $this->Point->find("first");
        $point_rate = $points["Point"]["price"];
        $this->set("point_rate", $point_rate);

        if (isset($this->data["amount"])) {
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
            // save payment if it is done from point
            if ($this->data['amount'] == 0) {
                $data["Payment"]["user_id"] = AuthComponent::user("id");
                $data["Payment"]["package_id"] = $this->data['package_id'];
                $data["Payment"]["point"] = $this->data['point_expence'];
                $data["Payment"]["from_point"] = 1;
                $data["Payment"]["first_name"] = $this->data['firstname'];
                $data["Payment"]["last_name"] = $this->data['lastname'];
                $data["Payment"]["billing_address_1"] = $this->data['billing_address_1'];
                $data["Payment"]["billing_address_2"] = $this->data['billing_address_2'];
                $data["Payment"]["city"] = $this->data['city'];
                $data["Payment"]["state"] = $this->data['state'];
                $data["Payment"]["zip"] = $this->data['zip'];
                $this->Payment->save($data);
                $payment_id = $this->Payment->getLastInsertID();
                $this->Session->write("payment_id", $payment_id);
            } else { 
                // save payment if it is done by paypal
                if (isset($this->data['card_id']) && !empty($this->data['card_id'])) {
                    $billing_info = $this->BillingInfo->findById($this->data['card_id']);
                    $this->request->data['creditCardNumber'] = $billing_info["BillingInfo"]["card_no"];
                    $this->request->data['expDateMonth'] = $billing_info["BillingInfo"]["exp_month"];
                    $this->request->data['expDateYear'] = $billing_info["BillingInfo"]["exp_year"];
                    $this->request->data['credit_card_type'] = $billing_info["BillingInfo"]["card_type"];
                }
                $payment = array(
                    'amount' => $this->data['amount'],
                    'card' => $this->data['creditCardNumber'], // This is a sandbox CC
                    'expiry' => array(
                        'M' => $this->data['expDateMonth'],
                        'Y' => $this->data['expDateYear'],
                    ),
                    'cvv' => $this->data['cvv2Number'],
                    'currency' => 'USD' // Defaults to GBP if not provided
                );

                try {
                    $payment = $this->Paypal->doDirectPayment($payment);
                   
                    if ($payment['ACK'] == 'Success') {
                        $this->loadModel['Payment'];
                        $data = $this->data;
                        if (strpos($data['package_id'], '-') !== false) {
                            $expData = explode("-", $data['package_id']);
                            for ($i = 0; $i < count($expData); $i++) {

                                $this->Payment->create();
                                $info['amount'] = $payment['AMT'];
                                $info['transaction_id'] = $payment['TRANSACTIONID'];
                                $info['credit_card_type'] = $data['credit_card_type'];
                                $info['user_id'] = $data['user_id'];
                                $info['first_name'] = $data['firstname'];
                                $info['last_name'] = $data['lastname'];
                                $info['billing_address_1'] = $data['billing_address_1'];
                                $info['billing_address_1'] = $data['billing_address_2'];
                                $info['city'] = $data['city'];
                                $info['state'] = $data['state'];
                                $info['zip'] = $data['zip'];
                                $info['package_id'] = $expData[$i];
                                $this->Payment->save($info);
                            }
                            $this->loadModel("Cart");
                            $this->Cart->deleteAll(array('Cart.user_id' => $info['user_id']));
                        } else {
                            $info['amount'] = $payment['AMT'];
                            $info['transaction_id'] = $payment['TRANSACTIONID'];
                            $info['credit_card_type'] = $data['credit_card_type'];
                            $info['user_id'] = $data['user_id'];
                            $info['first_name'] = $data['firstname'];
                            $info['last_name'] = $data['lastname'];
                            $info['billing_address_1'] = $data['billing_address_1'];
                            $info['billing_address_1'] = $data['billing_address_2'];
                            $info['city'] = $data['city'];
                            $info['state'] = $data['state'];
                            $info['zip'] = $data['zip'];
                            $info['package_id'] = $data['package_id'];
                            $this->Payment->save($info);
                        }
                        $payment_id = $this->Payment->getLastInsertID();
                        // payment mail
                        $this->orderConfirmationEmail($payment_id);
                        // now add points
                        $package_detail = $this->Package->findById($this->data["package_id"]);
                        if($package_detail["Package"]["buy_point_id"] != 0)
                        $this->addPoint($this->data["package_id"],$payment_id);
                        // now deduct point from user table
                        

                        //$this->Session->setFlash("Payment Successfull", "default", array("class" => "green"));
                    }
                } catch (Exception $e) {
                  
                    $error = $e->getMessage();
                    $this->Session->setFlash($error, "default", array("class" => "red"));
                    $this->redirect($this->referer());
                }
            }
            if (isset($this->data['point']) && $package_detail["Package"]["buy_point_id"] == 0) {
                $user_detail["User"]["id"] = AuthComponent::user("id");
                $user_detail["User"]["ALH_point"] = $this->data['point']; //pr($user_detail);die;
                $this->User->save($user_detail);
            }
            if ($this->Session->check("event_id")) {
                $this->Session->write("payment_id", $payment_id);
                $this->redirect(array("controller" => "Events", "action" => "shareEvent"));
            } else {
                $this->Session->write("payment_id", $payment_id);
                $this->redirect(array("controller" => "Payments", "action" => "thankYou"));
            }
        }
    }

    /** @Created:    1-Aug-2014
     * @Method :     addPoint
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Function for add points if user has purchase points
     * @Param:       $package_id,$payment_id
     * @Return:      true
     */
    
    public function addPoint($package_id,$payment_id){
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $this->loadModel("BuyPoint");
        $this->loadModel("Package");
        $this->loadModel("User");
        $this->loadModel("Payment");
        if($package_id){
            $package_detail = $this->Package->findById($package_id);
            if($package_detail["Package"]["buy_point_id"] != 0){
                $point_detail = $this->BuyPoint->findById($package_detail["Package"]["buy_point_id"]);
                
                // now add points
                $user = $this->User->find("first",array("conditions"=>array("User.id"=>AuthComponent::user("id")),"recursive"=>-1,"fields"=>array("User.ALH_point")));
                $user_detail["User"]["id"] = AuthComponent::user("id");
                $user_detail["User"]["ALH_point"] = $user["User"]["ALH_point"]+$point_detail["BuyPoint"]["no_of_point"];
                $this->User->save($user_detail);
               
                // now confirm payment done
                $payment_detail["Payment"]["id"] = $payment_id;
                $payment_detail["Payment"]["status"] = 1;
                $this->Payment->save($payment_detail);
                // send order confirmation mail
                $this->orderConfirmationEmail($payment_id);
            }
        }
        return TRUE;
    }

        /** @Created:    25-June-2014
     * @Method :     thankYou
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Function for the Thank You page
     * @Param:       none
     * @Return:      none
     */
    public function thankYou() {
        $this->layout = "front/home";
        if (!$this->Session->check("payment_id")) {
            $this->redirect(array("controller" => "Users", "action" => "index"));
        }
    }

    /** @Created:    13-June-2014
     * @Method :     customPackagePayment
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     Function for the payment of custom package
     * @Param:       none
     * @Return:      none
     */
    function customPackagePayment() {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $this->loadModel("Package");
        $this->loadModel("PackagesService");
        if ($this->data) {
            #Code Start to Calculate the total price
            $cnt = 0;
            $initial = 0;
            foreach ($this->data['Payment']['amount'] as $tot):
                if ($cnt == 0) {
                    $old = $tot;
                    $total = $initial + $old;
                } else {
                    $old = $total;
                    $total = $old + $tot;
                }
                $cnt++;
            endforeach;
            #Code End to Calculate the total price
            // make an custom package
            $package["Package"]["is_a_la_carte"] = 1;
            $package["Package"]["user_id"] = AuthComponent::user("id");
            $package["Package"]["name"] = AuthComponent::user("username") . "_custom_package_on_" . date("m-d-y");
            $package["Package"]["price"] = $total;
            $this->Package->save($package);
            $package_id = $this->Package->getLastInsertID();
            // code for save packages
            foreach ($this->data["CustomPackage"]["service_id"] as $service_id) {
                $packagesService["PackagesService"]["id"] = "";
                $packagesService["PackagesService"]["service_id"] = $service_id;
                $packagesService["PackagesService"]["package_id"] = $package_id;
                $this->PackagesService->save($packagesService);
            }
            $this->redirect(array("controller" => "Payments", "action" => "payForPackage", base64_encode($package_id)));
        }
    }

    /** @Created:    13-June-2014
     * @Method :     payForCustomPackage
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     Function for the payment for custom package
     * @Param:       none
     * @Return:      none
     */
    public function payForCustomPackage() {
        $this->layout = 'front/home';
        $this->set('title_for_layout', 'ALIST Hub :: Card Details');

        if ($this->data) {


            $payment = array(
                'amount' => $this->data['amount'],
                'card' => $this->data['creditCardNumber'], // This is a sandbox CC
                'expiry' => array(
                    'M' => $this->data['expDateMonth'],
                    'Y' => $this->data['expDateYear'],
                ),
                'cvv' => $this->data['cvv2Number'],
                'currency' => 'USD' // Defaults to GBP if not provided
            );

            try {
                $payment = $this->Paypal->doDirectPayment($payment);
                if ($payment['ACK'] == 'Success') {

                    $this->loadModel['Payment'];
                    $data = $this->data;
                    $info['amount'] = $payment['AMT'];
                    $info['transaction_id'] = $payment['TRANSACTIONID'];
                    $info['credit_card_type'] = $data['credit_card_type'];
                    $info['user_id'] = $data['user_id'];
                    $info['first_name'] = $data['firstname'];
                    $info['last_name'] = $data['lastname'];
                    $info['billing_address_1'] = $data['billing_address_1'];
                    $info['billing_address_1'] = $data['billing_address_2'];
                    $info['city'] = $data['city'];
                    $info['state'] = $data['state'];
                    $info['zip'] = $data['zip'];
                    $info['package_id'] = $data['package_id'];
                    $info['type'] = 1;
                    if ($this->Payment->save($info)) {
                        $this->loadModel("CustomPackage");
                        $payment_id = $this->Payment->getLastInsertID();
                        $exp = explode(",", $data['service_id']);
                        for ($i = 0; $i < count($exp); $i++) {
                            $this->CustomPackage->create();
                            $info['user_id'] = AuthComponent::user("id");
                            $info['payment_id'] = $payment_id;
                            $info['user_id'] = $data['package_id'];
                            $info['service_id'] = $exp[$i];
                            $this->CustomPackage->save($info);
                        }
                    }
                    $this->Session->setFlash("Successfully Package Purchased", "default", array("class" => "green"));
                    if ($this->Session->check("event_id")) {
                        $this->Session->write("payment_id", $payment_id);
                        $this->redirect(array("controller" => "Events", "action" => "shareEvent"));
                    } else {
                        $this->redirect(array("controller" => "Services", "action" => "alacartePromotionalService"));
                    }
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
                $this->Session->setFlash($error, "default", array("class" => "red"));
                $this->redirect(array("controller" => "Services", "action" => "alacartePromotionalService"));
            }
        }
    }

    /** @Created:    24-June-2014
     * @Method :     function payFromPoint
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Function for the payment from point
     * @Param:       $id
     * @Return:      none
     */
    public function payFromPoint($pid = NULL) {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        // decode parameters
        $id = base64_decode($pid);
      
        // load models
        $this->loadModel("User");
        $this->loadModel("Package");
        $this->loadModel("Point");
        //find user detaial
        $user_id = AuthComponent::user("id");
        $user_detail = $this->User->findById($user_id);
        // find package detail
        $package_detail = $this->Package->findById($id);
       
        // calculate package
        $point_rate = $this->Point->find("first");
        $point = $point_rate["Point"]["price"] * $package_detail["Package"]["price"];
        // check conditions
        if ($point > $user_detail["User"]["ALH_point"]) {
            $this->Session->setFlash("You do not have suficient points to buy this package", "default", array("class" => "red"));
            $this->redirect(array("controller" => "Payments", "action" => "payForPackage", base64_encode($id)));
        } else {
            // now buy from points
            $flag = "yes";
            $data["Payment"]["user_id"] = $user_id;
            $data["Payment"]["package_id"] = $id;
            $data["Payment"]["point"] = $point;
            $data["Payment"]["from_point"] = 1;
           
            if ($this->Payment->save($data)) {
                $payment_id = $this->Payment->getLastInsertID();
                $user_detail["User"]["ALH_point"] = $user_detail["User"]["ALH_point"] - $point;
                if ($this->User->save($user_detail)) {
                    
                }
            } else {
                $flag = "no";
            }
        }
        if ($flag == "yes") {
            $this->Session->setFlash("Payment successfully done.", "default", array("class" => "green"));
            if ($this->Session->check("event_id")) {
                $this->Session->write("payment_id", $payment_id);
                $this->redirect(array("controller" => "Events", "action" => "shareEvent"));
            } else {
                $this->redirect(array("controller" => "Users", "action" => "index"));
            }
        } else {
            $this->Session->setFlash("Something went wrong, please try again", "default", array("class" => "red"));
            $This->redirect(array("controller" => "Payments", "action" => "payForPackage", base64_encode($id)));
        }
    }

    /* @Created:     30-July-2014
     * @Method :     function payFromPoint
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Function for order confirmation 
     * @Param:       $id
     * @Return:      none
     */

    public function orderConfirmationEmail($payment_id) {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $this->loadModel("EmailTemplate");
        if ($payment_id) {
            $payment_detail = $this->Payment->findById($payment_id);
            if ($payment_detail) {
                if ($payment_detail["Payment"]["status"] == 1) {

                    // here is code for order completion
                    $emailTemp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.alias' => "order-completion")));
                    $emailContent = utf8_decode($emailTemp['EmailTemplate']['description']);
                    // here if for admin
                    $emailTempAdmin = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.alias' => "order-completion-admin")));
                    $emailContentAdmin = utf8_decode($emailTempAdmin['EmailTemplate']['description']);
                } else {
                    // here is code for order confirmation
                    $emailTemp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.alias' => "order-confirmation")));
                    $emailContent = utf8_decode($emailTemp['EmailTemplate']['description']);
                    // here is for admin
                    $emailTempAdmin = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.alias' => "order-confirmation-admin")));
                    $emailContentAdmin = utf8_decode($emailTempAdmin['EmailTemplate']['description']);
                }
                // send to user first
                $url = "http://".$_SERVER["HTTP_HOST"]."/Sales/orderList";
                $data = str_replace(array('{USER_NAME}', '{TRANSACTION_ID}', '{PACKAGE_NAME}', '{AMOUNT}','{ORDER_DATE}', '{URL}'), array($payment_detail['User']['username'], $payment_detail["Payment"]['transaction_id'], $payment_detail["Package"]["name"],$payment_detail["Payment"]["amount"],date("l, F d, Y",  strtotime($payment_detail["Payment"]["created"])),$url), $emailContent);
                $emailTemp['EmailTemplate']['subject'] = str_replace(array("{TRANSACTION_ID}"),array($payment_detail["Payment"]["transaction_id"]),$emailTemp['EmailTemplate']['subject']);
                $this->set('mailData', $data);
                $this->Email->to = $payment_detail["User"]["email"];
                $this->Email->subject = $emailTemp['EmailTemplate']['subject'];
                $this->Email->from = "admin@aisthub.com";
                $this->Email->template = "forgot";
                $this->Email->sendAs = 'html';
                $this->Email->send($data);
               
                // now send to admin
                $url = "http://".$_SERVER["HTTP_HOST"]."/admin/Sales/list";
                $dataAdmin = str_replace(array('{USER_NAME}', '{TRANSACTION_ID}', '{PACKAGE_NAME}', '{AMOUNT}','{ORDER_DATE}', '{URL}'), array($payment_detail['User']['username'], $payment_detail["Payment"]['transaction_id'], $payment_detail["Package"]["name"],$payment_detail["Payment"]["amount"],date("l, F d, Y",  strtotime($payment_detail["Payment"]["created"])),$url), $emailContentAdmin);
                $emailTempAdmin['EmailTemplate']['subject'] = str_replace(array("{TRANSACTION_ID}"),array($payment_detail["Payment"]["transaction_id"]),$emailTempAdmin['EmailTemplate']['subject']);
                $this->set('mailData', $dataAdmin);
                $this->Email->to = "admin@aisthub.com";
                $this->Email->subject = $emailTemp['EmailTemplate']['subject'];
                $this->Email->from = "admin@aisthub.com";
                $this->Email->template = "forgot";
                $this->Email->sendAs = 'html';
                $this->Email->send($dataAdmin);
                
            }
        }
        return 1;
    }
    
 
}

?> 