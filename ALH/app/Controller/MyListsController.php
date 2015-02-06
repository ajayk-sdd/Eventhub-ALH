<?php

class MyListsController extends AppController {

    var $helpers = array('Html', 'Form', 'Cache');
    public $components = array('Email', 'Upload');
    public $paginate = array(
        'limit' => 10
    );

    /** @Created:    22-May-2014
     * @Method :     beforeFilter
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Rendered before any function
     * @Param:       none
     * @Return:      none
     */
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('cronSetEmailInfo');
    }

    /** @Created:    22-May-2014
     * @Method :     admin_index
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     this is index page
     * @Param:       none
     * @Return:      none
     */
    public function admin_index() {
        $this->redirect(array("controller" => "Lists", "action" => "list"));
    }

    /** @Created:    22-May-2014
     * @Method :     admin_list
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for listing of brands
     * @Param:       none
     * @Return:      none
     */
    public function admin_list() {
        $this->layout = 'admin/admin';
        $this->set('title_for_layout', 'ALIST Hub :: Listing');
        $this->loadModel("MyList");
        $conditions = array();
        if (isset($this->params["named"]["page"])) {
            $this->request->data = $this->Session->read("mylist");
        } else {
            $this->Session->delete("mylist");
        }
        if ($this->data) {
            if (!empty($this->data["MyList"]["list_name"])) {
                $conditions = array_merge($conditions, array("MyList.list_name LIKE" => "%" . $this->data["MyList"]["list_name"] . "%"));
            }
            if (!empty($this->data["User"]["username"])) {
                $conditions = array_merge($conditions, array("User.username LIKE" => "%" . $this->data["User"]["username"] . "%"));
            }
            $this->paginate = array('conditions' => $conditions, 'limit' => $this->data["MyList"]["limit"], "recursive" => 2, 'order' => array($this->data["MyList"]["order"] => $this->data["MyList"]["direction"]));
            $this->request->data = $this->data;
            $this->Session->write("mylist", $this->data);
        } else {
            $this->paginate = array('conditions' => $conditions, 'limit' => 10, "recursive" => 2, 'order' => array('MyList.id' => 'DESC'));
        }
       
        $this->set('mylists', $this->paginate('MyList'));
    }

    /** @Created:    22-May-2014
     * @Method :     admin_add
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for add/edit brands
     * @Param:       $brand_id
     * @Return:      none
     */
    public function admin_add($list_id = null) {
        $this->layout = 'admin/admin';
        $this->set('title_for_layout', 'ALIST Hub :: Add/Edit Brand');
        $this->loadModel('User');
        $this->loadModel("MyList");
        $UAddress = $this->User->find("first", array("conditions" => array("User.id" => AUthComponent::user("id"))));
        $this->set("user", $this->User->find("list", array("conditions" => array("User.status" => 1, "User.role_id" => 4), "fields" => array("User.id", "User.username"), 'order' => "User.username ASC")));
        $this->set("userAddress", $UAddress['User']['address']);
        $this->set("userDetail", $UAddress['User']);
        
        $reminds = $this->MyList->find("list", array("conditions" => array("MyList.user_id" => AUthComponent::user("id"), "MyList.remind_me !=" => ""), "fields" => array("MyList.remind_me", "MyList.remind_me")));
        $this->set("reminds", $reminds);
        if ($this->data) {
            if ($this->MyList->save($this->data)) {
                if (!empty($list_id)) {
                    $msg = "List has been updated successfully";
                } else {
                    $msg = "New List has been added successfully";
                }
                $this->Session->setFlash($msg, "default", array("class" => "green"));
                $this->redirect(array("controller" => "MyLists", "action" => "list"));
            } else {
                $this->Session->setFlash("Unable to update list, Please try again", "default", array("class" => "red"));
            }
        } if ($list_id) {
            $list_id = base64_decode($list_id);
            $this->request->data = $this->MyList->findById($list_id);
        }
    }

    /** @Created:    22-May-2014
     * @Method :     admin_editEmail
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for edit emails admin
     * @Param:       $id,$value
     * @Return:      none
     */
    public function admin_editEmail($id = null, $value = null) {
        //echo $id;die;
        $this->set('title_for_layout', 'ALIST Hub :: Edit Email');
        $this->layout = false;
        $this->autoRender = false;
        $this->loadModel("ListEmail");
        if (isset($id) && isset($value)) {
            if ($this->ListEmail->updateAll(array('ListEmail.email' => "'" . $value . "'"), array('ListEmail.id' => $id))) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    /** @Created:    12-May-2014
     * @Method :     admin_view
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for view brands
     * @Param:       $brand_id
     * @Return:      none
     */
    public function admin_view($mylist_id = null) {
        $this->layout = "admin/admin";
        $this->set('title_for_layout', 'ALIST Hub :: View Brand');
        if ($mylist_id) {
            $mylist = $this->MyList->find("first", array("conditions" => array("MyList.id" => base64_decode($mylist_id)), "recursive" => 2)); #pr($brand);die;
            if (!empty($mylist)) {
                $this->set('mylist', $mylist);
            } else {
                $this->Session->setFlash("Data not found", "default", array("class" => "red"));
                $this->redirect(array("controller" => "MyLists", "action" => "list"));
            }
        } else {
            $this->Session->setFlash("Direct access not allowed", "default", array("class" => "red"));
            $this->redirect(array("controller" => "MyLists", "action" => "list"));
        }
    }

    /** @Created:    12-May-2014
     * @Method :     admin_list_csv
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for listing of brands
     * @Param:       none
     * @Return:      none
     */
    public function admin_list_csv() {
        $this->layout = "admin/admin";
        $conditions = array();
        if ($this->Session->check("paginator")) {
            $this->request->data = $this->Session->read("paginator");
        }
        if ($this->data) {
            if (!empty($this->data["Brand"]["name"])) {
                $conditions = array_merge($conditions, array("Brand.name LIKE" => "%" . $this->data["Brand"]["name"] . "%"));
            }
            if (!empty($this->data["User"]["username"])) {
                $conditions = array_merge($conditions, array("User.username LIKE" => "%" . $this->data["User"]["username"] . "%"));
            }
            $this->paginate = array('conditions' => $conditions, 'order' => array($this->data["Brand"]["order"] => $this->data["Brand"]["direction"]));
            $this->request->data = $this->data;
            $this->Session->write("paginator", $this->data);
        } else {
            $this->paginate = array('conditions' => $conditions, 'order' => array('Brand.id' => 'ASC'));
        }
       
        $this->set('brands', $this->paginate('Brand'));
    }

    /** @Created:    22-May-2014
     * @Method :     
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     listing of list
     * @Param:       none
     * @Return:      none
     */
    function lists() {
        $this->layout = "frontend";
        $this->paginate = array('conditions' => "", 'limit' => 4, 'order' => array('MyList.id' => 'DESC'), 'recursive' => 2);
        $lists = $this->paginate('MyList');
        
        $this->set('lists', $lists);
    }

    /** @Created:    26-May-2014
     * @Method :     MyList
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for listing of mylist
     * @Param:       none
     * @Return:      none
     */
    public function myList() {
        $this->layout = 'front/home';
        $this->loadModel("MyList");
        $this->loadModel("CampaignEmail");
        $this->loadModel("ListEmail");
        $conditions = array("MyList.user_id" => AuthComponent::user("id"));
        if (isset($this->params["named"]["page"])) {
            $this->request->data = $this->Session->read("mylist");
        } else {
            $this->Session->delete("mylist");
        }
        if ($this->data) {
            // search criteria will be here
            $this->paginate = array('conditions' => $conditions, 'limit' => $this->data["MyList"]["limit"], 'order' => array($this->data["MyList"]["order"] => $this->data["MyList"]["direction"]));
            $this->request->data = $this->data;
            $this->Session->write("mylist", $this->data);
        } else {
            $this->paginate = array('conditions' => $conditions, 'limit' => 4, 'order' => array('MyList.id' => 'DESC'), "recursive" => -1);
        }
     
        $mylists = $this->paginate('MyList');
        $i = 0;
        foreach ($mylists as $mylist) {
            $list_id = $mylist["MyList"]["id"];
            $total_mail = $this->ListEmail->find("count", array("conditions" => array("ListEmail.my_list_id" => $list_id)));
            $sent_mail = $this->CampaignEmail->find("count", array("conditions" => array("CampaignEmail.sent_status" => 1, "CampaignEmail.list_id" => $list_id)));
            $bounce = $total_mail - $sent_mail;
            $open_mail = $this->CampaignEmail->find("count", array("conditions" => array("CampaignEmail.sent_status" => 1, "CampaignEmail.list_id" => $list_id, "CampaignEmail.open_status" => 1)));
            $click_mail = $this->CampaignEmail->find("count", array("conditions" => array("CampaignEmail.sent_status" => 1, "CampaignEmail.list_id" => $list_id, "CampaignEmail.click_status" => 1)));

            $mylists[$i]["MyList"]["total_mail"] = $total_mail;
            $mylists[$i]["MyList"]["sent_mail"] = $sent_mail;
            $mylists[$i]["MyList"]["bounce_mail"] = $bounce;
            $mylists[$i]["MyList"]["open_mail"] = $open_mail;
            $mylists[$i]["MyList"]["click_mail"] = $click_mail;
            $i++;
        }
        $this->set('mylists', $mylists);
    }

    /** @Created:    26-May-2014
     * @Method :     view
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for view mylist
     * @Param:       $brand_id
     * @Return:      none
     */
    public function view($mylist_id = null) {
        $this->layout = "front/home";
        $this->loadModel("MyList");
        $conditions_my = array("MyList.user_id" => AuthComponent::user("id"));

        $mlist = $this->MyList->find("all", array('conditions' => $conditions_my, 'order' => array('MyList.id' => 'ASC'), "recursive" => -1));

        // $this->paginate = array('conditions' => $conditions_my, 'order' => array('MyList.id' => 'ASC'), "recursive" => -1);
       
        $this->set('mylists_link', $mlist);
       
        $this->set("list_id", $mylist_id);

        $listdetail = $this->MyList->find("first", array("conditions" => array("MyList.id" => base64_decode($mylist_id)), "recursive" => 2));

        $this->set("listdetail", $listdetail);

        $conditions = array("ListEmail.my_list_id" => base64_decode($mylist_id));

        if ($this->data) {
            if (!empty($this->data["ListEmail"]["email"])) {
                $conditions = array_merge($conditions, array("ListEmail.email LIKE" => "%" . $this->data["ListEmail"]["email"] . "%"));
            }

            $this->paginate = array('conditions' => $conditions, 'limit' => 20, 'order' => array($this->data["ListEmail"]["order"] => $this->data["ListEmail"]["direction"]));
            $this->request->data = $this->data;
            $this->Session->write("list_emails", $this->data);
        } else {

            $this->paginate = array('conditions' => $conditions, 'limit' => 20, 'order' => array('ListEmail.email' => 'ASC'));
        }

        $this->set('list_emails', $this->paginate('ListEmail'));
    }

    /** @Created:    26-May-2014
     * @Method :     add
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for add/edit lists
     * @Param:       $list_id
     * @Return:      none
     */
    public function add($list_id = null) {
        $this->layout = "front/home";
        $this->loadModel("MyList");
        $this->loadModel("User");
      
        $UAddress = $this->User->find("first", array("conditions" => array("User.id" => AUthComponent::user("id")), "fields" => array("User.address")));
        $this->set("userAddress", $UAddress['User']['address']);
        
        $reminds = $this->MyList->find("list", array("conditions" => array("MyList.user_id" => AUthComponent::user("id"), "MyList.remind_me !=" => ""), "fields" => array("MyList.remind_me", "MyList.remind_me")));
        $this->set("reminds", $reminds);
        if ($this->data) {
            if ($this->MyList->save($this->data)) {
                if(!empty($list_id))
                {
                    $msg = "List has been updated successfully";
                }
                else
                {
                     $msg = "New List has been added successfully";
                }
                $this->Session->setFlash($msg, "default", array("class" => "green"));
                $this->redirect(array("controller" => "MyLists", "action" => "myList"));
            } else {
                $this->Session->setFlash("Unable to update list, Please try again", "default", array("class" => "red"));
            }
        } if ($list_id) {
            $list_id = base64_decode($list_id);
            $this->request->data = $this->MyList->findById($list_id);
        }
    }

    /** @Created:    04-Jun-2014
     * @Method :     import
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for import contact from various email provider
     * @Param:       none
     * @Return:      none
     */
    public function import() {
        $this->autoRender = FALSE;
        $this->layout = FALSE;
        $this->loadModel("ListEmail");
        if ($this->data) {
            $id = trim($this->data["MyList"]["id"]);
           
           $contact_list_email = explode(",",htmlspecialchars($this->data["MyList"]["contact_list_email"]));
           
           $n = 0;
           
          foreach($contact_list_email as $emailList)
           {
              $eList = array_reverse(explode(" ",trim($emailList)));
              $emailTrim = trim($eList[0]);
          print_r($emailTrim); 
              $emailLeftTrim = array_reverse(explode('&lt;',$emailTrim));
          
              $emailRightTrim = explode('&gt;',$emailLeftTrim[0]);
          
              $emailAdd = $emailRightTrim[0];
              
              $checkEmail = $this->ListEmail->find("all", array("conditions" => array("ListEmail.email" => $emailAdd, "ListEmail.my_list_id" => $id)));
             
              $checkEmailCount = count($checkEmail);
              
              if($checkEmailCount==0)
              {
                        $data["ListEmail"]["id"] = "";
                        $data["ListEmail"]["email"] = $emailAdd;
                        $data["ListEmail"]["from"] = 2;
                        $data["ListEmail"]["my_list_id"] = $id;
                        $this->ListEmail->save($data);
                        $n++;
              }
                   
           }
           die;
           
                   $countList = count($contact_list_email);
                   
                    if($n>0 && $countList>0)
                    {
                        $this->Session->setFlash("$n Contacts has been Imported Successfully", "default", array("class" => "green"));
                    }
                    elseif($n==0)
                    {
                        $this->Session->setFlash("Unable to Import, No New Contact in List", "default", array("class" => "red"));
                    }
                    else
                    {
                        $this->Session->setFlash("Unable to Import, No Contact in List", "default", array("class" => "red"));
                    }
    
            $this->redirect($this->referer());
        }
    }

    /** @Created:    26-May-2014
     * @Method :     editEmail
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for edit emails frontend
     * @Param:       $id,$value
     * @Return:      none
     */
    public function editEmail($id = null, $value = null) {
        //echo $id; echo $value;die;
        $this->layout = "ajax";
        $this->autoRender = false;
        $this->loadModel("ListEmail");
        if (isset($id) && isset($value)) {
            if ($this->ListEmail->updateAll(array('ListEmail.email' => "'" . $value . "'"), array('ListEmail.id' => $id))) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    /** @Created:    26-May-2014
     * @Method :     delete
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for delete emails frontend
     * @Param:       $id
     * @Return:      none
     */
    public function delete($mylist_id) {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        if ($mylist_id) {
            $mylist_id = base64_decode($mylist_id);
            if ($this->MyList->delete($mylist_id)) {
                $this->Session->setFlash("List Deleted", "default", array("class" => "green"));
                $this->redirect(array("controller", "action" => "myList"));
            } else {
                $this->Session->setFlash("List Unable To Delete", "default", array("class" => "red"));
                $this->redirect(array("controller", "action" => "myList"));
            }
        } else {
            $this->Session->setFlash("Direct access not allowed", "default", array("class" => "green"));
            $this->redirect(array("controller" => "MyLists", "action" => "myList"));
        }
    }

    /** @Created:    03-Jun-2014
     * @Method :     admin_listView
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     for view mylist Email
     * @Param:       $mylist_id
     * @Return:      none
     */
    public function admin_listView($mylist_id = null) {
        $this->layout = "admin/admin";
        $this->set('title_for_layout', 'ALIST Hub :: MyList List');

        $this->set("list_id", $mylist_id);

        $listdetail = $this->MyList->find("first", array("conditions" => array("MyList.id" => base64_decode($mylist_id)), "recursive" => 2));

        $this->set("listdetail", $listdetail);

        $conditions = array("ListEmail.my_list_id" => base64_decode($mylist_id));

        if ($this->data) {
            if (!empty($this->data["ListEmail"]["email"])) {
                $conditions = array_merge($conditions, array("ListEmail.email LIKE" => "%" . $this->data["ListEmail"]["email"] . "%"));
            }

            $this->paginate = array('conditions' => $conditions, 'limit' => $this->data["ListEmail"]["limit"], 'order' => array($this->data["ListEmail"]["order"] => $this->data["ListEmail"]["direction"]));
            $this->request->data = $this->data;
            $this->Session->write("list_emails", $this->data);
        } else {

            $this->paginate = array('conditions' => $conditions, 'limit' => 20, 'order' => array('ListEmail.email' => 'ASC'));
        }

        $this->set('list_emails', $this->paginate('ListEmail'));
    }

    /** @Created:    05-Jun-2014
     * @Method :     importCsv
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for import email using csv file
     * @Param:       none
     * @Return:      none
     */
    public function importCsv() {
        $this->layout = false;
        $this->autoRender = false;
        if ($this->data) {
            //pr($this->data);die;
            $imgNameExt = pathinfo($this->data['MyList']['file']['name']);
            $ext = $imgNameExt['extension'];
            $ext = strtolower($ext);
            if ($ext == 'csv') {
                $finalpath = ('../webroot/files/EmailCsv') . '/';
                $file = $this->data["MyList"]["file"]; //pr($file);die;tmp_name
                $newname = $this->data["MyList"]["file"]['name'];
               
                $tmp_name = $this->data["MyList"]["file"]['tmp_name'];

                //move_uploaded_file($tmp_name,WWW_ROOT.'zip/'.$newname);die;

                if (move_uploaded_file($tmp_name, WWW_ROOT . 'files/EmailCsv/' . $newname)) {
                    ini_set('max_execution_time', 2000);
                    $this->loadModel('ListEmail');
                    
                    $delimeter = $this->csv_delimeter(WWW_ROOT . 'files/EmailCsv/' . $newname);
                    
                    if (($handle = fopen("files/EmailCsv/$newname", "r")) !== false) {
                     
                          $read = fgetcsv($handle, filesize(WWW_ROOT . 'files/EmailCsv/' . $newname), ";");
                       
                         $extFirst = explode($delimeter,trim($read[0]));
                         $extFirst = array_flip(array_map('strtolower', $extFirst));
                         
                         
                        $i = 0;
                        while (($read = fgetcsv($handle, filesize(WWW_ROOT . 'files/EmailCsv/' . $newname), $delimeter)) !== false) {
                           
                            if(isset($extFirst['email']))
                            {
                                $email = $read[$extFirst['email']];
                            }
                            else
                            {
                                $email = $read[0];
                            }
                            if(isset($extFirst['first-name']))
                            {
                                $fName = $read[$extFirst['first-name']];
                            }
                            else
                            {
                                $fName = $read[1];
                            }
                            if(isset($extFirst['last-name']))
                            {
                                $lName = $read[$extFirst['last-name']];
                            }
                            else
                            {
                                $lName = $read[2];
                            }
                            if(isset($extFirst['phone']))
                            {
                                $phone = $read[$extFirst['phone']];
                            }
                            else
                            {
                                $phone = $read[3];
                            }
                            $listEmail = $this->ListEmail->find("first", array("conditions" => array("ListEmail.email" => $email,"ListEmail.my_list_id" => $this->data["MyList"]["id"])));
                            $listEmailCount = count($listEmail);
                            if($listEmailCount==0)
                            {
                            $i++;
                            $data['ListEmail']['id'] = "";
                            $data['ListEmail']['email'] = $email;
                            $data['ListEmail']['first_name'] = $fName;
                            if(isset($lName) && !is_numeric($lName))
                            {
                            $data['ListEmail']['last_name'] = $lName;
                            }
                            if(isset($phone) && is_numeric($phone))
                            {
                            $data['ListEmail']['phone'] = $phone;
                            }
                            $data["ListEmail"]["from"] = 2;
                            $data['ListEmail']['my_list_id'] = $this->data["MyList"]["id"];
                            $this->ListEmail->save($data);
                            
                            }
                           
                        }
                        
                    } 
                }
                $this->Session->setFlash("$i data imported successfully", "default", array("class" => "green"));
                $this->redirect($this->referer());
            } else {
                $this->Session->setFlash("Not valid csv file", "default", array("class" => "red"));
                $this->redirect($this->referer());
            }
        }
    }

    /** @Created:    05-Jun-2014
     * @Method :     exportCsv
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for export email through csv file
     * @Param:       $MyList_id
     * @Return:      none
     */
    public function exportCsv($myList_id = null) {
        $this->layout = FALSE;
        $this->loadModel("ListEmail");
        if ($myList_id) {
            $myList_id = base64_decode($myList_id);
            $datas = $this->ListEmail->find("all", array("conditions" => array("ListEmail.my_list_id" => $myList_id), "fields" => array("ListEmail.email", "ListEmail.first_name", "ListEmail.last_name", "ListEmail.phone")));
            $this->set("datas", $datas);
        } else {
            $this->Session->setFlash("Direct access not allowed", "default", array("class" => "red"));
            $this->redirect(array("controller" => "MyLists", "action" => "myList"));
        }
    }

    /** @Created:    05-Jun-2014
     * @Method :     exportCsv
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     for export email through csv file For admin
     * @Param:       $MyList_id
     * @Return:      none
     */
    public function admin_exportCsv($myList_id = null) {
         $this->layout = FALSE;
        $this->loadModel("ListEmail");
        if ($myList_id) {
            $myList_id = base64_decode($myList_id);
            $datas = $this->ListEmail->find("all", array("conditions" => array("ListEmail.my_list_id" => $myList_id), "fields" => array("ListEmail.email", "ListEmail.first_name", "ListEmail.last_name", "ListEmail.phone")));
            $this->set("datas", $datas);
        } else {
            $this->Session->setFlash("Direct access not allowed", "default", array("class" => "red"));
            $this->redirect(array("controller" => "MyLists", "action" => "myList"));
        }
    }

    /** @Created:    04-Jun-2014
     * @Method :     admin_import
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for import contact from various email provider
     * @Param:       none
     * @Return:      none
     */
    public function admin_import() {
        $this->autoRender = FALSE;
        $this->layout = FALSE;
        $this->loadModel("ListEmail");
          if ($this->data) {
            $id = trim($this->data["MyList"]["id"]);
           
           $contact_list_email = explode(",",htmlspecialchars($this->data["MyList"]["contact_list_email"]));
           
           $n = 0;
           
          foreach($contact_list_email as $emailList)
           {
              $eList = array_reverse(explode(" ",trim($emailList)));
              $emailTrim = trim($eList[0]);
          
              $emailLeftTrim = array_reverse(explode('&lt;',$emailTrim));
          
              $emailRightTrim = explode('&gt;',$emailLeftTrim[0]);
          
              $emailAdd = $emailRightTrim[0];
              
              $checkEmail = $this->ListEmail->find("all", array("conditions" => array("ListEmail.email" => $emailAdd, "ListEmail.my_list_id" => $id)));
             
              $checkEmailCount = count($checkEmail);
              
              if($checkEmailCount==0)
              {
                        $data["ListEmail"]["id"] = "";
                        $data["ListEmail"]["email"] = $emailAdd;
                        $data["ListEmail"]["from"] = 2;
                        $data["ListEmail"]["my_list_id"] = $id;
                        $this->ListEmail->save($data);
                        $n++;
              }
                   
           }
           
                   $countList = count($contact_list_email);
                   
                    if($n>0 && $countList>0)
                    {
                        $this->Session->setFlash("$n Contacts has been Imported Successfully", "default", array("class" => "green"));
                         $this->redirect(array("controller" => "MyLists", "action" => "listView", base64_encode($this->data["MyList"]["id"])));
                    }
                    elseif($n==0)
                    {
                        $this->Session->setFlash("Unable to Import, No New Contact in List", "default", array("class" => "red"));
                    }
                    else
                    {
                        $this->Session->setFlash("Unable to Import, No Contact in List", "default", array("class" => "red"));
                    }
         
            $this->redirect($this->referer());
        }
    }

    /** @Created:    05-Jun-2014
     * @Method :     importCsv
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for import email using csv file
     * @Param:       none
     * @Return:      none
     */
    public function admin_importCsv() {
        $this->layout = false;
        $this->autoRender = false;
        if ($this->data) {
           
            $imgNameExt = pathinfo($this->data['MyList']['file']['name']);
            $ext = $imgNameExt['extension'];
            $ext = strtolower($ext);
            if ($ext == 'csv') {
                $finalpath = ('../webroot/files/EmailCsv') . '/';
                $file = $this->data["MyList"]["file"]; //pr($file);die;tmp_name
                $newname = $this->data["MyList"]["file"]['name'];
                
                $tmp_name = $this->data["MyList"]["file"]['tmp_name'];

                if (move_uploaded_file($tmp_name, WWW_ROOT . 'files/EmailCsv/' . $newname)) {
                    ini_set('max_execution_time', 2000);
                    $this->loadModel('ListEmail');
                    $delimeter = $this->csv_delimeter(WWW_ROOT . 'files/EmailCsv/' . $newname);
                    
                    if (($handle = fopen("files/EmailCsv/$newname", "r")) !== false) {
                     
                          $read = fgetcsv($handle, filesize(WWW_ROOT . 'files/EmailCsv/' . $newname), ";");
                       
                         $extFirst = explode($delimeter,trim($read[0]));
                         $extFirst = array_flip(array_map('strtolower', $extFirst));
                         
                         
                        $i = 0;
                        while (($read = fgetcsv($handle, filesize(WWW_ROOT . 'files/EmailCsv/' . $newname), $delimeter)) !== false) {
                          
                            if(isset($extFirst['email']))
                            {
                                $email = $read[$extFirst['email']];
                            }
                            else
                            {
                                $email = $read[0];
                            }
                            if(isset($extFirst['first-name']))
                            {
                                $fName = $read[$extFirst['first-name']];
                            }
                            else
                            {
                                $fName = $read[1];
                            }
                            if(isset($extFirst['last-name']))
                            {
                                $lName = $read[$extFirst['last-name']];
                            }
                            else
                            {
                                $lName = $read[2];
                            }
                            if(isset($extFirst['phone']))
                            {
                                $phone = $read[$extFirst['phone']];
                            }
                            else
                            {
                                $phone = $read[3];
                            }
                            $listEmail = $this->ListEmail->find("first", array("conditions" => array("ListEmail.email" => $email,"ListEmail.my_list_id" => $this->data["MyList"]["id"])));
                            $listEmailCount = count($listEmail);
                            if($listEmailCount==0)
                            {
                            $i++;
                            $data['ListEmail']['id'] = "";
                            $data['ListEmail']['email'] = $email;
                            $data['ListEmail']['first_name'] = $fName;
                            if(isset($lName) && !is_numeric($lName))
                            {
                            $data['ListEmail']['last_name'] = $lName;
                            }
                            if(isset($phone) && is_numeric($phone))
                            {
                            $data['ListEmail']['phone'] = $phone;
                            }
                            $data["ListEmail"]["from"] = 2;
                            $data['ListEmail']['my_list_id'] = $this->data["MyList"]["id"];
                            $this->ListEmail->save($data);
                            
                            }
                           
                        }
                        
                    }
                }
                $this->Session->setFlash("$i data imported successfully", "default", array("class" => "green"));
                $this->redirect(array("controller" => "MyLists", "action" => "listView", base64_encode($this->data["MyList"]["id"])));
            } else {
                $this->Session->setFlash("Not valid csv file", "default", array("class" => "red"));
                //$this->redirect(array("controller" => "MyLists", "action" => "add", base64_encode($this->data["MyList"]["id"])));
            }
        }
    }

    /** @Created:    11-Jun-2014
     * @Method :     premiumList
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for listing of premiumList
     * @Param:       none
     * @Return:      none
     */
    public function premiumList() {
        $this->layout = "front/home";
        $this->set('title_for_layout', 'ALIST Hub :: Premium List');
        $myId = AuthComponent::user("id");
        $this->MyList->unbindModel(array('hasMany' => array('ListEmail')));

        $this->loadModel("Region");
        $this->loadModel("Category");
        $this->loadModel("Vibe");

        $regions = $this->Region->find("all", array("conditions" => array(), "recursive" => -1));
        $categories = $this->Category->find("all", array("conditions" => array(), "recursive" => -1));
        $vibes = $this->Vibe->find("all", array("conditions" => array(), "recursive" => -1));
        $this->set(compact('regions', 'categories', 'vibes'));

        $conditions = array();
        $conditions = array_merge($conditions, array("MyList.status" => 1, "MyList.user_id !=" => $myId));

        if (isset($this->data["MyList"]["order"])) {
            $order = $this->data["MyList"]["order"];
        } else {
            $order = "MyList.id ASC";
        }

        if ($this->data) {
            if (isset($this->data['listVibe']['id']) && $this->data['listVibe']['id'] != "") {
                $arrayVibe = array();
                foreach ($this->data['listVibe']['id'] as $key => $value):
                    $arrayVibe[] = $key;
                endforeach;


                $VibesSearch = $this->Vibe->find("all", array("conditions" => array('Vibe.id' => $arrayVibe), "recursive" => -1));

                foreach ($VibesSearch as $vibeSearch):
                    $vibeSearchArray[] = $vibeSearch['Vibe']['name'];
                endforeach;
                $vibesS = substr(implode(', ', $vibeSearchArray), 0, 50) . '...';
                $this->set("vibesS", $vibesS);

                if (isset($this->data['listVibe']['id']) && !empty($this->data['listVibe']['id'])) {
                    // $conditions = array_merge($conditions,array('listVibe.vibe_id'=>$arrayVibe));
                }
            }
            if (isset($this->data['listCategory']['id']) && $this->data['listCategory']['id'] != "") {
                $arrayCategory = array();
                foreach ($this->data['listCategory']['id'] as $key => $value):
                    $arrayCategory[] = $key;
                endforeach;

                $categoriesSearch = $this->Category->find("all", array("conditions" => array('Category.id' => $arrayCategory), "recursive" => -1));

                foreach ($categoriesSearch as $catSearch):
                    $catSearchArray[] = $catSearch['Category']['name'];
                endforeach;
                $categoriesS = substr(implode(', ', $catSearchArray), 0, 50) . '...';
                $this->set("categoriesS", $categoriesS);

                if (isset($this->data['listCategory']['id']) && !empty($this->data['listCategory']['id'])) {
                    //  $conditions = array_merge($conditions,array('listCategory.category_id'=>$arrayCategory));
                }
            }

            if (isset($this->data["MyList"]["title"])) {

                $conditions = array_merge($conditions, array("MyList.list_name LIKE" => '%' . $this->data["MyList"]["title"] . '%'));
            }
        }


        if (isset($this->data["MyList"]["limit"]) && !empty($this->data["MyList"]["limit"])) {
          
            $limit = $this->data["MyList"]["limit"];
        } else {
            $limit = 10;
        }
        $this->set("limit", $limit);
      
        $conditions = array_merge($conditions, array("MakeOffer.status" => 1));
        $this->MyList->unbindModel(array("hasMany" => array("ListEmail")));
        $this->paginate = array('conditions' => $conditions, 'limit' => $limit, 'order' => $order, "recursive" => 2);
        $this->set('lists', $this->paginate('MyList'));
       

        if (isset($this->params->query['type']) && $this->params->query['type'] == "list") {
            $this->set('viewType', 'list');
        }
    }

    /** @Created:    11-Jun-2014
     * @Method :     makeAOffer
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for showing and adjust price of offer
     * @Param:       $myListId
     * @Return:      none
     */
    public function makeAOffer($myListId = NULL) {
        $this->layout = "front/home";
        if ($myListId) {
            $myListId = base64_decode($myListId);
            $this->MyList->unbindModel(array('hasMany' => array('ListEmail')));
            $data = $this->MyList->find("first", array("conditions" => array("MyList.id" => $myListId), "recursive" => 2));
            $this->set("data", $data);
        } else {
            $this->Session->setFlash("Direct access not allowed", "default", array("class" => "red"));
            $this->redirect(array("controller" => "Users", "action" => "dashboard"));
        }
    }

    /*     * @Created:     12-Jun-2014
     * @Method :     admin_listOfferPrice
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for managing offer price
     * @Param:       none
     * @Return:      none
     */

    public function admin_listOfferPrice() {
        $this->layout = "admin/admin";
        $this->set('title_for_layout', 'ALIST Hub :: Offer Price List');
        $this->loadModel("OfferPrice");
        $this->set('offer_prices', $this->paginate('OfferPrice'));
    }

    /*     * @Created:     12-Jun-2014
     * @Method :     admin_addOfferPrice
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for add/edit offer price
     * @Param:       $offerPriceId/none
     * @Return:      none
     */

    public function admin_addOfferPrice($offerPriceId = NULL) {
        $this->layout = "admin/admin";
        $this->set('title_for_layout', 'ALIST Hub :: Add Offer Price');
        $this->loadModel("OfferPrice");
        $lists = $this->MyList->find("list", array("conditions" => array("MyList.status" => 1), "fields" => array("MyList.id", "MyList.list_name"), 'order' => "MyList.list_name ASC"));
        $this->set("lists", $lists);
        if ($this->data) {
            if ($this->OfferPrice->save($this->data)) {
                if(empty($this->data['OfferPrice']["id"]))
                {
                    $upMsg = "Added Successfully"; 
                }
                else
                {
                    $upMsg = "Updated Successfully";
                }
                $this->Session->setFlash($upMsg, "default", array("class" => "green"));
                $this->redirect(array("controller" => "MyLists", "action" => "listOfferPrice"));
            } else {
                $this->Session->setFlash("Unable to update offer price, try again", "default", array("class" => "red"));
            }
        }
        if ($offerPriceId) {
            $offerPriceId = base64_decode($offerPriceId);
            $this->request->data = $this->OfferPrice->findById($offerPriceId);
        }
    }

    /*     * @Created:     12-Jun-2014
     * @Method :     checkPrice
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for check and return offer price
     * @Param:       $my_list_id, $parameter, $qty
     * @Return:      $total
     */

    public function checkPrice($my_list_id = null, $parameter = null, $qty = null) {
        $this->layout = false;
        $this->autoRender = false;
        $this->loadModel("OfferPrice");
        $price = $this->OfferPrice->find("first", array("conditions" => array("OfferPrice.my_list_id" => $my_list_id)));
        if ($price) {
            
            $P = $price["OfferPrice"][$parameter];
            $total = $P * $qty;
        } else {
            $total = 0;
        }
        return $total;
    }

    /*     * @Created:     12-Jun-2014
     * @Method :     makeOffer
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for saving an offer
     * @Param:       none
     * @Return:      none
     */

    public function makeOffer() {
        $this->layout = "front/home";
        $this->loadModel("MakeOffer");
        if ($this->data) {
            if ($this->MakeOffer->save($this->data)) {
                $id = $this->MakeOffer->getLastInsertID();
                // $this->sendConfirmationMail($id);
                $this->Session->setFlash("Your offer submitted.", "default", array("class" => "green"));
                $this->set("yes", "yes");
            } else {
                $this->Session->setFlash("Unable to update offer price, try again", "default", array("class" => "red"));
                $this->redirect(array("controller" => "MyLists", "action" => "makeAOffer"));
            }
        }
    }

    /*     * @Created:     31-July-2014
     * @Method :     sendConfirmationMail
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for send confirmation email
     * @Param:       make_offer_id
     * @Return:      TRUE/FALSE
     */

    public function sendConfirmationMail($make_offer_id = NULL) {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $this->loadModel("MakeOffer");
        $this->loadModel("EmailTemplate");
        if ($make_offer_id) {

            $make_offer_data = $this->MakeOffer->find("first", array("conditions" => array("MakeOffer.id" => $make_offer_id), "recursive" => 1));
            $user_detail = $this->User->find("first", array("conditions" => array("User.id" => $make_offer_data["MyList"]["user_id"]), "recursive" => "-1"));
          
            // send to user
            $emailTemp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.alias' => "offer-submit")));
            $emailContent = utf8_decode($emailTemp['EmailTemplate']['description']);
            $data = str_replace(array('{USER_NAME}', '{LIST_NAME}', '{DEDICATED_EMAIL_TO_SEND}', '{MULTI_EVENT_To_SEND}', '{TICKET_OFFER_FOR_TRADE}', '{TICKET_VALUE}', '{ADJUSTED_PRICE}'), array($user_detail["User"]["username"], $make_offer_data["MyList"]["list_name"], $make_offer_data["MakeOffer"]["dedicated_email_to_send"], $make_offer_data["MakeOffer"]["multi_event_to_send"], $make_offer_data["MakeOffer"]["ticket_offered_for_trade"], $make_offer_data["MakeOffer"]["ticket_value"], $make_offer_data["MakeOffer"]["adjusted_price"]), $emailContent);
            $this->set('mailData', $data);
            $subject = str_replace(array('{LIST_NAME}'), array($make_offer_data["MyList"]["list_name"]), utf8_decode($emailTemp['EmailTemplate']['subject']));
            $this->Email->to = $user_detail["User"]["email"];
            $this->Email->subject = $subject;
            $this->Email->from = "admin@alisthub.com";
            $this->Email->template = "forgot";
            $this->Email->sendAs = 'html';
            $this->Email->send($data);

            // send to admin
            $emailTemp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.alias' => "offer-submit-admin")));
            $emailContent = utf8_decode($emailTemp['EmailTemplate']['description']);
            $data = str_replace(array('{USER_NAME}', '{LIST_NAME}', '{DEDICATED_EMAIL_TO_SEND}', '{MULTI_EVENT_To_SEND}', '{TICKET_OFFER_FOR_TRADE}', '{TICKET_VALUE}', '{ADJUSTED_PRICE}'), array($user_detail["User"]["username"], $make_offer_data["MyList"]["list_name"], $make_offer_data["MakeOffer"]["dedicated_email_to_send"], $make_offer_data["MakeOffer"]["multi_event_to_send"], $make_offer_data["MakeOffer"]["ticket_offered_for_trade"], $make_offer_data["MakeOffer"]["ticket_value"], $make_offer_data["MakeOffer"]["adjusted_price"]), $emailContent);
            $this->set('mailData', $data);
            $subject = str_replace(array('{LIST_NAME}'), array($make_offer_data["MyList"]["list_name"]), utf8_decode($emailTemp['EmailTemplate']['subject']));
            $this->Email->to = "admin@alisthub.com";
            $this->Email->subject = $subject;
            $this->Email->from = "admin@alisthub.com";
            $this->Email->template = "forgot";
            $this->Email->sendAs = 'html';
            $this->Email->send($data);

            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*     * @Created:     31-July-2014
     * @Method :     sendStatusMail
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for send status email
     * @Param:       make_offer_id
     * @Return:      TRUE/FALSE
     */

    public function sendStatusMail($make_offer_id = NULL) {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $this->loadModel("MakeOffer");
        $this->loadModel("EmailTemplate");
        if ($make_offer_id) {

            $make_offer_data = $this->MakeOffer->find("first", array("conditions" => array("MakeOffer.id" => $make_offer_id), "recursive" => 1));
            $user_detail = $this->User->find("first", array("conditions" => array("User.id" => $make_offer_data["MyList"]["user_id"]), "recursive" => "-1"));

            if ($make_offer_data["MakeOffer"]["status"] == 1) {
                // mail for accepted conditions
                // for user
                $emailTemp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.alias' => "offer-accepted")));
                $emailContent = utf8_decode($emailTemp['EmailTemplate']['description']);

                // for admin
                $emailTempAdmin = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.alias' => "offer-accepted-admin")));
                $emailContentAdmin = utf8_decode($emailTempAdmin['EmailTemplate']['description']);
            } else {
                // mail for decline conditions
                // for user
                $emailTemp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.alias' => "offer-decline")));
                $emailContent = utf8_decode($emailTemp['EmailTemplate']['description']);

                // for admin
                $emailTempAdmin = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.alias' => "offer-decline-admin")));
                $emailContentAdmin = utf8_decode($emailTempAdmin['EmailTemplate']['description']);
            }

            // send to user
            $data = str_replace(array('{USER_NAME}', '{LIST_NAME}', '{DEDICATED_EMAIL_TO_SEND}', '{MULTI_EVENT_To_SEND}', '{TICKET_OFFER_FOR_TRADE}', '{TICKET_VALUE}', '{ADJUSTED_PRICE}'), array($user_detail["User"]["username"], $make_offer_data["MyList"]["list_name"], $make_offer_data["MakeOffer"]["dedicated_email_to_send"], $make_offer_data["MakeOffer"]["multi_event_to_send"], $make_offer_data["MakeOffer"]["ticket_offered_for_trade"], $make_offer_data["MakeOffer"]["ticket_value"], $make_offer_data["MakeOffer"]["adjusted_price"]), $emailContent);
            $this->set('mailData', $data);
            $subject = str_replace(array('{LIST_NAME}'), array($make_offer_data["MyList"]["list_name"]), utf8_decode($emailTemp['EmailTemplate']['subject']));
            $this->Email->to = "sdd.sdei@gmail.com";
            $this->Email->subject = $subject;
            $this->Email->from = "admin@alisthub.com";
            $this->Email->template = "forgot";
            $this->Email->sendAs = 'html';
            $this->Email->send($data);

            // send to admin
            $dataAdmin = str_replace(array('{USER_NAME}', '{LIST_NAME}', '{DEDICATED_EMAIL_TO_SEND}', '{MULTI_EVENT_To_SEND}', '{TICKET_OFFER_FOR_TRADE}', '{TICKET_VALUE}', '{ADJUSTED_PRICE}'), array($user_detail["User"]["username"], $make_offer_data["MyList"]["list_name"], $make_offer_data["MakeOffer"]["dedicated_email_to_send"], $make_offer_data["MakeOffer"]["multi_event_to_send"], $make_offer_data["MakeOffer"]["ticket_offered_for_trade"], $make_offer_data["MakeOffer"]["ticket_value"], $make_offer_data["MakeOffer"]["adjusted_price"]), $emailContentAdmin);
            $this->set('mailData', $dataAdmin);
            $subject = str_replace(array('{LIST_NAME}'), array($make_offer_data["MyList"]["list_name"]), utf8_decode($emailTemp['EmailTemplate']['subject']));
            $this->Email->to = "sdd.sdei@gmail.com";
            $this->Email->subject = $subject;
            $this->Email->from = "admin@alisthub.com";
            $this->Email->template = "forgot";
            $this->Email->sendAs = 'html';
            $this->Email->send($data);

            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*     * @Created:     13-Jun-2014
     * @Method :     admin_listOffer
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for listing requested offer
     * @Param:       none
     * @Return:      none
     */

    public function admin_listOffer() {
        $this->layout = "admin/admin";
        $this->set('title_for_layout', 'ALIST Hub :: Offer List');
        $this->loadModel("MakeOffer");
        $this->set('makeOffers', $this->paginate('MakeOffer'));
    }

    /*     * @Created:     13-Jun-2014
     * @Method :     admin_offerView
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for view requested offer
     * @Param:       $makeOfferId
     * @Return:      none
     */

    public function admin_offerView($makeOfferId = null) {
        $this->set('title_for_layout', 'ALIST Hub :: View Offer');
        $this->loadModel("MakeOffer");
        if ($makeOfferId) {
            $makeOfferId = base64_decode($makeOfferId);
            $data = $this->MakeOffer->find("first", array("conditions" => array("MakeOffer.id" => $makeOfferId)));
            $this->set("data", $data);
        } else {
            $this->Session->setFlash("Direct access not allowed", "default", array("class" => "red"));
            $this->redirect(array("controller" => "Users", "action" => "dashboard"));
        }
    }

    /*     * @Created:     02-Jul-2014
     * @Method :     list_reporting
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     for view list reporting
     * @Param:       $mylist_id
     * @Return:      none
     */

    public function listReporting($mylist_id = null) {
        $this->layout = "front/home";
        $this->loadModel("MyList");
        $this->loadModel("CampaignEmail");
        $this->loadModel("ListEmail");
        $listdetail = $this->MyList->find("first", array("conditions" => array("MyList.id" => base64_decode($mylist_id)), "recursive" => -1));

        $list_id = $listdetail["MyList"]["id"];
        $total_mail = $this->ListEmail->find("count", array("conditions" => array("ListEmail.my_list_id" => $list_id)));
        $sent_mail = $this->CampaignEmail->find("count", array("conditions" => array("CampaignEmail.sent_status" => 1, "CampaignEmail.list_id" => $list_id)));
        $bounce = $total_mail - $sent_mail;
        $open_mail = $this->CampaignEmail->find("count", array("conditions" => array("CampaignEmail.sent_status" => 1, "CampaignEmail.list_id" => $list_id, "CampaignEmail.open_status" => 1)));
        $click_mail = $this->CampaignEmail->find("count", array("conditions" => array("CampaignEmail.sent_status" => 1, "CampaignEmail.list_id" => $list_id, "CampaignEmail.click_status" => 1)));
        $last_send = $this->CampaignEmail->find("first", array("conditions" => array("CampaignEmail.list_id" => $list_id), "fields" => array("CampaignEmail.created"), "order" => "CampaignEmail.created DESC"));

        $listdetail["MyList"]["total_mail"] = $total_mail;
        $listdetail["MyList"]["sent_mail"] = $sent_mail;
        $listdetail["MyList"]["bounce_mail"] = $bounce;
        $listdetail["MyList"]["open_mail"] = $open_mail;
        $listdetail["MyList"]["click_mail"] = $click_mail;
        if (!empty($last_send)) {
            $listdetail["MyList"]["last_send"] = $last_send["CampaignEmail"]["created"];
        }
        $this->set("listdetail", $listdetail);
    }

    /*     * @Created:     03-Jul-2014
     * @Method :     userDetail
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     for Edit user Detail
     * @Param:       $list_id,$mylist_id
     * @Return:      none
     */

    public function listuserDetail($list_id = null, $listemail_id = null) {
        #echo base64_decode($listemail_id); die;
        $this->loadModel("ListEmail");
        $this->layout = "front/home";


        $this->set("listemail", base64_decode($listemail_id));
        $this->set("list_id", base64_decode($list_id));

        if ($this->data) {
        
            //$data['ListEmail'] = $this->data['MyLists'];
            $this->ListEmail->save($this->data);
            $this->Session->setFlash("Updated successful!", "default", array("class" => "green"));
            $this->redirect(array("controller" => "MyLists", "action" => "view/" . $list_id));
        }
        if ($listemail_id) {
            $this->request->data = $this->ListEmail->findById(base64_decode($listemail_id));
        }
    }

    /* @Created:     08-Sep-2014
     * @Method :     importcopy
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     for import email list by Copy/Paste
     * @Param:       $mylist_id
     * @Return:      none
     */

    public function importcopy($mylist_id = null) {
        $this->autoRender = FALSE;
        $this->layout = FALSE;
        $this->loadModel("ListEmail");
        if ($this->data) {
          
            $listEmail = explode(",", $this->data['MyList']['copy_email_list']);
            foreach ($listEmail as $listEmails) {

                $data["ListEmail"]["id"] = "";
                $data["ListEmail"]["email"] = $listEmails;
                $data["ListEmail"]["from"] = 2;
                $data["ListEmail"]["my_list_id"] = $this->data['MyList']['id'];
                $this->ListEmail->save($data);
            }

            $count = count($listEmail);
            $this->Session->setFlash("$count contacts has been imported successfully", "default", array("class" => "green"));
            $this->redirect($this->referer());
        }
    }

    /* @Created:     03-Oct-2014
     * @Method :     importForList
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for import email list from all posible options
     * @Param:       $mylist_id
     * @Return:      none
     */

    public function importForList($mylist_id) {
        $this->layout = "front/home";
        if ($mylist_id) {
            $mylist_id = base64_decode($mylist_id);
            $this->set("mylist_id", $mylist_id);
        } else {
            $this->redirect(array("controller" => "Users", "action" => "index"));
        }
    }

    /* @Created:     07-Oct-2014
     * @Method :     segment
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for create segments
     * @Param:       $mylist_id
     * @Return:      none
     */

    public function segment($mylist_id = NULL) {
        $this->layout = "front/home";
        if ($mylist_id) {
            $mylist_id = base64_decode($mylist_id);
            $this->set("mylist_id", $mylist_id);
        }
    }

    /* @Created:     07-Oct-2014
     * @Method :     selectItem
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for select item and provide associated contains
     * @Param:       $item
     * @Return:      none
     */

    public function selectItem($item = NULL) {
        $this->layout = FALSE;
        $this->set("item", $item);
    }

    /* @Created:     07-Oct-2014
     * @Method :     selectContain
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for select item and conatin to provide associated text
     * @Param:       $item
     * @Return:      none
     */

    public function selectContain($item = NULL, $contain = NULL) {
        $this->layout = FALSE;
        $this->set("item", $item);
        $this->set("contain", $contain);
        $this->loadModel("Campaign");
        if ($item == "campaign_activity") {
            if (stristr($contain, 'sent') === FALSE) {
                // for recent campaign
                $campaigns = $this->Campaign->find("list", array("conditions" => array("Campaign.user_id" => AuthComponent::user("id"), "Campaign.subject_line !=" => ""), "order" => "Campaign.created DESC", "fields" => array("Campaign.id", "Campaign.subject_line")));
                $this->set("campaigns", $campaigns);
            } else {
                // for sent campaign
                $campaigns = $this->Campaign->find("list", array("conditions" => array("Campaign.user_id" => AuthComponent::user("id"), "Campaign.subject_line !=" => "", "Campaign.mail_status" => 2), "order" => "Campaign.created DESC", "fields" => array("Campaign.id", "Campaign.subject_line")));
                $this->set("campaigns", $campaigns);
            }
        } else if ($item == "date_Added") {
            if (stristr($contain, 'campaign') === FALSE) {
                $this->set("date", "date");
            } else {
                // for recent campaign
                $campaigns = $this->Campaign->find("list", array("conditions" => array("Campaign.user_id" => AuthComponent::user("id"), "Campaign.subject_line !=" => "", "Campaign.mail_status" => 2), "order" => "Campaign.created DESC", "fields" => array("Campaign.id", "Campaign.subject_line")));
                $this->set("campaigns", $campaigns);
            }
        } else if ($item == "info_changed") {
            if (stristr($contain, 'campaign') === FALSE) {
                $this->set("date", "date");
            } else {
                // for recent campaign
                $campaigns = $this->Campaign->find("list", array("conditions" => array("Campaign.user_id" => AuthComponent::user("id"), "Campaign.subject_line !=" => ""), "order" => "Campaign.created DESC", "fields" => array("Campaign.id", "Campaign.subject_line")));
                $this->set("campaigns", $campaigns);
            }
        } else if ($item == "location") {
            if (stristr($contain, 'within') != FALSE) {
                $this->set("location", "location");
            } else if (stristr($contain, 'country') != FALSE) {
                $this->set("country", "country");
            } else if (stristr($contain, 'is in US state') != FALSE) {
                $this->set("us", "us");
            } else if (stristr($contain, 'is within distance of zip') != FALSE) {
                $this->set("location", "location");
            } else if (stristr($contain, 'zip') != FALSE) {
                $this->set("zip", "zip");
            } else if (stristr($contain, 'is unknown') != FALSE) {
                $this->set("unknown", "unknown");
            }
        } else if ($item == "member_rating") {
            
        }
    }

    /* @Created:     08-Oct-2014
     * @Method :     previewSegment
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for preview segment according to conditions
     * @Param:       $my_list_id, $item, $contain, $textvalue
     * @Return:      none
     */

    public function previewSegment($my_list_id = NULL, $item = NULL, $contain = NULL, $textvalue = NULL, $zip_value = NULL) {
        $this->layout = FALSE;
        $this->loadModel("Campaign");
        $this->loadModel("CampaignEmail");
        $this->loadModel("ListEmail");
        $this->loadModel("Zip");
        if ($item == "campaign_activity") {
            //echo stristr($contain, 'djkfjkl');die;
            if (stristr($contain, 'Open') != FALSE) {
                $campaign_id = $textvalue;
                $emails = $this->CampaignEmail->find("list", array("conditions" => array("CampaignEmail.campaign_id" => $campaign_id, "CampaignEmail.list_id" => $my_list_id, "CampaignEmail.open_status" => 1), "fields" => array("CampaignEmail.id", "CampaignEmail.email")));
                $this->Set("emails", $emails);

            } else if (stristr($contain, 'Click') != FALSE) {
                $campaign_id = $textvalue;
                $emails = $this->CampaignEmail->find("list", array("conditions" => array("CampaignEmail.campaign_id" => $campaign_id, "CampaignEmail.list_id" => $my_list_id, "CampaignEmail.click_status" => 1), "fields" => array("CampaignEmail.id", "CampaignEmail.email")));
                $this->set("emails", $emails);

            } else if (stristr($contain, 'was sent') != FALSE) {
                $campaign_id = $textvalue;
                $emails = $this->CampaignEmail->find("list", array("conditions" => array("CampaignEmail.campaign_id" => $campaign_id, "CampaignEmail.list_id" => $my_list_id, "CampaignEmail.sent_check" => 1), "fields" => array("CampaignEmail.id", "CampaignEmail.email")));
                $this->set("emails", $emails);

            }
            if (stristr($contain, 'not Open') != FALSE) {
                $campaign_id = $textvalue;
                $emails = $this->CampaignEmail->find("list", array("conditions" => array("CampaignEmail.campaign_id" => $campaign_id, "CampaignEmail.list_id" => $my_list_id, "CampaignEmail.open_status" => 0), "fields" => array("CampaignEmail.id", "CampaignEmail.email")));
                $this->set("emails", $emails);

            } else if (stristr($contain, 'was not sent Sent') != FALSE) {
                $campaign_id = $textvalue;
                $emails = $this->CampaignEmail->find("list", array("conditions" => array("CampaignEmail.campaign_id" => $campaign_id, "CampaignEmail.list_id" => $my_list_id, "CampaignEmail.sent_check" => 0), "fields" => array("CampaignEmail.id", "CampaignEmail.email")));
                $this->set("emails", $emails);

            } else {
                $campaign_id = $textvalue;
                $emails = $this->CampaignEmail->find("list", array("conditions" => array("CampaignEmail.campaign_id" => $campaign_id, "CampaignEmail.list_id" => $my_list_id, "CampaignEmail.click_status" => 0), "fields" => array("CampaignEmail.id", "CampaignEmail.email")));
                $this->set("emails", $emails);

            }
        } else if ($item == "date_Added") {

            if (stristr($contain, 'specific date') != FALSE) {
                $textvalue = str_replace("-", "/", $textvalue);
                $a = strtotime($textvalue);
                $date_to_send = date("Y-m-d", $a);
            } else {
                $campaign_id = $textvalue;
                $campaign = $this->Campaign->find("first", array("conditions" => array("Campaign.id" => $campaign_id), "recursive" => -1, "fields" => array("Campaign.date_to_send")));
                $date_to_send = $campaign["Campaign"]["date_to_send"];
                $textvalue = str_replace("-", "/", $textvalue);
                $a = strtotime($date_to_send);
                $date_to_send = date("Y-m-d", $a);
            }

            if (stristr($contain, 'after') != FALSE) {
                $emails = $this->ListEmail->find("list", array("conditions" => array("ListEmail.created >" => $date_to_send), "fields" => array("ListEmail.id", "ListEmail.email")));
            } else if (stristr($contain, 'before') != FALSE) {
                $emails = $this->ListEmail->find("list", array("conditions" => array("ListEmail.created <" => $date_to_send), "fields" => array("ListEmail.id", "ListEmail.email")));
            } else {
                $emails = $this->ListEmail->find("list", array("conditions" => array("ListEmail.created " => $date_to_send), "fields" => array("ListEmail.id", "ListEmail.email")));
            }

            $this->set("emails", $emails);
        } else if ($item == "info_changed") {
            if (stristr($contain, 'specific date') != FALSE) {

                $textvalue = str_replace("-", "/", $textvalue);
                $date_to_send = date("Y-m-d", strtotime($textvalue));
            } else {
                $campaign_id = $textvalue;
                $campaign = $this->Campaign->find("first", array("conditions" => array("Campaign.id" => $campaign_id), "recursive" => -1, "fields" => array("Campaign.date_to_send")));
                $date_to_send = $campaign["Campaign"]["date_to_send"];
                $textvalue = str_replace("-", "/", $textvalue);
                $a = strtotime($date_to_send);
                $date_to_send = date("Y-m-d", $a);
            }

            if (stristr($contain, 'after') != FALSE) {
                $emails = $this->ListEmail->find("list", array("conditions" => array("ListEmail.modified >" => $date_to_send), "fields" => array("ListEmail.id", "ListEmail.email")));
            } else if (stristr($contain, 'before') != FALSE) {
                $emails = $this->ListEmail->find("list", array("conditions" => array("ListEmail.modified <" => $date_to_send), "fields" => array("ListEmail.id", "ListEmail.email")));
            } else { //echo $date_to_send;
                $emails = $this->ListEmail->find("list", array("conditions" => array("ListEmail.modified " => $date_to_send), "fields" => array("ListEmail.id", "ListEmail.email")));
            }

            $this->set("emails", $emails);
        } else if ($item == "location") {
            if (stristr($contain, 'is within distance of zip') != FALSE) {
                $zip_detail = $this->Zip->findByZip($zip_value);
                $lat = $zip_detail["Zip"]["lat"];
                $lng = $zip_detail["Zip"]["lng"];
                $find_zip = $this->findzip($textvalue, $lat, $lng); //pr($find_zip);
                $find_zip[$zip_detail["Zip"]["id"]] = $zip_detail["Zip"]["zip"];
                $cities = $this->Zip->find("list", array("conditions" => array("Zip.zip" => $find_zip), "fields" => array("Zip.id", "Zip.city")));
                $campaign_id = $this->Campaign->find("list", array("fields" => array("Campaign.id", "Campaign.id"), 'conditions' => array("Campaign.user_id" => AuthComponent::user("id"))));
                $emails = $this->CampaignEmail->find("list", array("conditions" => array("CampaignEmail.city" => $cities, "CampaignEmail.campaign_id" => $campaign_id), "fields" => array("CampaignEmail.id", "CampaignEmail.email")));

            } else if (stristr($contain, 'within') != FALSE) {
                $zip_detail = $this->Zip->findByZip($zip_value);
                $lat = $zip_detail["Zip"]["lat"];
                $lng = $zip_detail["Zip"]["lng"];
                $find_zip = $this->findzip($textvalue, $lat, $lng); //pr($find_zip);
                $find_zip[$zip_detail["Zip"]["id"]] = $zip_detail["Zip"]["zip"];
                $cities = $this->Zip->find("list", array("fields" => array("Zip.zip", "Zip.city"), "conditions" => array("Zip.zip" => $find_zip)));
                $campaign_id = $this->Campaign->find("list", array("fields" => array("Campaign.id", "Campaign.id"), 'conditions' => array("Campaign.user_id" => AuthComponent::user("id"))));
                if (stristr($contain, 'is within') != FALSE) {
                    $emails = $this->CampaignEmail->find("list", array("conditions" => array("CampaignEmail.city" => $cities, "CampaignEmail.campaign_id" => $campaign_id), "fields" => array("CampaignEmail.id", "CampaignEmail.email")));
                } else if (stristr($contain, 'is not within')) {
                    $emails = $this->CampaignEmail->find("list", array("conditions" => array("CampaignEmail.city !=" => $cities, "CampaignEmail.campaign_id" => $campaign_id), "fields" => array("CampaignEmail.id", "CampaignEmail.email")));
                }

            } else if (stristr($contain, 'country') != FALSE) {
                $campaign_id = $this->Campaign->find("list", array("fields" => array("Campaign.id", "Campaign.id"), 'conditions' => array("Campaign.user_id" => AuthComponent::user("id"))));
                if (stristr($contain, 'is in country') != FALSE) {
                    $emails = $this->CampaignEmail->find("list", array("conditions" => array("CampaignEmail.country " => $textvalue, "CampaignEmail.campaign_id" => $campaign_id), "fields" => array("CampaignEmail.id", "CampaignEmail.email")));
                } else {
                    $emails = $this->CampaignEmail->find("list", array("conditions" => array("CampaignEmail.country !=" => $textvalue, "CampaignEmail.campaign_id" => $campaign_id), "fields" => array("CampaignEmail.id", "CampaignEmail.email")));
                }

            } else if (stristr($contain, 'is in US state') != FALSE) {
                $campaign_id = $this->Campaign->find("list", array("fields" => array("Campaign.id", "Campaign.id"), 'conditions' => array("Campaign.user_id" => AuthComponent::user("id"))));
                $emails = $this->CampaignEmail->find("list", array("conditions" => array("CampaignEmail.country " => "US", "CampaignEmail.campaign_id" => $campaign_id), "fields" => array("CampaignEmail.id", "CampaignEmail.email")));

            } else if (stristr($contain, 'is zip') != FALSE) {
                $campaign_id = $this->Campaign->find("list", array("fields" => array("Campaign.id", "Campaign.id"), 'conditions' => array("Campaign.user_id" => AuthComponent::user("id"))));
                $zip_detail = $this->Zip->findByZip($textvalue);
                $emails = $this->CampaignEmail->find("list", array("conditions" => array("CampaignEmail.city" => $zip_detail["Zip"]["city"], "CampaignEmail.campaign_id" => $campaign_id), "fields" => array("CampaignEmail.id", "CampaignEmail.email")));

            } else if (stristr($contain, 'is not zip') != FALSE) {
                $campaign_id = $this->Campaign->find("list", array("fields" => array("Campaign.id", "Campaign.id"), 'conditions' => array("Campaign.user_id" => AuthComponent::user("id"))));
                $zip_detail = $this->Zip->findByZip($textvalue);
                $emails = $this->CampaignEmail->find("list", array("conditions" => array("CampaignEmail.city !=" => $zip_detail["Zip"]["city"], "CampaignEmail.campaign_id" => $campaign_id), "fields" => array("CampaignEmail.id", "CampaignEmail.email")));

            } else if (stristr($contain, 'is unknown') != FALSE) {
                $campaign_id = $this->Campaign->find("list", array("fields" => array("Campaign.id", "Campaign.id"), 'conditions' => array("Campaign.user_id" => AuthComponent::user("id"))));
                $emails = $this->CampaignEmail->find("list", array("conditions" => array("CampaignEmail.city" => NULL, "CampaignEmail.campaign_id" => $campaign_id), "fields" => array("CampaignEmail.id", "CampaignEmail.email")));

            }
        }
        $this->set("emails", $emails);
    }

    /* @Created:     08-Oct-2014
     * @Method :     savSegment
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for save segment according to conditions
     * @Param:       none
     * @Return:      none
     */

    public function saveSegment() {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        if ($this->data) {
            $this->loadModel("Segment");
            $this->loadModel("SegmentEmail");
            $segment["Segment"]["id"] = $this->data["MyList"]["id"];
            $segment["Segment"]["my_list_id"] = $this->data["MyList"]["myListId"];
            $segment["Segment"]["name"] = $this->data["MyList"]["name"];
            $segment["Segment"]["user_id"] = AuthComponent::user("id");
            $this->Segment->save($segment);
            if (!empty($this->data["MyList"]["id"])) {
                $segment_id = $this->data["MyList"]["id"];
                // this is case of edit so we need to delete all old one first
                $this->SegmentEmail->deleteAll(array('SegmentEmail.segment_id' => $segment_id), false);
            } else {
                $segment_id = $this->Segment->getLastInsertID();
            }
            foreach ($this->data["SegmentEmail"]["email"] as $email) {
                $segmentEmail["SegmentEmail"]["id"] = "";
                $segmentEmail["SegmentEmail"]["segment_id"] = $segment_id;
                $segmentEmail["SegmentEmail"]["email"] = $email;
                $this->SegmentEmail->save($segmentEmail);
            }
        }
        $this->Session->setFlash("Segment Saved successfully.", "default", array("class" => "green"));
        $this->redirect(array("controller" => "MyLists", "action" => "segmentList", base64_encode($this->data["MyList"]["myListId"])));
    }

    /* @Created:     08-Oct-2014
     * @Method :     segmentList
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for list segment according to conditions
     * @Param:       $my_list_id
     * @Return:      none
     */

    public function segmentList($my_list_id = NULL) {
        $this->layout = "front/home";
        $this->loadMOdel("Segment");
        $conditions = array("Segment.my_list_id" => base64_decode($my_list_id));
        $this->paginate = array('conditions' => $conditions, 'limit' => 20, "recursive" => -1, 'order' => array('Segment.created' => 'DESC'));
        $this->set("mylist_id", base64_decode($my_list_id));
        $this->set("datas", $this->paginate('Segment'));
    }

    /* @Created:     08-Oct-2014
     * @Method :     deleteSegment
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for delete segment according to conditions
     * @Param:       $segment_id
     * @Return:      none
     */

    public function deleteSegment($segment_id = NULL) {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        if ($segment_id) {
            $segment_id = base64_decode($segment_id);
            $this->loadModel("Segment");
            if ($this->Segment->delete($segment_id)) {
                $this->Session->setFlash("Segment deleted successfully.", "default", array("class" => "green"));
            } else {
                $this->Session->setFlash("Unable to delete segment, Try again.", "default", array("class" => "red"));
            }
        }
        $this->redirect($this->referer());
    }

    /* @Created:     08-Oct-2014
     * @Method :     editSegment
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for edit segment according to conditions
     * @Param:       $segment_id
     * @Return:      none
     */

    public function editSegment($segment_id = NULL) {
        $this->layout = "front/home";
        $this->loadModel("Segment");
        if ($segment_id) {
            $segment_id = base64_decode($segment_id);
            $segment = $this->Segment->findById($segment_id);
            $this->set("segment", $segment);
        }
    }

    /* @Created:     27-Oct-2014
     * @Method :     replicate
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for replicate list according to conditions
     * @Param:       $list_id
     * @Return:      none
     */

    public function replicate($list_id = NULL) {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $this->loadModel("MyList");
        $this->loadModel("ListEmail");
        if ($list_id) {
            $list_id = base64_decode($list_id);
            $listDetail = $this->MyList->findById($list_id);
            $listDetail["MyList"]["id"] = "";
            unset($listDetail["MyList"]["created"]);
            unset($listDetail["MyList"]["modified"]);
            $listDetail["MyList"]["list_name"] = $listDetail["MyList"]["list_name"] . "_copy";
            $this->MyList->save($listDetail);
            $myListID = $this->MyList->getLastInsertID();
            $listEmailS = $this->ListEmail->find("all", array("conditions" => array("ListEmail.my_list_id" => $list_id), "recursive" => -1));
            foreach ($listEmailS as $listEmail) {
                $listEmail["ListEmail"]["id"] = "";
                $listEmail["ListEmail"]["my_list_id"] = $myListID;
                $listEmail["ListEmail"]["from"] = 3;
                unset($listEmail["ListEmail"]["created"]);
                unset($listEmail["ListEmail"]["modified"]);
                $this->ListEmail->save($listEmail);
            }
        }
        $this->redirect($this->referer());
    }

    /* @Created:     28-Oct-2014
     * @Method :     cronSetEmailInfo
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     cron job for set regions categories and vibes
     * @Param:       none
     * @Return:      none
     */

    public function cronSetEmailInfo() {
        ini_set('max_execution_time', 2000);
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $this->loadModel("MyList");
        $this->loadModel("ListEmail");
        $this->loadModel("CampaignEmail");
        $this->loadModel("User");
        $this->loadModel("UserpCategory");
        $this->loadModel("UserpRegion");
        $this->loadModel("UserpVibe");
        $this->loadModel("Category");
        $this->loadModel("Vibe");
        $this->loadModel("Region");
        $this->loadModel("CampaignEvent");
        $this->loadModel("EventCategory");
        $this->loadModel("EventVibe");

        // first lets find out all mylist_ids
        $myListIds = $this->MyList->find("list", array("fields" => array("MyList.id", "MyList.id")));
        // now for each mylist_id we will find there regions categories and vibes
        $j = 0;
        foreach ($myListIds as $myListId) {
            // now find out every email for each email list
            $emails = $this->ListEmail->find("list", array("conditions" => array("ListEmail.my_list_id" => $myListId), "fields" => array("ListEmail.id", "ListEmail.email")));

            foreach ($emails as $key => $email) {
                // lets first check if user is registerd user from user table
                $userDetail = $this->User->find("first", array("conditions" => array("User.email" => $email), "fields" => array("User.email")));
                if (!empty($userDetail)) {
                    // here we got userdetail :) so directly find regions category and vibes and save directly from here
                    // lets first find categories
                    $categoriesList = $this->UserpCategory->find("list", array("conditions" => array("UserpCategory.user_id" => $userDetail["User"]["id"]), "fields" => array("UserpCategory.id", "UserpCategory.category_id")));
                    if (!empty($categoriesList)) {
                        $categories = $this->Category->find("list", array("conditions" => array("Category.id" => $categoriesList), "fields" => array("Category.id", "Category.name")));
                        $category = implode(",", $categories);
                    } else {
                        $category = "";
                    }

                    // now find vibes
                    $vibesList = $this->UserpVibe->find("list", array("conditions" => array("UserpVibe.user_id" => $userDetail["User"]["id"]), "fields" => array("UserpVibe.id", "UserpVibe.vibe_id")));
                    if (!empty($vibesList)) {
                        $vibes = $this->Vibe->find("list", array("conditions" => array("Vibe.id" => $vibesList), "fields" => array("Vibe.id", "Vibe.name")));
                        $vibe = implode(",", $vibes);
                    } else {
                        $vibe = "";
                    }

                    // now find regions
                    $regionsList = $this->UserpRegion->find("list", array("conditions" => array("UserpRegion.user_id" => $userDetail["User"]["id"]), "fields" => array("UserpRegion.id", "UserpRegion.region_id")));
                    if (!empty($regionsList)) {
                        $regions = $this->Region->find("list", array("conditions" => array("Region.id" => $regionsList), "fields" => array("Region.id", "Region.name")));
                        $region = implode(",", $regions);
                    } else {
                        $region = "";
                    }

                    // now save these categories, vibes and regions
                    $data["ListEmail"]["id"] = $key;
                    $data["ListEmail"]["categories"] = $category;
                    $data["ListEmail"]["vibes"] = $vibe;
                    $data["ListEmail"]["regions"] = $region;
                    $this->ListEmail->save($data);
                    // for testing only
                    $arr[$key] = $data;
                } else {
                    // now need to find out another way :(
                    // collect email address which were not our user

                    $otherEmails[$j]["ListEmail"]["id"] = $key;
                    $otherEmails[$j]["ListEmail"]["email"] = $email;
                    // now get there regions
                    $regn = $this->CampaignEmail->findByEmail($email);
                    if (!empty($regn)) {
                        $otherEmails[$j]["ListEmail"]["regions"] = $regn["CampaignEmail"]["region"];
                    }
                    $j++;
                }
            }
            // now below code for other emails information
            // now check how many campaign sent using this list id
            $campaigns = $this->CampaignEmail->find("list", array("conditions" => array("CampaignEmail.list_id" => $myListId), "fields" => array("CampaignEmail.campaign_id", "CampaignEmail.campaign_id")));
            if (!empty($campaigns)) {
                // find events for campaign if exist
                $events = $this->CampaignEvent->find("list", array("conditions" => array("CampaignEvent.campaign_id" => $campaigns), "fields" => array("CampaignEvent.event_id", "CampaignEvent.event_id")));

                $categoriesList = $this->EventCategory->find("list", array("conditions" => array("EventCategory.event_id" => $events), "fields" => array("EventCategory.id", "EventCategory.category_id")));
                if (!empty($categoriesList)) {
                    $categories = $this->Category->find("list", array("conditions" => array("Category.id" => $categoriesList), "fields" => array("Category.id", "Category.name")));
                    $category = implode(",", $categories);
                } else {
                    $category = "";
                }

                // now find vibes
                $vibesList = $this->EventVibe->find("list", array("conditions" => array("EventVibe.event_id" => $events), "fields" => array("EventVibe.id", "EventVibe.vibe_id")));
                if (!empty($vibesList)) {
                    $vibes = $this->Vibe->find("list", array("conditions" => array("Vibe.id" => $vibesList), "fields" => array("Vibe.id", "Vibe.name")));
                    $vibe = implode(",", $vibes);
                } else {
                    $vibe = "";
                }
                // now update category and vibes for otheremail

                foreach ($otherEmails as $oe) {
                    $oe["ListEmail"]["id"] = $oe["ListEmail"]["id"];
                    $oe["ListEmail"]["categories"] = $category;
                    $oe["ListEmail"]["vibes"] = $vibe;
                    $this->ListEmail->save($oe);
                    $test_data[] = $oe;
                }
            }
        }
        
        die;
    }

     /* @Created:     22-Dec-2014
     * @Method :     userDetail
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     for Viewt user Detail
     * @Param:       $list_id,$mylist_id
     * @Return:      none
     */

    public function userDetail($list_id = null, $listemail_id = null) {
        #echo base64_decode($listemail_id); die;
        $this->loadModel("ListEmail");
        $this->layout = "front/home";


        $this->set("listemail", base64_decode($listemail_id));
        $this->set("list_id", base64_decode($list_id));

        
        if ($listemail_id) {
            $listUserDetail = $this->ListEmail->findById(base64_decode($listemail_id));
            $this->set("listUserDetail", $listUserDetail);
        }
    }
    
     /*@Created:     15-Jan-2015
     * @Method :     admin_addContact
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     for Add New user Detail in mylist (admin end)
     * @Param:       $list_id,$mylist_id
     * @Return:      none
     */

    public function admin_addContact($list_id = null, $listemail_id = null) {
        #echo base64_decode($listemail_id); die;
        $this->set('title_for_layout', 'ALIST Hub :: Add Contact');
        $this->loadModel("ListEmail");
        $this->layout = "admin/admin";


        $this->set("listemail", base64_decode($listemail_id));
        $this->set("list_id", base64_decode($list_id));

        if ($this->data) {
           
            //$data['ListEmail'] = $this->data['MyLists'];
            $this->ListEmail->save($this->data);
            $this->Session->setFlash("Email Added successfully", "default", array("class" => "green"));
            $this->redirect(array("controller" => "MyLists", "action" => "listView/" . $list_id));
        }
        if ($listemail_id) {
            $this->request->data = $this->ListEmail->findById(base64_decode($listemail_id));
        }
    }
    
    /* @Created:     15-Jan-2015
     * @Method :     admin_importForList
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     for import email list from all posible options
     * @Param:       $mylist_id
     * @Return:      none
     */

    public function admin_importForList($mylist_id) {
        $this->layout = "admin/admin";
        if ($mylist_id) {
            $mylist_id = base64_decode($mylist_id);
            $this->set("mylist_id", $mylist_id);
        } else {
            $this->redirect(array("controller" => "Users", "action" => "index"));
        }
    }
    
    /* @Created:     15-Jan-2015
     * @Method :     admin_importcopy
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     for import email list by Copy/Paste (Admin End)
     * @Param:       $mylist_id
     * @Return:      none
     */

    public function admin_importcopy($mylist_id = null) {
        $this->autoRender = FALSE;
        $this->layout = FALSE;
        $this->loadModel("ListEmail");
        if ($this->data) {
          
            $listEmail = explode(",", $this->data['MyList']['copy_email_list']);
            foreach ($listEmail as $listEmails) {

                $data["ListEmail"]["id"] = "";
                $data["ListEmail"]["email"] = $listEmails;
                $data["ListEmail"]["from"] = 2;
                $data["ListEmail"]["my_list_id"] = $this->data['MyList']['id'];
                $this->ListEmail->save($data);
            }

            $count = count($listEmail);
            $this->Session->setFlash("$count contacts has been imported successfully", "default", array("class" => "green"));
            $this->redirect(array("controller" => "MyLists", "action" => "listView", base64_encode($this->data["MyList"]["id"])));
        }
    }
}
