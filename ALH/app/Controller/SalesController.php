<?php

class SalesController extends AppController {

    var $helpers = array('Html', 'Form', 'Cache');
    public $components = array('Email');
    public $paginate = array(
        'limit' => 10
    );

    /** @Created:    05-June-2014
     * @Method :     beforeFilter
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     Rendered before any function
     * @Param:       none
     * @Return:      none
     */
    function beforeFilter() {
        parent::beforeFilter();
        //  $this->Auth->allow('createEvent', 'reviewEvent');
    }

    /** @Created:    05-June-2014
     * @Method :     admin_list
     * @Author:      Sachin Thakur
     * @Modified :   15-July-2014(Prateek Jadhav)
     * @Purpose:     for made amendments
     * @Param:       none
     * @Return:      none
     */
    function admin_list() {
        $this->layout = 'admin/admin';
        $this->set('title_for_layout', 'ALIST Hub :: Sales Listing');
        $this->loadModel('Payment');
        $fields = array("Payment.transaction_id", "Payment.status", "Payment.id", "Payment.amount", "Package.*", "User.first_name", "User.last_name", "User.username", "User.id", "User.email");
        //  $reports= $this->SaleReport->find('all',array('contain'=>array('Sale'=>array('User','conditions'=>array('Sale.vendor'=>'amazon')))));

        /*   $this->Payment->contain('Package.id');
          $this->Package->contain('Service.id'); */
        $this->paginate = array('conditions' => array(), 'limit' => 10, 'order' => array('Payment.id' => 'DESC'), "recursive" => 2);
        $sales = $this->paginate('Payment');
        $this->set('sales', $sales);
    }

    /** @Created:    11-June-2014
     * @Method :     order_list
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     
     * @Param:       none
     * @Return:      none
     */
    function orderList() {
        $this->layout = 'front/home';
        $this->loadModel('User');
        $user = $this->User->findById(AuthComponent::User("id"));

        $this->set('title_for_layout', 'ALIST Hub :: My Order Listing');
        $this->loadModel('Payment');
        $this->paginate = array('conditions' => array('Payment.user_id' => $user['User']['id']), 'limit' => 10, 'order' => array('Payment.id' => 'DESC'));
        $this->paginate('Payment');
        $this->set('sales', $this->paginate('Payment'));
    }

    /** @Created:    15-July-2014
     * @Method :     admin_view
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     
     * @Param:       $payment_id
     * @Return:      none
     */
    public function admin_view($payment_id = NULL) {
        $this->layout = "admin/admin";
        $this->set('title_for_layout', 'ALIST Hub :: View Sale');
        $this->loadModel("Payment");
        if ($payment_id) {
            $payment_id = base64_decode($payment_id);
            $detail = $this->Payment->find("first", array("conditions" => array("Payment.id" => $payment_id), "recursive" => 2));
            $this->set("detail",$detail);
        } else {
            $this->redirect(array("controller" => "User", "action" => "index"));
        }
    }
     /** @Created:    30-July-2014
     * @Method :     invoice
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     
     * @Param:       $payment_id
     * @Return:      none
     */
    
    public function invoice($payment_id = NULL){
        $this->layout = FALSE;
        $this->loadModel("Payment");
        if ($payment_id){
            $payment_id = base64_decode($payment_id);
            $data = $this->Payment->find("first",array("conditions"=>array("Payment.id"=>$payment_id)));
            $this->set("data",$data);
        } else {
            $this->set('message',"No data found");
        }
    }
    

}

?>