<?php

class TemplatesController extends AppController {

    var $helpers = array('Html', 'Form', 'Cache');
    public $components = array('Cookie', 'Email', 'Upload');
    public $uses = array();

    /* @Created:     15-Sept-2014
     * @Method :     beforeFilter
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Rendered before any function
     * @Param:       none
     * @Return:      none
     */

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow("saveTemplateImage");
    }

    /* @Created:     15-Sept-2014
     * @Method :     index
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Rendered before any function
     * @Param:       none
     * @Return:      none
     */

    function admin_index() {
        $this->redirect(array("controller" => "Templates", "action" => "list"));
    }

    /* @Created:     15-Sept-2014
     * @Method :     admin_list
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for listing of event templates
     * @Param:       none
     * @Return:      none
     */

    function admin_list() {
        $this->layout = "admin/admin";
        $this->set('title_for_layout', 'ALIST Hub :: Email Templates');

        $this->loadModel("EventTemplate");
        $conditions = array();
        $fields = array("EventTemplate.*");
        if (isset($this->params["named"]["page"])) {
            $this->request->data = $this->Session->read("template_list");
        } else {
            $this->Session->delete("template_list");
        }
        if ($this->data) {
            if (!empty($this->data["EventTemplate"]["name"])) {
                $conditions = array_merge($conditions, array("EventTemplate.name LIKE" => "%" . $this->data["EventTemplate"]["name"] . "%"));
            }
            $this->paginate = array('conditions' => $conditions, 'limit' => $this->data["EventTemplate"]["limit"], 'order' => array($this->data["EventTemplate"]["order"] => $this->data["EventTemplate"]["direction"]));
            $this->request->data = $this->data;
            $this->Session->write("template_list", $this->data);
        } else {
            $this->paginate = array('conditions' => $conditions, 'limit' => 10, 'order' => array('EventTemplate.id' => 'DESC'), "fields" => $fields);
        }
        $templates = $this->paginate('EventTemplate');
        $this->set('templates', $templates);
    }

    /* @Created:     15-Sept-2014
     * @Method :     admin_create
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for create / edit any template
     * @Param:       $id/none
     * @Return:      none
     */

    public function admin_create($id = NULL) {
        $this->layout = "admin/admin";
        $this->set('title_for_layout', 'ALIST Hub :: Create / Edit Templates');
        $this->loadModel("EventTemplate");
        if ($this->data) {
            // first adjust html according to template editor need
            if (!isset($this->data["EventTemplate"]["id"]) || empty($this->data["EventTemplate"]["id"])) {
                    $html = $this->data["EventTemplate"]["html"];
                    // now from here strat createing new setting
                    $dom = new DOMDocument();
                    $dom->loadHTML($html);
        
                    foreach ($dom->getElementsByTagName('img') as $img) {
                        $fancyHref = $dom->createElement('div');
                        $clone = $img->cloneNode();
        
                        $clone->setAttribute("id", uniqid());
        
                        //$clone->setAttribute("onclick", "launchEditor(this.id,this.src);");
        
                        $clone->setAttribute("class", "launchEditor");
        
        
                        $fancyHref->appendChild($clone);
        
                        $img->parentNode->replaceChild($fancyHref, $img);
                    };
                    $html = $dom->saveHTML();
        
                    // add contenteditable to every div
                    // remove all existing edit setting
                    $html = str_replace("contenteditable = 'true'", "", $html);
                    $html = str_replace("<div", "<div contenteditable = 'true'", $html);
                    $html = str_replace("background-color", "#background-color", $html);
                    $this->request->data["EventTemplate"]["html"] = $html;
            }

            // now save to database
            if (!isset($this->data["EventTemplate"]["image"]["name"]) || empty($this->data["EventTemplate"]["image"]["name"])) {
                unset($this->request->data["EventTemplate"]["image"]);
            } else {
                $imgNameExt = pathinfo($this->data["EventTemplate"]["image"]["name"]);
                $ext = $imgNameExt['extension'];
                $ext = strtolower($ext);
                if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                    $newImgName = 'flyer_image_' . time();
                    $tempFile = $this->data["EventTemplate"]["image"]['tmp_name'];
                    $destLarge = realpath('../webroot/img/template/large/') . '/';
                    $destThumb = realpath('../webroot/img/template/small/') . '/';
                    $destOriginal = realpath('../webroot/img/template/original/') . '/';
                    $file = $this->data["EventTemplate"]["image"];
                    $file['name'] = $imgNameExt['filename'] . '.' . $ext;
                    if ($ext == 'gif') {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('300', '600')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".gif", NULL, array('gif'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('100', '200')));
                        $name = $newImgName . ".gif";
                    } else if ($ext == 'png') {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('300', '600')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".png", NULL, array('png'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('100', '200')));
                        $name = $newImgName . ".png";
                    } else if ($ext == 'jpeg') {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('300', '600')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpeg", NULL, array('jpeg'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('100', '200')));
                        $name = $newImgName . ".jpeg";
                    } else {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('300', '600')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpg", NULL, array('jpg'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('100', '200')));
                        $name = $newImgName . ".jpg";
                    }
                    $this->request->data["EventTemplate"]["image"] = $name;
                }
            }
            if ($this->EventTemplate->save($this->data)) {
                $this->Session->setFlash("Your template has been saved", "default", array("class" => "green"));
                $this->redirect(array("controller" => "Templates", "action" => "list"));
            } else {
                $this->Session->setFlash("Unable to save, Please try again", "default", array("class" => "red"));
            }
        } else {
            $id = base64_decode($id);
            $this->request->data = $this->EventTemplate->findById($id);
        }
    }

    /* @Created:     16-Sept-2014
     * @Method :     admin_view
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for view and put placeholder on any template
     * @Param:       $id
     * @Return:      none
     */

    public function admin_view($id = NULL) {
        $this->layout = "admin/admin";
        $this->set('title_for_layout', 'ALIST Hub :: View Template');
        $this->loadModel("EventTemplate");
        if ($this->data) {
            if ($this->EventTemplate->save($this->data)) {
                $this->Session->setFlash("Template saved successfully", "default", array("class" => "green"));
                $this->redirect(array("controller" => "Templates", "action" => "list"));
            } else {
                $this->Session->setFlash("Unable to save template, please try again", "default", array("class" => "red"));
            }
        }
        $data = $this->EventTemplate->findById(base64_decode($id));
        $this->set("data", $data);
    }

    public function uploadCustomHtml() {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        if ($this->data) {
            $this->loadModel("EventTemplate");
            
            // first adjust html according to template editor need
            $html = $this->data["Template"]["html"];
            // now from here strat createing new setting
            $dom = new DOMDocument();
            $dom->loadHTML($html);

            foreach ($dom->getElementsByTagName('img') as $img) {
                $fancyHref = $dom->createElement('div');
                $clone = $img->cloneNode();

                $clone->setAttribute("id", uniqid());

                //$clone->setAttribute("onclick", "launchEditor(this.id,this.src);");

                $clone->setAttribute("class", "launchEditor");


                $fancyHref->appendChild($clone);

                $img->parentNode->replaceChild($fancyHref, $img);
            };
            $html = $dom->saveHTML();

            // add contenteditable to every div
            // remove all existing edit setting
            $html = str_replace("contenteditable = 'true'", "", $html);
            $html = str_replace("<div", "<div contenteditable = 'true'", $html);
            $html = str_replace("background-color", "#background-color", $html);
            //$this->request->data["EventTemplate"]["html"] = $html;
            $savedata["EventTemplate"]["html"] = $html;
            $savedata["EventTemplate"]["type"] = 3;
            if ($this->EventTemplate->save($savedata)) {
                $id = $this->EventTemplate->getLastInsertID();
                //$this->Session->setFlash("Your template has been saved", "default", array("class" => "green"));
                $this->redirect(array("controller" => "Campaigns", "action" => "chooseTemplate", base64_encode($id)));
            } else {
                //$this->Session->setFlash("Unable to save, Please try again", "default", array("class" => "red"));
                $this->redirect(array("controller" => "Campaigns", "action" => "chooseTemplate"));
            }
        }
    }

    public function imgupload() {

        $url = 'ContestImages' . DS . time() . "_" . $_FILES['upload']['name'];
        $url1 = WWW_ROOT . $url;
        if (($_FILES['upload'] == "none") OR (empty($_FILES['upload']['name']))) {
            $message = "No file uploaded.";
        } else if ($_FILES['upload']["size"] == 0) {
            $message = "The file is of zero length.";
        } else if (($_FILES['upload']["type"] != "image/pjpeg") AND ($_FILES['upload']["type"] != "image/jpeg") AND ($_FILES['upload']["type"] != "image/png")) {
            $message = "The image must be in either JPG or PNG format. Please upload a JPG or PNG instead.";
        } else if (!is_uploaded_file($_FILES['upload']["tmp_name"])) {
            $message = "You may be attempting to hack our server. We're on to you; expect a knock on the door sometime soon.";
        } else {
            $message = "";

            $move = move_uploaded_file($_FILES['upload']['tmp_name'], $url1);
            if (!$move) {
                $message = "Error moving uploaded file. Check the script is granted Read/Write/Modify permissions.";
            }

            $url = BASE_URL . DS . $url;
        }
        $funcNum = $_GET['CKEditorFuncNum'];
        echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
    }
    
    public function saveTemplateImage(){
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        
        
        $image_data = file_get_contents($_POST['image']);
        $abc_path = realpath("../webroot/img/template/");
        $img_name = "template_".time().".jpeg";
        imagejpeg(imagecreatefromstring($image_data),"img/template/".$img_name);
        return "http://".$_SERVER["HTTP_HOST"]."/img/template/".$img_name;
    }

}
