<?php

App::uses('AppController', 'Controller');

class CmsPagesController extends AppController {

    public $name = 'CmsPages';
    var $helpers = array('Html', 'Form', 'Cache');
    public $components = array('Email', 'Upload');
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
        $this->Auth->allow('cmsPage', 'changeOrder', 'contactUs');
    }

    /** @Created:     30-April-2014
     * @Method :     admin_add
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     Add Cms Page
     * @Param:       none
     * @Return:      none
     */
    function admin_add($id = NULL) {
        $this->layout = 'admin/admin';
        $this->set('title_for_layout', 'ALIST Hub :: Add Cms Page');
        $this->loadModel('CmsPage');
        $cmsData = $this->CmsPage->find('list', array('conditions' => array('CmsPage.status' => 1), 'order' => "CmsPage.title ASC"));
       
        $this->set('cmsData', $cmsData);
        if (!empty($this->data)) {
        if(empty($this->data['CmsPage']["id"]))
           {
               $upMsg = "Added Successfully"; 
           }
        else
           {
               $upMsg = "Updated Successfully";
           }
            $this->CmsPage->save($this->data);
            $this->Session->setFlash($upMsg, 'default', array('class' => 'green'));
            $this->redirect(array('controller' => 'CmsPages', 'action' => 'list'));
        }
        if ($id) {
            $id = base64_decode($id);
            $this->request->data = $this->CmsPage->findById($id);
        }
    }

    /** @Created:     30-April-2014
     * @Method :     admin_list
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     Cms Page Listing
     * @Param:       none
     * @Return:      none
     */
    function admin_list() {
        $this->layout = 'admin/admin';
        $this->set('title_for_layout', 'ALIST Hub :: Cms Pages');
        $this->loadModel('CmsPage');
        $Cmsdata = $this->CmsPage->find('all',array("order" => "CmsPage.id DESC"));
        $this->set('Cmsdata', $Cmsdata);
    }

    /** @Created:     30-April-2014
     * @Method :     admin_emailList
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     Email Template Listing
     * @Param:       none
     * @Return:      none
     */
    function admin_emailList() {
        $this->layout = 'admin/admin';
        $this->set('title_for_layout', 'ALIST Hub :: Email Template');
        $this->loadModel('EmailTemplate');
        $Emaildata = $this->EmailTemplate->find('all',array("order" => "EmailTemplate.id DESC"));
        $this->set('Emaildata', $Emaildata);
    }

    /** @Created:     30-April-2014
     * @Method :     admin_editEmailTemplate
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     Edit Email Template
     * @Param:       none
     * @Return:      none
     */
    function admin_editEmailTemplate($id = NULL) {
        $this->layout = 'admin/admin';
        $this->set('title_for_layout', 'ALIST Hub :: Edit Email Template');
        $this->loadModel('EmailTemplate');
        if (!empty($this->data)) {
             if(empty($this->data['EmailTemplate']["id"]))
           {
               $upMsg = "Added Successfully"; 
           }
        else
           {
               $upMsg = "Updated Successfully";
           }
            $this->EmailTemplate->save($this->data);
            $this->Session->setFlash($upMsg, 'default', array('class' => 'green'));
            $this->redirect(array('controller' => 'CmsPages', 'action' => 'emailList'));
        }
        if ($id) {
            $id = base64_decode($id);
            $this->request->data = $this->EmailTemplate->findById($id);
        }
    }

    /** @Created:     30-May-2014
     * @Method :     cmsPage
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     show Cms Pages
     * @Param:       title
     * @Return:      none
     */
    public function cmsPage($title = null) {

        $this->layout = 'front/home';
        $page = $this->CmsPage->find('first', array('conditions' => array('CmsPage.slug' => trim($title))));
        if (isset($page) && !empty($page)) {
            $this->set('title_for_layout', 'ALIST Hub :: ' . $page['CmsPage']['title']);
        }
        $this->set('page', $page);
        
    }

    /**
     * @Created:        30-May-2014
     * @Method :        changeOrder
     * @Author: 	Prateek Jadhav
     * @Modified :	
     * @Purpose:   	Function to change the order of banners
     * @Param:     	$order, $id, $type, $old_order
     * @Return:    	none
     */
    public function changeOrder($order = null, $id = null, $type = null, $old_order) {
        $this->layout = "ajax";
        $this->autoRender = false;
        $this->loadModel("Banner");
        $second_banner = $this->Banner->find("first", array("conditions" => array("Banner.type" => $type, "Banner.order" => $order)));
        $first_banner["Banner"]["id"] = $id;
        $first_banner["Banner"]["order"] = $order;
        $return = 0;
        if ($this->Banner->save($first_banner)) {
            $return = 1;
        } else
            $return = 2;

        $second_banner["Banner"]["order"] = $old_order;
        if ($this->Banner->save($second_banner)) {
            $return = 1;
        } else
            $return = 2;

        return $return;
    }

    /**
     * @Created:        02-June-2014
     * @Method :        contactUs
     * @Author: 	Sachin Thakur
     * @Modified :	
     * @Purpose:   	Function for contact user to the site owner
     * @Param:     	none
     * @Return:    	none
     */
    public function contactUs() {
        $this->layout = 'front/home';
        $this->set('title_for_layout', 'ALIST Hub :: Contact Us');
        if ($this->data) {
            $this->loadModel("EmailTemplate");

            $emailTemp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.alias' => "contact-us")));
            $emailContent = utf8_decode($emailTemp['EmailTemplate']['description']);
            $data = str_replace(array('{Name}', '{Email}', '{Subject}', '{Message}'), array($this->data['CmsPage']['name'], $this->data['CmsPage']['email'], $this->data['CmsPage']['subject'], $this->data['CmsPage']['message']), $emailContent);
            $this->set('mailData', $data);
            $this->Email->to = "sdd.sdei@gmail.com";
            $this->Email->subject = $this->data['CmsPage']['email'];
            $this->Email->from = "alisthub@admin.com";
            $this->Email->template = "forgot";
            $this->Email->sendAs = 'html';
            if ($this->Email->send($data)) {
                $emailTemp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.alias' => "contact-us-admin")));
                $emailContent = utf8_decode($emailTemp['EmailTemplate']['description']);
                $data = str_replace(array('{Name}', '{Email}', '{Subject}', '{Message}'), array($this->data['CmsPage']['name'], $this->data['CmsPage']['email'], $this->data['CmsPage']['subject'], $this->data['CmsPage']['message']), $emailContent);
                $this->set('mailData', $data);
                $this->Email->to = "sdd.sdei@gmail.com";
                $this->Email->subject = $this->data['CmsPage']['email'];
                $this->Email->from = "alisthub@admin.com";
                $this->Email->template = "forgot";
                $this->Email->sendAs = 'html';
                $this->Email->send($data);
            }
            $this->set("message", "Thank you for contact us, We will get back to you soon.");
        } else {
            $this->set("message", "");
        }
    }

    // all new banner concept
    /**
     * @Created:        30-May-2014
     * @Method :        admin_addBanner
     * @Author: 	Prateek Jadhav
     * @Modified :	
     * @Purpose:   	Function to add/edit the banner for wordpress
     * @Param:     	$id
     * @Return:    	none
     */
    public function admin_addBanner() {
        $this->layout = "admin/admin";
        $this->set('title_for_layout', 'ALIST Hub :: Banners');
        $this->loadModel("Banner");
        $this->loadModel('BannerImage');
        #Start Code :: To fetch User / Brand
        $this->loadModel("User");
        $users = $this->User->find('list', array('conditions' => array('User.role_id' => 4), 'fields' => array('User.id', 'User.username'), 'order' => "User.username ASC"));
        $this->set('users', $users);
        #End Code :: To fetch User / Brand
        #Start Code :: To fetch Events
        $this->loadModel("Event");
        $events = $this->Event->find('list', array('conditions' => array(''), 'fields' => array('Event.id', 'Event.title'), 'order' => "Event.title ASC"));
        $this->set('events', $events);
        #End Code :: To fetch User / Brand

        if ($this->data) {
            $tmp = $this->data;
           
            #save flyer_image
            if ($tmp['BannerImage']['image_name']["name"]) {

                $imgNameExt = pathinfo($tmp['BannerImage']['image_name']["name"]);
                $ext = $imgNameExt['extension'];
                $ext = strtolower($ext);
                if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {

                    $size = getimagesize($tmp["BannerImage"]["image_name"]["tmp_name"]);
                    $width = $size[0];
                    $height = $size[1];

                    $newImgName = 'banner_' . time();
                    $tempFile = $tmp['BannerImage']['image_name']['tmp_name'];
                    $destThumb = realpath('../webroot/img/Banner/') . '/';
                    $file = $tmp['BannerImage']['image_name'];

                    $file['name'] = $imgNameExt['filename'] . '.' . $ext;

                 
                    $width_height = explode("*", trim($this->data["Banner"]["size"]));
                    $set_width = $width_height[0];
                    $set_height = $width_height[1];
                    if ($ext == 'gif') {
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array($set_width, $set_height)));
                        $name = $newImgName . ".gif";
                    } else if ($ext == 'png') {

                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array($set_width, $set_height)));

                        $name = $newImgName . ".png";
                    } else if ($ext == 'jpeg') {
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array($set_width, $set_height)));
                        $name = $newImgName . ".jpeg";
                    } else {
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array($set_width, $set_height)));
                        $name = $newImgName . ".jpg";
                    }
                    $tmp["Banner"]["image_name"] = "http://" . $_SERVER["HTTP_HOST"] . "/img/Banner/" . $name;

                   
                }
            }

            // background image
            if (!empty($tmp['BannerImage']['background_image']["name"])) {
                //unlink('../webroot/img/Banner/' . $bnnerImg['Banner']['background_image']);
                $imgNameExt = pathinfo($tmp['BannerImage']['background_image']["name"]);
                $ext = $imgNameExt['extension'];
                $ext = strtolower($ext);
                if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {

                    $size = getimagesize($tmp["BannerImage"]["background_image"]["tmp_name"]);
                    $width = $size[0];
                    $height = $size[1];

                    $newImgName = 'back_ground_banner_' . time();
                    $tempFile = $tmp['BannerImage']['background_image']['tmp_name'];
                    $destThumb = realpath('../webroot/img/Banner/') . '/';
                    $file = $tmp['BannerImage']['background_image'];

                    $file['name'] = $imgNameExt['filename'] . '.' . $ext;

                   
                    if ($ext == 'gif') {
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('1200', '250')));
                        $name = $newImgName . ".gif";
                    } else if ($ext == 'png') {

                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('1200', '250')));

                        $name = $newImgName . ".png";
                    } else if ($ext == 'jpeg') {
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('1200', '250')));
                        $name = $newImgName . ".jpeg";
                    } else {
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('1200', '250')));
                        $name = $newImgName . ".jpg";
                    }
                    $tmp["Banner"]["background_image"] = "http://" . $_SERVER["HTTP_HOST"] . "/img/Banner/" . $name;

                    
                }
            }

            if ($this->Banner->save($tmp)) {

                for ($i = 0; $i < count($tmp['BannerImage']['to_brand']); $i++) {
                    $this->BannerImage->create();
                    $ImageData['BannerImage']['banner_id'] = $this->Banner->getLastInsertId();
                    $ImageData['BannerImage']['to_brand'] = $tmp['BannerImage']['to_brand'][$i];
                    $ImageData['BannerImage']['location'] = $tmp['BannerImage']['location'][$i];
                    $ImageData['BannerImage']['url'] = $tmp['BannerImage']['url'][$i];
                    if ($tmp['BannerImage']['start_date'][$i] == 0) {
                        $ImageData['BannerImage']['is_show'] = 0;
                    } else {
                        $ImageData['BannerImage']['is_show'] = 1;
                    }
                    $ImageData['BannerImage']['start_date'] = $tmp['BannerImage']['start_date'][$i];
                    $ImageData['BannerImage']['end_date'] = $tmp['BannerImage']['end_date'][$i];
                  

                    $this->BannerImage->save($ImageData);
                }
                if(empty($this->data['Banner']["id"]))
                {
                    $upMsg = "Added Successfully"; 
                }
                else
                {
                    $upMsg = "Updated Successfully";
                }
                $this->Session->setFlash($upMsg, "default", array("class" => "green"));
                $this->redirect(array("controller" => "CmsPages", "action" => "listBanner"));
            } else {
                $this->Session->setFlash("Unable to Saved Banner", "default", array("class" => "red"));
                $this->redirect(array("controller" => "CmsPages", "action" => "listBanner"));
            }
        }
    }

    /**
     * @Created:        3-Jun-2014
     * @Method :        admin_listBanner
     * @Author: 	Prateek Jadhav
     * @Modified :	
     * @Purpose:   	Function to list/manage the banner
     * @Param:     	$id
     * @Return:    	none
     */
    public function admin_listBanner() {
        $this->layout = "admin/admin";
        $this->set('title_for_layout', 'ALIST Hub :: Banner Listing');
        $this->loadModel("Banner");
        $this->paginate = array("conditions" => array(), 'limit' => 10, 'order' => "Banner.id desc", "fields" => array("Banner.*"));
      
        $this->set('banners', $this->paginate('Banner'));
    }

    /**
     * @Created:        3-Jun-2014
     * @Method :        admin_editBanner
     * @Author: 	Sachin Thakur
     * @Modified :	
     * @Purpose:   	Function to edit the banner
     * @Param:     	$bannerId
     * @Return:    	none
     */
    public function admin_editBanner($bannerId = NULL) {
        $this->layout = "admin/admin";
        $this->set('title_for_layout', 'ALIST Hub :: Edit Banner');
        $bId = base64_decode($bannerId);
        $this->loadModel("Banner");
        $this->loadModel("BannerImage");
        #Start Code :: To fetch User / Brand
        $this->loadModel("User");
        $users = $this->User->find('list', array('conditions' => array('User.role_id' => 4), 'fields' => array('User.id', 'User.username')));
        $this->set('users', $users);

        #Start Code :: To fetch Events
        $this->loadModel("Event");
        $events = $this->Event->find('list', array('conditions' => array(''), 'fields' => array('Event.id', 'Event.title')));
        $this->set('events', $events);
        #End Code :: To fetch User / Brand
        $bnnerImg = $this->Banner->find('first', array('conditions' => array('Banner.id' => $bId), 'fields' => array('Banner.image_name', "Banner.background_image"), 'recursive' => -1));

        $oldImg = $bnnerImg['Banner']['image_name'];
        $this->set('oldImg', $oldImg);

        $oldBImg = $bnnerImg['Banner']['background_image'];
        $this->set('oldBImg', $oldBImg);

        $bannerData = $this->Banner->find('first', array('conditions' => array('Banner.id' => $bId)));

        if (!empty($bannerData)) {
            foreach ($bannerData['BannerImage'] as $key => $value):
                if (!($key == 0)) {
                    $bannerData['BannerImage'][$key]['is_show_' . $key] = $value['is_show'];
                    unset($bannerData['BannerImage'][$key]['is_show']);
                }
            endforeach;

            $this->set('bannerData', $bannerData);
        }
        if ($this->data) {

            $this->set('oldImg', $oldImg);
            $this->Banner->deleteAll(array('Banner.id' => $bId));
            $tmp = $this->data;

            if (!empty($tmp['BannerImage']['image_name']["name"])) {
                //unlink('../webroot/img/Banner/' . $bnnerImg['Banner']['image_name']);
                $imgNameExt = pathinfo($tmp['BannerImage']['image_name']["name"]);
                $ext = $imgNameExt['extension'];
                $ext = strtolower($ext);
                if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {

                    $size = getimagesize($tmp["BannerImage"]["image_name"]["tmp_name"]);
                    $width = $size[0];
                    $height = $size[1];

                    $newImgName = 'banner_' . time();
                    $tempFile = $tmp['BannerImage']['image_name']['tmp_name'];
                    $destThumb = realpath('../webroot/img/Banner/') . '/';
                    $file = $tmp['BannerImage']['image_name'];

                    $file['name'] = $imgNameExt['filename'] . '.' . $ext;

                  
                    $width_height = explode("*", trim($this->data["Banner"]["size"]));
                    $set_width = $width_height[0];
                    $set_height = $width_height[1];
                    
                    if ($ext == 'gif') {
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array($set_width, $set_height)));
                        $name = $newImgName . ".gif";
                    } else if ($ext == 'png') {

                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array($set_width, $set_height)));

                        $name = $newImgName . ".png";
                    } else if ($ext == 'jpeg') {
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array($set_width, $set_height)));
                        $name = $newImgName . ".jpeg";
                    } else {
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array($set_width, $set_height)));
                        $name = $newImgName . ".jpg";
                    }
                    $tmp["Banner"]["image_name"] = "http://" . $_SERVER["HTTP_HOST"] . "/img/Banner/" . $name;

                  
                }
            } else {
                $tmp["Banner"]["image_name"] = $oldImg;
            }

            if (!empty($tmp['BannerImage']['background_image']["name"])) {
                //unlink('../webroot/img/Banner/' . $bnnerImg['Banner']['background_image']);
                $imgNameExt = pathinfo($tmp['BannerImage']['background_image']["name"]);
                $ext = $imgNameExt['extension'];
                $ext = strtolower($ext);
                if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {

                    $size = getimagesize($tmp["BannerImage"]["background_image"]["tmp_name"]);
                    $width = $size[0];
                    $height = $size[1];

                    $newImgName = 'back_ground_banner_' . time();
                    $tempFile = $tmp['BannerImage']['background_image']['tmp_name'];
                    $destThumb = realpath('../webroot/img/Banner/') . '/';
                    $file = $tmp['BannerImage']['background_image'];

                    $file['name'] = $imgNameExt['filename'] . '.' . $ext;

                   

                    if ($ext == 'gif') {
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('1200', '250')));
                        $name = $newImgName . ".gif";
                    } else if ($ext == 'png') {

                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('1200', '250')));

                        $name = $newImgName . ".png";
                    } else if ($ext == 'jpeg') {
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('1200', '250')));
                        $name = $newImgName . ".jpeg";
                    } else {
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('1200', '250')));
                        $name = $newImgName . ".jpg";
                    }
                    $tmp["Banner"]["background_image"] = "http://" . $_SERVER["HTTP_HOST"] . "/img/Banner/" . $name;

                   
                }
            } else {
                $tmp["Banner"]["background_image"] = $oldBImg;
            }

            if ($this->Banner->save($tmp)) {

                for ($i = 0; $i < count($tmp['BannerImage']['to_brand']); $i++) {
                    $this->BannerImage->create();
                    $ImageData['BannerImage']['banner_id'] = $this->Banner->getLastInsertId();
                    $ImageData['BannerImage']['to_brand'] = $tmp['BannerImage']['to_brand'][$i];
                    $ImageData['BannerImage']['location'] = $tmp['BannerImage']['location'][$i];
                    $ImageData['BannerImage']['url'] = $tmp['BannerImage']['url'][$i];
                    if ($tmp['BannerImage']['start_date'][$i] == 0) {
                        $ImageData['BannerImage']['is_show'] = 0;
                    } else {
                        $ImageData['BannerImage']['is_show'] = 1;
                    }
                    $ImageData['BannerImage']['start_date'] = $tmp['BannerImage']['start_date'][$i];
                    $ImageData['BannerImage']['end_date'] = $tmp['BannerImage']['end_date'][$i];

                    $this->BannerImage->save($ImageData);
                }
                $this->Session->setFlash("Banner Updated", "default", array("class" => "green"));
                $this->redirect(array("controller" => "CmsPages", "action" => "listBanner"));
            } else {
                $this->Session->setFlash("Unable to Update Banner", "default", array("class" => "red"));
                $this->redirect(array("controller" => "CmsPages", "action" => "listBanner"));
            }
        }
    }

    /**
     * @Created:        11-Jun-2014
     * @Method :        admin_viewBanner
     * @Author: 	Prateek Jadhav
     * @Modified :	
     * @Purpose:   	Function to view the banner detail
     * @Param:     	$bannerId
     * @Return:    	none
     */
    public function admin_viewBanner($bannerId = NULL) {
        $this->layout = "admin/admin";
        $this->set('title_for_layout', 'ALIST Hub :: View Banner');
        $this->loadModel("Banner");
        if ($bannerId) {
            $bannerId = base64_decode($bannerId);
            $this->set("banner", $this->Banner->find("first", array("conditions" => array("Banner.id" => $bannerId), "recursive" => 2, "fields" => array("Banner.*", "Brand.name", "Event.title"))));
        } else {
            $this->Session->setFlash("Direct access not allowed", "default", array("class" => "red"));
            $this->redirect(array("controller" => "CmsPages", "action" => "listBanner"));
        }
    }

    /**
     * @Created:        21-July-2014
     * @Method :        setOrder
     * @Author: 	Sachin Thakur
     * @Modified :	
     * @Purpose:   	Function to set the banner order
     * @Param:     	$bannerId
     * @Return:    	none
     */
    public function setOrder() {
        $this->layout = "ajax";
        $this->autoRender = false;
        $this->loadModel("Banner");
        foreach ($this->data['li'] as $key => $value) {
            $this->Banner->id = $value;
            $this->Banner->saveField('order', $key);
        }
        echo "true";
        exit;
    }

}

?>