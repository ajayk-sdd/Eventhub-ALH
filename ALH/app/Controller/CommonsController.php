<?php

App::uses('AppController', 'Controller');

class CommonsController extends AppController {

    public $name = 'Commons';
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
    }

    /**
     * @Created:     30-April-2014
     * @Method :     admin_changestatus
     * @Author: 	Sachin Thakur
     * @Modified :	
     * @Purpose:   	Function for changing status of cms 
     * @Param:     	$id,$status,$model
     * @Return:    	none
     */
    function admin_changestatus($id = NULL, $status = NULL, $model = NULL) {
        $this->layout = "ajax";
        $this->autoRender = false;
        $this->loadModel($model);
        $id = base64_decode($id);

        $this->$model->id = $id;
        if ($model == "Payment") {
            $this->requestAction('/Payments/orderConfirmationEmail', array('pass' => array($id)));
        }
        if ($model == "MakeOffer") {
            $this->requestAction('/MyLists/sendStatusMail', array('pass' => array($id)));
        }
        if ($status == 0) {
            $this->$model->saveField('status', 1);
            echo 1;
        } else {
            $this->$model->saveField('status', 0);
            echo 0;
        }
    }

    /** @Created:    29-July-2014
     * @Method :     admin_changefeature
     * @Author:      Prateek Jadhav
     * @Modified :   
     * @Purpose:     Change feature event status
     * @Param:       none
     * @Return:      none
     */
    function admin_changefeature($id = NULL, $status = NULL, $model = NULL) {
        $this->layout = "ajax";
        $this->autoRender = false;
        $this->loadModel($model);
        $id = base64_decode($id);

        $this->$model->id = $id;

        if ($status == 0) {
            $this->$model->saveField('is_feature', 1);
            echo 1;
        } else {
            $this->$model->saveField('is_feature', 0);
            echo 0;
        }
    }

    public function admin_selectMultiple() {
        $this->layout = false;
        $this->autoRender = false;
        if ($this->data) {
            $model = $this->params->query['model'];
            $this->loadModel($model);
            if (isset($this->data['activate'])) {
                $this->$model->updateAll(array($model . '.status' => 1), array($model . '.id' => $this->data['IDs']));
                $this->Session->setFlash('Selected status has been activated.', 'default', array('class' => 'green'));
            } else if (isset($this->data['deactivate'])) {
                $this->$model->updateAll(array($model . '.status' => 0), array($model . '.id' => $this->data['IDs']));
                $this->Session->setFlash('Selected status has been deactivated.', 'default', array('class' => 'green'));
            } else if (isset($this->data['delete'])) {
                $this->$model->deleteAll(array($model . '.id' => $this->data['IDs']));
                $this->Session->setFlash('Deleted Successfully.', 'default', array('class' => 'green'));
            }
        }
        $this->redirect($this->referer());
    }

    /** @Created:    29-April-2014
     * @Method :     admin_delete
     * @Author:      Sachin Thakur
     * @Modified :  
     * @Purpose:     Common Delete Function
     * @Param:       $id,$model
     * @Return:      none
     */
    public function admin_Delete($id = NULL, $model = NULL) {
        $this->layout = false;
        $this->autoRender = false;
        $this->loadModel($model);
        $id = base64_decode($id);
        //echo $id.'=='.$model;die;
        if ($this->$model->delete($id)) {
            $this->Session->setFlash('Successfully deleted', 'default', array('class' => 'green'));
        } else {
            $this->Session->setFlash('Unable to delete, try again', 'default', array('class' => 'red'));
        }
        $this->redirect($this->referer());
    }

    /* @Created:    29-july-2014
     * @Method :     removeEvent
     * @Author:      Prateek Jadhav
     * @Modified :  
     * @Purpose:     Common Remove Event Function
     * @Param:       $event_id
     * @Return:      1/0
     */

    public function removeEvent($event_id = NULL) {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $this->loadModel("Event");
        if ($event_id) {
            $data = $this->Event->find("first", array("conditions" => array("Event.id" => $event_id), "fields" => array("Event.id"), "recursive" => -1));
            if ($data) {
                $data["Event"]["status"] = 0;
                if ($this->Event->save($data)) {
                    return 1;
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    /* @Created:    29-july-2014
     * @Method :     feature
     * @Author:      Prateek Jadhav
     * @Modified :  
     * @Purpose:     Common feature Event Function
     * @Param:       $event_id
     * @Return:      1/2/0
     */

    public function feature($event_id = NULL) {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $this->loadModel("Event");
        if ($event_id) {
            $data = $this->Event->find("first", array("conditions" => array("Event.id" => $event_id), "fields" => array("Event.id", "Event.is_feature"), "recursive" => -1));
            if ($data) {
                if ($data["Event"]["is_feature"] == 0) {
                    $data["Event"]["is_feature"] = 1;
                    $return = 1;
                } else {
                    $data["Event"]["is_feature"] = 0;
                    $return = 2;
                }
                if ($this->Event->save($data)) {
                    return $return;
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

}

?>