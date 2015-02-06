<?php

class BrandsController extends AppController {

    var $helpers = array('Html', 'Form', 'Cache','Common');
    public $components = array('Email', 'Upload');
    public $paginate = array(
        'limit' => 10
    );

    /** @Created:    12-May-2014
     * @Method :     beforeFilter
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Rendered before any function
     * @Param:       none
     * @Return:      none
     */
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('createEvent');
    }

    /** @Created:    12-May-2014
     * @Method :     admin_index
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     this is index page
     * @Param:       none
     * @Return:      none
     */
    public function admin_index() {
        $this->redirect(array("controller" => "Brands", "action" => "list"));
    }

    /** @Created:    12-May-2014
     * @Method :     admin_list
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for listing of brands
     * @Param:       none
     * @Return:      none
     */
    public function admin_list() {
        $this->layout = 'admin/admin';
        $this->set('title_for_layout', 'ALIST Hub :: Brand List');
        $conditions = array();
        if (isset($this->params["named"]["page"])) {
            $this->request->data = $this->Session->read("paginator");
        } else {
            $this->Session->delete("paginator");
        }
        if ($this->data) {
            if (!empty($this->data["Brand"]["name"])) {
                $conditions = array_merge($conditions, array("Brand.name LIKE" => "%" . $this->data["Brand"]["name"] . "%"));
            }
            if (!empty($this->data["User"]["username"])) {
                $conditions = array_merge($conditions, array("User.username LIKE" => "%" . $this->data["User"]["username"] . "%"));
            }
            $this->paginate = array('conditions' => $conditions, 'limit' => $this->data["Brand"]["limit"], 'order' => array($this->data["Brand"]["order"] => $this->data["Brand"]["direction"]));
            $this->request->data = $this->data;
            $this->Session->write("paginator", $this->data);
        } else {
            $this->paginate = array('conditions' => $conditions, 'limit' => 10, 'order' => array('Brand.id' => 'DESC'));
        }
        //pr($conditions);die;
        $this->set('brands', $this->paginate('Brand'));
    }

    /** @Created:    12-May-2014
     * @Method :     admin_add
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for add/edit brands
     * @Param:       $brand_id
     * @Return:      none
     */
    public function admin_add($brand_id = null) {
        $this->layout = 'admin/admin';
        $this->set('title_for_layout', 'ALIST Hub :: Add/Edit Brand');
        $this->loadModel('User');
        $this->loadModel('Category');
        $this->loadModel('Vibe');
        
        $this->set("user", $this->User->find("list", array("conditions" => array("User.status" => 1, "User.role_id != " => 1), "fields" => array("User.id", "User.username"), 'order' => "User.username ASC")));
        
        #Fetch Categories
        $categories = $this->Category->find('all', array('recursive' => -1, 'conditions' => array('Category.status' => '1')));
        //print_r($categories);
      
        #Fetch Vibes
        $vibes = $this->Vibe->find('all', array('recursive' => -1, 'conditions' => array('Vibe.status' => '1')));
        
         $this->set(compact('categories', 'vibes'));
        
        if ($this->data) {
            $tmp = $this->data;
            if (!empty($this->data['Brand']['logo']["name"]) && is_array($this->data['Brand']['logo'])) {
                $imgNameExt = pathinfo($this->data['Brand']['logo']["name"]);
                $ext = $imgNameExt['extension'];
                $ext = strtolower($ext);
                if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                    $newImgName = 'brand_' . time();
                    $tempFile = $this->data['Brand']['logo']['tmp_name'];
                    $destLarge = realpath('../webroot/img/brand/large/') . '/';
                    $destThumb = realpath('../webroot/img/brand/small/') . '/';
                    $destOriginal = realpath('../webroot/img/brand/original/') . '/';
                    $file = $this->data['Brand']['logo'];
                    $file['name'] = $imgNameExt['filename'] . '.' . $ext;
                    if ($ext == 'gif') {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('500', '400')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".gif", NULL, array('gif'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('75', '75')));
                        $name = $newImgName . ".gif";
                    } else if ($ext == 'png') {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('500', '400')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".png", NULL, array('png'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('75', '75')));
                        $name = $newImgName . ".png";
                    } else if ($ext == 'jpeg') {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('500', '400')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpeg", NULL, array('jpeg'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('75', '75')));
                        $name = $newImgName . ".jpeg";
                    } else {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('500', '400')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpg", NULL, array('jpg'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('75', '75')));
                        $name = $newImgName . ".jpg";
                    }
                    $tmp["Brand"]["logo"] = $name;
                }
            } else {
                unset($tmp["Brand"]["logo"]);
            }
            if ($this->Brand->save($tmp)) {
                
                   # now save category
                
                $BrandCategory = $tmp["BrandCategory"]["category_id"];
                #pr($UserpCategory);die;
                $this->loadModel("BrandCategory");
                $this->BrandCategory->deleteAll(array('BrandCategory.brand_id' => $this->Brand->id), false);
                foreach ($BrandCategory as $tc) {
                   

                    if ($tc != 0) {
                        $save_tc["BrandCategory"]["id"] = "";
                        $save_tc["BrandCategory"]["brand_id"] = $this->Brand->id;
                        $save_tc["BrandCategory"]["category_id"] = $tc;
                        $this->BrandCategory->save($save_tc);
                    }
                }
                
                # now save vibes
                $BrandVibe = $tmp["BrandVibe"]["vibe_id"]; #pr($UserpVibe);die;
                $this->loadModel("BrandVibe");
                $this->BrandVibe->deleteAll(array('BrandVibe.brand_id' => $this->Brand->id), false);
                foreach ($BrandVibe as $ev) {

                    if ($ev != 0) {
                        $save_ev["BrandVibe"]["id"] = "";
                        $save_ev["BrandVibe"]["brand_id"] = $this->Brand->id;
                        $save_ev["BrandVibe"]["vibe_id"] = $ev;
                        $this->BrandVibe->save($save_ev);
                        //pr($save_ev);
                    }
                }
                
                
                $user["User"]["id"] = $this->data["Brand"]["user_id"];
                $user["User"]["role_id"] = 4;
                $this->loadModel("User");
                $this->User->save($user);
                 if(empty($this->data['Brand']["id"]))
                {
                    $upMsg = "Added Successfully"; 
                }
                else
                {
                    $upMsg = "Updated Successfully";
                }
                $this->Session->setFlash($upMsg, "default", array("class" => "green"));
                $this->redirect(array("controller" => "Brands", "action" => "list"));
            }
        }
        if ($brand_id) {
            
            $this->request->data = $this->Brand->find('first', array('recursive' => 2, 'conditions' => array('Brand.id' => base64_decode($brand_id))));
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
    public function admin_view($brand_id = null) {
        $this->layout = "admin/admin";
        $this->set('title_for_layout', 'ALIST Hub :: View Brand');
        if ($brand_id) {
            $brand = $this->Brand->find("first", array("conditions" => array("Brand.id" => base64_decode($brand_id)))); #pr($brand);die;
            if (!empty($brand)) {
                $this->set('brand', $brand);
            } else {
                $this->Session->setFlash("Data not found", "default", array("class" => "red"));
                $this->redirect(array("controller" => "Brands", "action" => "list"));
            }
        } else {
            $this->Session->setFlash("Direct access not allowed", "default", array("class" => "red"));
            $this->redirect(array("controller" => "Brands", "action" => "list"));
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
        //pr($conditions);die;
        $this->set('brands', $this->paginate('Brand'));
    }

    /** @Created:    26-May-2014
     * @Method :     brandList
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for listing of brands
     * @Param:       none
     * @Return:      none
     */
    public function brandList() {
        $this->layout = 'front/home';
        $this->loadModel("SuscribeBrand");
        $this->loadModel("User");
        $this->loadModel("Category");
        $this->loadModel("Vibe");
        if (isset($this->data["Brand"]["order"])) {
            $order = $this->data["Brand"]["order"];
        } else {
            $order = "Brand.id DESC";
        }
        $this->Brand->unBindModel(array('hasMany'=>array("BrandCategory","BrandVibe"),'belongsTo'=>array('User')),false);
      
        $this->Brand->bindModel(array('hasOne'=>array("BrandCategory","BrandVibe")),false);
        $categories = $this->Category->find("all", array("conditions" => array(), "recursive" => -1));
        $vibes = $this->Vibe->find("all", array("conditions" => array(), "recursive" => -1));
        $this->set(compact('categories', 'vibes'));
        
        
        $my_sub = $this->SuscribeBrand->find("list", array("conditions" => array("SuscribeBrand.user_id" => AuthComponent::User("id")), "fields" => array("SuscribeBrand.id", "SuscribeBrand.brand_id")));
        $this->set("my_suscribe", $my_sub);
        
         if (isset($this->data["Brand"]["view"])) {
            $this->set("view", $this->data["Brand"]["view"]);
        } else {
            $this->set("view", "list");
        }
        
        $conditions = array();
        if(!isset($this->params['named']['page']) && !isset($this->params['named']['sort']))
        {
            if($this->Session->read('conditions'))
            {
                $this->Session->delete('conditions');
            }
        }
        if ($this->data) {
            if(isset($this->data["Event"]["limit"]) && !empty($this->data["Event"]["limit"])){
                $limit = $this->data["Event"]["limit"];
            } else {
                $limit = 10;
            }
            $this->set("limit",$limit);
            
            if(isset($this->data['BrandVibe']['id']) && $this->data['BrandVibe']['id']!=""){
                $arrayVibe = array();
                foreach($this->data['BrandVibe']['id'] as $key=>$value):
                    $arrayVibe[] = $key;
                endforeach;
                
                
                $VibesSearch = $this->Vibe->find("all", array("conditions" => array('Vibe.id'=> $arrayVibe), "recursive" => -1));
                 
                foreach($VibesSearch as $vibeSearch):
                         $vibeSearchArray[] = $vibeSearch['Vibe']['name'];
                endforeach;
                        $vibesS = substr(implode(', ',$vibeSearchArray), 0, 50) . '...';
                $this->set("vibesS", $vibesS);
                  
                if(isset($this->data['BrandVibe']['id']) && !empty($this->data['BrandVibe']['id'])){
                   $conditions = array_merge($conditions,array('BrandVibe.vibe_id'=>$arrayVibe));
                 
                }
            }
            if(isset($this->data['BrandCategory']['id']) && $this->data['BrandCategory']['id']!=""){
                $arrayCategory = array();
                foreach($this->data['BrandCategory']['id'] as $key=>$value):
                    $arrayCategory[] = $key;
                endforeach;
             
              $categoriesSearch = $this->Category->find("all", array("conditions" => array('Category.id'=> $arrayCategory), "recursive" => -1));
             
                foreach($categoriesSearch as $catSearch):
                         $catSearchArray[] = $catSearch['Category']['name'];
                endforeach;
                          $categoriesS = substr(implode(', ',$catSearchArray), 0, 50) . '...';
                $this->set("categoriesS", $categoriesS);
               
                if(isset($this->data['BrandCategory']['id']) && !empty($this->data['BrandCategory']['id'])){
                    $conditions = array_merge($conditions,array('BrandCategory.category_id'=>$arrayCategory));
                  
                }
                   
            }
            
            if (!empty($this->data["Brand"]["name"])) {
                $conditions = array_merge($conditions, array("Brand.name LIKE" => "%" . $this->data["Brand"]["name"] . "%"));
            }
          
            if (!empty($this->data["Brand"]["list"])) {
                if($this->data["Brand"]["list"]=='1')
                {
                    $conditions = array_merge($conditions, array("SuscribeBrand.user_id" => AuthComponent::User("id")));
                }
                if($this->data["Brand"]["list"]=='3')
                {
                   if(count($my_sub)==1)
                   {
                         $first_val =array_values($my_sub);
                         $bList = $first_val[0];
                   }
                   else
                   {
                        $bList = $my_sub;
                   }
                    //print_r();
                    $conditions = array_merge($conditions, array("Brand.id != " => $bList));
                }
            }
            
        }
        
        if(isset($this->data["Event"]["limit"]) && !empty($this->data["Event"]["limit"])){
        
            $limit = $this->data["Event"]["limit"];
        } else {
            $limit = 10;
        }
        $this->set("limit",$limit);
        if(sizeof($conditions) > 0){
            $this->Session->write('conditions',$conditions);
        }
        
        $this->paginate = array('conditions' => $this->Session->read('conditions'),'recursive'=>2,'limit' => $limit, 'order' => $order,'group'=>'Brand.id');
     //  pr($this->paginate('Brand'));die;
        $this->set('brands', $this->paginate('Brand'));
    }

    /**
     * @Created:        26-May-2014
     * @Method :        suscribe
     * @Author: 	Prateek Jadhav
     * @Modified :	
     * @Purpose:   	Function for add brand to my susbscriptions 
     * @Param:     	$brand_id
     * @Return:    	1/2/3
     */
    public function suscribe($brand_id = null) {
        $this->layout = false;
        $this->autoRender = false;
        $this->loadModel("SuscribeBrand");
        if ($brand_id) {
            $user_id = AuthComponent::user("id");
            // check for existing
            $check = $this->SuscribeBrand->find("first", array("conditions" => array("SuscribeBrand.user_id" => $user_id, "SuscribeBrand.brand_id" => $brand_id)));
            if (!empty($check)) {
                if ($this->SuscribeBrand->delete($check["SuscribeBrand"]["id"])) {
                    return 1;
                } else {
                    return 3;
                }
            } else {
                $data["SuscribeBrand"]["user_id"] = $user_id;
                $data["SuscribeBrand"]["brand_id"] = $brand_id;
                if ($this->SuscribeBrand->save($data)) {
                    return 2;
                } else {
                    return 3;
                }
            }
        } else {
            return 3;
        }
    }
    
    
    /** @Created:    26-May-2014
     * @Method :     mySubcription
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for listing of my subscription brands
     * @Param:       none
     * @Return:      none
     */
    public function mySubcription() {
        $this->layout = 'frontend';
        $this->loadModel("SuscribeBrand");
        
        if (isset($this->params["named"]["page"])) {
            $this->request->data = $this->Session->read("brand_paginator");
        } else {
            $this->Session->delete("brand_paginator");
        }
        if ($this->data) {
            
             if (!empty($this->data["Brand"]["list"])) { 
                if($this->data["Brand"]["list"]=='1')
                {
                    $conditions = array("SuscribeBrand.user_id" => AuthComponent::User("id"));
                }
                elseif($this->data["Brand"]["list"]=='3')
                {
                    $conditions = array("SuscribeBrand.user_id != " => AuthComponent::User("id"));
                }
                else
                {
                    $conditions = array();
                }
                }
            // search code will be here
              if (!empty($this->data["Brand"]["name"])) {
                $conditions = array_merge($conditions, array("Brand.name LIKE" => "%" . $this->data["Brand"]["name"] . "%"));
            }
           
            $this->paginate = array('conditions' => $conditions, 'limit' => $this->data["Brand"]["limit"]);
            $this->request->data = $this->data;
            $this->Session->write("paginator", $this->data);
        } else {
            $conditions = array("SuscribeBrand.user_id" => AuthComponent::User("id"));
            $this->paginate = array('conditions' => $conditions, 'limit' => 4, 'order' => array('SuscribeBrand.id' => 'DESC'));
        }
        //pr($conditions);die;
        $this->set('brands', $this->paginate('SuscribeBrand'));
    }
    
    /** @Created:    12-May-2014
     * @Method :     viewBrand
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     for view brands
     * @Param:       $brand_id
     * @Return:      none
     */
    public function viewBrand($brand_id = null) {
        $this->layout = "front/home";
        if ($brand_id) {
            $br_id = base64_decode($brand_id);
            $brand = $this->Brand->find("first", array("conditions" => array("Brand.id" => $br_id)));
            if (!empty($brand)) {
                $this->set('brand', $brand);
            } else {
                $this->Session->setFlash("Data not found", "default", array("class" => "red"));
                $this->redirect(array("controller" => "Brands", "action" => "mySubcription"));
            }
        } else {
            $this->Session->setFlash("Direct access not allowed", "default", array("class" => "red"));
            $this->redirect(array("controller" => "Brands", "action" => "mySubcription"));
        }
       
    }

}

?> 