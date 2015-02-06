<?php

class NewslettersController extends AppController {

    var $helpers = array('Html', 'Form', 'Cache');
    public $components = array('Cookie', 'Email');
    public $paginate = array(
        'limit' => 10
    );

    /** @Created:     28-may-2014
     * @Method :     beforeFilter
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Rendered before any function
     * @Param:       none
     * @Return:      none
     */
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('subscribe', 'sendNewsletterConfirmation','confirmEmail');
    }

    /** @Created:     28-may-2014
     * @Method :     subscribe
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     to subscribe an email address for newsletter
     * @Param:       email
     * @Return:      none
     */
    public function subscribe($email = null) {
        $this->layout = "ajax";
        $this->autoRender = false;
        if ($email) {
            $check = $this->Newsletter->findByEmail($email);
          
            if (empty($check)) {
                $data["Newsletter"]["id"] = "";
                $data["Newsletter"]["email"] = $email;
                $data["Newsletter"]["token"] = md5($email);
                $data["Newsletter"]["status"] = 0;
                if ($this->Newsletter->save($data)) {
                    $sent_email = $this->sendNewsletterConfirmation($email);
                   
                    // successfully subscribe
                    return 1;
                } else {
                    // unable to save
                    return 2;
                }
            } else {
                // already subscribe
                return 3;
            }
        } else {
            // enter an email address
            return 4;
        }
    }

    /** @Created:    07-Aug-2014
     * @Method :     sendNewsletterConfirmation
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Send confirm newsletter mail
     * @Param:       $email
     * @Return:      true
     */
    public function sendNewsletterConfirmation($email = NULL) {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $return = "";
        if ($email) {

            $this->loadModel("EmailTemplate");


            // send to client
            $newsletter = $this->Newsletter->findByEmail($email);
            $url = "http://" . $_SERVER["HTTP_HOST"];
            $token = "http://" . $_SERVER["HTTP_HOST"] . "/Newsletters/confirmEmail/" . $newsletter["Newsletter"]["token"];
            $emailTemp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.alias' => "newsletter-confirmation")));
            $emailContent = utf8_decode($emailTemp['EmailTemplate']['description']);
            $data = str_replace(array('{EMAIL}', '{URL}', "{TOKEN}"), array($email, $url, $token), $emailContent);
            $this->set('mailData', $data);
            $this->Email->to = $email;
            $this->Email->subject = $emailTemp['EmailTemplate']['subject'];
            $this->Email->from = "aisthub@admin.com";
            $this->Email->template = "forgot";
            $this->Email->sendAs = 'html';
            
            if ($this->Email->send($data)) {
                $return = "sent";
            } else {
                $return = "Not sent";
            }

            // send to admin
            $url = "http://" . $_SERVER["HTTP_HOST"] . "/admin/Newsletters/list";
            $emailTemp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.alias' => "newsletter-confirmation-admin")));
            $emailContent = utf8_decode($emailTemp['EmailTemplate']['description']);
            $data = str_replace(array('{EMAIL}', '{URL}'), array($email, $url), $emailContent);
            $this->set('mailData', $data);
            $this->Email->to = "aisthub@admin.com";
            $this->Email->subject = $emailTemp['EmailTemplate']['subject'];
            $this->Email->from = "aisthub@admin.com";
            $this->Email->template = "forgot";
            $this->Email->sendAs = 'html';
            if ($this->Email->send($data)) {
                $return = "sent";
            } else {
                $return = "Not sent admin";
            }
        } else {
            return "No Email";
        }
        return $return;
    }

    /** @Created:    07-Aug-2014
     * @Method :     sendNewsletterConfirmation
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Send confirm newsletter mail
     * @Param:       $email
     * @Return:      true
     */
    public function confirmEmail($token = NULL) {
        $this->layout = "front/home";
        if ($token) {
            $this->loadModel("EmailTemplate");
            // send to client
            $newsletter = $this->Newsletter->findByToken($token);
            if (!empty($newsletter)) {
                $email = $newsletter["Newsletter"]["email"];
                $newsletter["Newsletter"]["status"] = 1;
                $newsletter["Newsletter"]["token"] = "";
                $this->Newsletter->save($newsletter);
                $url = "http://" . $_SERVER["HTTP_HOST"];
                $emailTemp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.alias' => "newsletter-confirmed")));
                $emailContent = utf8_decode($emailTemp['EmailTemplate']['description']);
                $data = str_replace(array('{EMAIL}', '{URL}'), array($email, $url), $emailContent);
                $this->set('mailData', $data);
                $this->Email->to = $email;
                $this->Email->subject = $emailTemp['EmailTemplate']['subject'];
                $this->Email->from = "aisthub@admin.com";
                $this->Email->template = "forgot";
                $this->Email->sendAs = 'html';
                if ($this->Email->send($data)) {
                    $status = "not sent";
                } else {
                    $status = "sent";
                }
                $this->set("message", "Thanks for confirming your email.");
            } else {
                $this->set("message", "Invalid or broken token, please try again.");
            }
        } else {
            $this->set("message", "Token missing, please try again.");
        }
       
    }

    /** @Created:     28-may-2014
     * @Method :     admin_list
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Listing of subscribe users for newsletter
     * @Param:       none
     * @Return:      none
     */
    public function admin_list() {
        $this->layout = "admin/admin";
        $this->set('title_for_layout', 'ALIST Hub :: Newsletter Subscriber');
        $conditions = array();
        if (isset($this->params["named"]["page"])) {
            $this->request->data = $this->Session->read("newsletter_list");
        } else {
            $this->Session->delete("newsletter_list");
        }
        if ($this->data) {
            if (!empty($this->data["Newsletter"]["email"])) {
                $conditions = array_merge($conditions, array("Newsletter.email LIKE" => "%" . $this->data["Newsletter"]["email"] . "%"));
            }

            $this->paginate = array('conditions' => $conditions, 'limit' => $this->data["Newsletter"]["limit"], 'order' => array($this->data["Newsletter"]["order"] => $this->data["Newsletter"]["direction"]));
            $this->request->data = $this->data;
            $this->Session->write("newsletter_list", $this->data);
        } else {
            $this->paginate = array('conditions' => $conditions, 'limit' => 10, 'order' => array('Newsletter.id' => 'DESC'));
        }
      
        $this->set('newsletters', $this->paginate('Newsletter'));
    }

    /** @Created:     28-may-2014
     * @Method :     admin_add
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Subscribe for an email/edit and email
     * @Param:       none/$id
     * @Return:      none
     */
    function admin_add($newsletter_id = null) {
        $this->layout = "admin/admin";
        $this->set('title_for_layout', 'ALIST Hub :: Subscribe for an email');
        if ($this->data) {
            if ($this->Newsletter->save($this->data)) {
                 if(empty($this->data['Newsletter']["id"]))
                {
                    $upMsg = "Added Successfully"; 
                }
                else
                {
                    $upMsg = "Updated Successfully";
                }
                $this->Session->setFlash($upMsg, "default", array("class" => "green"));
                $this->redirect(array("controller" => "Newsletters", "action" => "list"));
            } else {
                $this->Session->setFlash("Please correct the following error.", "default", array("class" => "red"));
            }
        }
        if ($newsletter_id) {
            $newsletter_id = base64_decode($newsletter_id);
            $this->request->data = $this->Newsletter->findById($newsletter_id);
        }
    }

    /** @Created:     28-may-2014
     * @Method :     admin_listEmail
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Listing of emails of newsletter
     * @Param:       none
     * @Return:      none
     */
    public function admin_listEmail() {
        $this->layout = "admin/admin";
        $this->loadModel("NewsletterEmail");
        $this->set('title_for_layout', 'ALIST Hub :: Newsletter Emails');
        $conditions = array();
        if (isset($this->params["named"]["page"])) {
            $this->request->data = $this->Session->read("NewsletterEmail_list");
        } else {
            $this->Session->delete("NewsletterEmail_list");
        }
        if ($this->data) {
            if (!empty($this->data["NewsletterEmail"]["subject"])) {
                $conditions = array_merge($conditions, array("NewsletterEmail.subject LIKE" => "%" . $this->data["NewsletterEmail"]["subject"] . "%"));
            }

            $this->paginate = array('conditions' => $conditions, 'limit' => $this->data["NewsletterEmail"]["limit"], 'order' => array($this->data["NewsletterEmail"]["order"] => $this->data["Newsletter"]["direction"]));
            $this->request->data = $this->data;
            $this->Session->write("NewsletterEmail_list", $this->data);
        } else {
            $this->paginate = array('conditions' => $conditions, 'limit' => 10, 'order' => array('NewsletterEmail.id' => 'DESC'));
        }
       
        $this->set('newsletters', $this->paginate('NewsletterEmail'));
    }

    /** @Created:     28-may-2014
     * @Method :     admin_addEmail
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Subscribe for an email/edit and email
     * @Param:       none/$id
     * @Return:      none
     */
    function admin_addEmail($newsletter_id = null) {
        $this->layout = "admin/admin";
        $this->set('title_for_layout', 'ALIST Hub :: Add Email');
        $this->loadModel("NewsletterEmail");
        if ($this->data) { 
            if ($this->NewsletterEmail->save($this->data)) {
                $this->Session->setFlash("Data saved", "default", array("class" => "green"));
                $this->redirect(array("controller" => "Newsletters", "action" => "listEmail"));
            } else {
                $this->Session->setFlash("Please correct the following error.", "default", array("class" => "red"));
            }
        }
        if ($newsletter_id) {
            $newsletter_id = base64_decode($newsletter_id);
            $this->request->data = $this->NewsletterEmail->findById($newsletter_id);
        }
    }

    /** @Created:     28-may-2014
     * @Method :     admin_send
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Subscribe for send an email
     * @Param:       none/$id
     * @Return:      none
     */
    public function admin_send($newsletterEmail_id = null) {
        $this->layout = false;
        $this->loadModel("NewsletterEmail");
        if ($newsletterEmail_id) {
            $newsletter = $this->NewsletterEmail->findById(base64_decode($newsletterEmail_id));
            $emails = $this->Newsletter->find("list", array("conditions" => array("Newsletter.status" => 1), "fields" => array("Newsletter.id", "Newsletter.email")));
            $emailContent = utf8_decode($newsletter["NewsletterEmail"]["content"]);
            foreach ($emails as $email) {
                $url = BASE_URL . "/Newsletters/unsubscribe/" . $email;
                $data = str_replace(array('{USER_NAME}', '{url}'), array($email, $url), $emailContent);
                $this->set('mailData', $data);
                $this->Email->to = $email;
                $this->Email->subject = $newsletter["NewsletterEmail"]['subject'];
                $this->Email->from = "aisthub@admin.com";
                $this->Email->template = "forgot";
                $this->Email->sendAs = 'html';
                $this->Email->send();
            }
            $this->Session->setFlash("Email sent", "default", array("class" => "green"));
            $this->redirect(array("controller" => "Newsletters", "action" => "listEmail"));
        } else {
            $this->Session->setFlash("Direct access not allowed", "default", array("class" => "red"));
            $this->redirect(array("controller" => "Newsletters", "action" => "listEmail"));
        }
    }

}

?> 