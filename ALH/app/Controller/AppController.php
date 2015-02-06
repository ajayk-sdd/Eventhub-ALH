<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
ob_start();
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    public $components = array(
        'Acl',
        'Auth' => array(
            'authorize' => array(
                'Actions' => array('actionPath' => 'controllers')
            )
        ),
        'Session'
    );

    function beforeFilter() {
        // For CakePHP 2.1 and up
        $this->Auth->allow("admin_login");
        if ($this->params['prefix'] == 'admin') {
            $this->Auth->loginAction = array('controller' => 'Users', 'action' => 'admin_login');
            $this->Auth->logoutRedirect = array('controller' => 'Users', 'action' => 'admin_login');
            $this->Auth->loginRedirect = array('controller' => 'Users', 'action' => 'admin_dashboard');
        }

        $this->loadModel('CmsPage');
        $cmsPages = $this->CmsPage->find("all", array('fields' => array('id', 'parent', 'title', 'description','slug'), 'conditions' => array('CmsPage.status' => 1, 'CmsPage.parent' => 0), 'order' => array('CmsPage.id')));
        //  pr($cmsPages);die;
        $this->set("cmsPages", $cmsPages);
        $this->callme();
        if (AuthComponent::user("id")) {
            $this->loadModel("User");
            $user_detail = $this->User->find("first", array("conditions" => array("User.id" => AuthComponent::user("id")), "fields" => array("User.ALH_point")));
            $ALH_point = $user_detail["User"]["ALH_point"];
            $this->set("ALH_point", $ALH_point);
        }
        if (AuthComponent::user("id")) {
            $this->loadModel("Event");
            $count_my_event = $this->Event->find("count", array("conditions" => array("Event.user_id" => AuthComponent::user("id"))));
            $this->set("count_my_event", $count_my_event);
        }

        //$ip = $_SERVER['REMOTE_ADDR'];
    }

    public function findzip($distance = null, $latitude = null, $longitude = null) {

        $this->loadModel('Zip');
        if (!empty($latitude)) {
            $sql = "SELECT zip,id, (((acos(sin(($latitude*pi()/180)) * sin((`lat`*pi()/180))+cos(($latitude*pi()/180)) * cos((`lat`*pi()/180)) * cos((($longitude - `lng`)*pi()/180))))*180/pi())*60*1.1515) AS `distance` FROM zips HAVING distance < $distance";
            $output = $this->Zip->query($sql);
            //pr($output);die;
            $postcode = array();
            foreach ($output as $op) {
                $postcode[$op['zips']['id']] = $op['zips']['zip'];
            }
        } else {
            $postcode = "";
        }
        return $postcode;
    }

    function callme() {
        App::import('Controller', 'Users');
        $Products = new UsersController;
        //$obj :: UsersController();
        $Products->testLogin();
    }

    public function ipDetail() {
        
        /************** Start Info DB Curl Code *****************/
        
         // For ipinfodb
        $ipAddress = $_SERVER["REMOTE_ADDR"];
        
       // set HTTP header
        $headers = array(
            'Content-Type: application/json',
        );
        
        // query string
        $fields = array(
            'key' => '39bd416ded13373d097fa0c5a1a28f35b5a385f0ba63239da28b9275c1f670f5',
            'format' => 'json',
            'ip' => $ipAddress,
        );
        $url = 'http://api.ipinfodb.com/v3/ip-city?' . http_build_query($fields);
        
        // Open connection
        $ch = curl_init();
        
        // Set the url, number of GET vars, GET data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        // Execute request
        $result = curl_exec($ch);
        
        // Close connection
        curl_close($ch);
        
        // get the result and parse to JSON
        $ip_detail = json_decode($result, true);
        
        /************** End Info DB Curl Code *****************/
     
        // now make this array into object
        $ip_detail = json_encode($ip_detail);
        $ip_detail = json_decode($ip_detail);
       
        
        return $ip_detail;
    }

    public function saveInfo() {
        $this->loadModel("Information");
        $ipDetail = $this->ipDetail();
        $information["Information"]["user_id"] = AuthComponent::user("id");
        $information["Information"]["last_login"] = date("Y-m-d H:i:s");
        $information["Information"]["ip"] = $ipDetail->ipAddress;
        $information["Information"]["country"] = $ipDetail->countryName;
        $information["Information"]["city"] = $ipDetail->cityName;
        $information["Information"]["zip"] = $ipDetail->zipCode;
        $information["Information"]["state"] = $ipDetail->regionName;
        if($this->Information->save($information)){
            return TRUE;
        } else {
            return FALSE;
        }
        
    }
    
    public function normalize_array($arr) {
                                for ($res = array(), $i = 0; $i < count($arr); $i+=2) {
                                    $key = strtr($arr[$i],array(': '=>'','-'=>'_'));
                                    $res[$key] = $arr[$i+1];
                                }
                            return $res;
                            }
                            
                            
      /** @Created:     25-Oct-2014
     * @Method :     mail_send
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     check sent/devlivered mail
     * @Param:       none
     * @Return:      none
     */
      
     public function mail_send($nxt=null){
         $this->loadModel("CampaignEmail");
        $response = $this->Mailgun->get_mail($nxt);
      //echo date('d-m-Y','1412717727');
     // pr($response);
      
      foreach($response as $responseEmail)
      {
        foreach($responseEmail as $responseEmaila)
        {
            if(is_array($responseEmaila))
      {
            pr($responseEmaila);
        $msgId = $responseEmaila['message']['headers']['message-id'];
           
        $msgIds = trim($msgId);
            $CampEmailDetails = $this->CampaignEmail->find("all",array("conditions" => array("CampaignEmail.sent_check" => '0',"CampaignEmail.message_id" => $msgIds,"CampaignEmail.email" => $responseEmaila['recipient'])));
          if(isset($CampEmailDetails[0]['CampaignEmail']['id']))
          {
            $data["CampaignEmail"]["id"] = $CampEmailDetails[0]['CampaignEmail']['id'];
            $data["CampaignEmail"]["sent_status"] = '1';
            $data["CampaignEmail"]["sent_check"] = '1';
            $this->CampaignEmail->save($data);
           // $this->CampaignEmail->updateAll(array('CampaignEmail.sent_status' => '1','CampaignEmail.sent_check' => '1'),array('CampaignEmail.message_id' => $msgId));
          }
        }
        
        
      }
        
      }
      if(isset($response['paging']))
         {
            $next = $response['paging']['next'];
         
          
           if(!empty($response['items']))
           {
           $this->mail_send($next);
           }
           }
         
     }
     
      /** @Created:     25-Oct-2014
     * @Method :     mail_opened
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     check Opened mail
     * @Param:       none
     * @Return:      none
     */
      
      public function mail_opened($nxt=null){
         $this->loadModel("CampaignEmail");
        $response = $this->Mailgun->get_mail_opened($nxt);
      //echo date('d-m-Y','1412717727');
      pr($response);
      
      foreach($response as $responseEmail)
      {
        foreach($responseEmail as $responseEmaila)
        {
            if(is_array($responseEmaila))
      {
            pr($responseEmaila);
        $msgId = $responseEmaila['message']['headers']['message-id'];
           
        $msgIds = trim($msgId);
            $CampEmailDetails = $this->CampaignEmail->find("all",array("conditions" => array("CampaignEmail.sent_status" => '1',"CampaignEmail.message_id" => $msgIds,"CampaignEmail.email" => $responseEmaila['recipient'])));
          if(isset($CampEmailDetails[0]['CampaignEmail']['id']))
          {
            $data["CampaignEmail"]["id"] = $CampEmailDetails[0]['CampaignEmail']['id'];
            $data["CampaignEmail"]["open_status"] = '1';
            $this->CampaignEmail->save($data);
           // $this->CampaignEmail->updateAll(array('CampaignEmail.sent_status' => '1','CampaignEmail.sent_check' => '1'),array('CampaignEmail.message_id' => $msgId));
          }
        }
        
        
      }
        
      }
      if(isset($response['paging']))
         {
            $next = $response['paging']['next'];
         
          
           if(!empty($response['items']))
           {
           $this->mail_opened($next);
           }
           }
         
     }
     
      /** @Created:     25-Oct-2014
     * @Method :     mail_clicked
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     check Clicked mail
     * @Param:       none
     * @Return:      none
     */
      
      public function mail_clicked($nxt=null){
         $this->loadModel("CampaignEmail");
        $response = $this->Mailgun->get_mail_clicked($nxt);
      //echo date('d-m-Y','1412717727');
      echo "<pre>";
      print_r($response);
      
      
      foreach($response as $responseEmail)
      {
        foreach($responseEmail as $responseEmaila)
        {
            if(is_array($responseEmaila))
      {
            pr($responseEmaila);
        $msgId = $responseEmaila['message']['headers']['message-id'];
           
        $msgIds = trim($msgId);
            $CampEmailDetails = $this->CampaignEmail->find("all",array("conditions" => array("CampaignEmail.sent_status" => '1',"CampaignEmail.message_id" => $msgIds,"CampaignEmail.email" => $responseEmaila['recipient'])));
          if(isset($CampEmailDetails[0]['CampaignEmail']['id']))
          {
            $data["CampaignEmail"]["id"] = $CampEmailDetails[0]['CampaignEmail']['id'];
            $data["CampaignEmail"]["open_status"] = '1';
            $data["CampaignEmail"]["click_status"] = '1';
            $data["CampaignEmail"]["city"] = $responseEmaila['geolocation']['city'];
            $data["CampaignEmail"]["region"] = $responseEmaila['geolocation']['region'];
            $data["CampaignEmail"]["country"] = $responseEmaila['geolocation']['country'];
            $this->CampaignEmail->save($data);
           // $this->CampaignEmail->updateAll(array('CampaignEmail.sent_status' => '1','CampaignEmail.sent_check' => '1'),array('CampaignEmail.message_id' => $msgId));
          }
        }
        
        
      }
        
      }
      if(isset($response['paging']))
         {
            $next = $response['paging']['next'];
         
          
           if(!empty($response['items']))
           {
           $this->mail_clicked($next);
           }
           }
         
     }
     
       /** @Created:     08-Jan-2015
     * @Method :     csv_delimeter
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     find csv delimeter
     * @Param:       none
     * @Return:      none
     */
      
      public function csv_delimeter($file=null){
        
        //detect these delimeters
                    $delA = array(";", ",", "|", "\t");
                    $linesA = array();
                    $resultA = array();
                
                    $maxLines = 20; //maximum lines to parse for detection, this can be higher for more precision
                    $lines = count(file($file));
                    if ($lines < $maxLines) {//if lines are less than the given maximum
                        $maxLines = $lines;
                    }
                
                    //load lines
                    foreach ($delA as $key => $del) {
                        $rowNum = 0;
                        if (($handle = fopen($file, "r")) !== false) {
                            $linesA[$key] = array();
                            while ((($data = fgetcsv($handle, 1000, $del)) !== false) && ($rowNum < $maxLines)) {
                                $linesA[$key][] = count($data);
                                $rowNum++;
                            }
                
                            fclose($handle);
                        }
                    }
                
                    //count rows delimiter number discrepancy from each other
                    foreach ($delA as $key => $del) {
                        echo 'try for key=' . $key . ' delimeter=' . $del;
                        $discr = 0;
                        foreach ($linesA[$key] as $actNum) {
                            if ($actNum == 1) {
                                $resultA[$key] = 65535; //there is only one column with this delimeter in this line, so this is not our delimiter, set this discrepancy to high
                                break;
                            }
                
                            foreach ($linesA[$key] as $actNum2) {
                                $discr += abs($actNum - $actNum2);
                            }
                
                            //if its the real delimeter this result should the nearest to 0
                            //because in the ideal (errorless) case all lines have same column number
                            $resultA[$key] = $discr;
                        }
                    }
                
                    var_dump($resultA);
                
                    //select the discrepancy nearest to 0, this would be our delimiter
                    $delRes = 65535;
                    foreach ($resultA as $key => $res) {
                        if ($res < $delRes) {
                            $delRes = $res;
                            $delKey = $key;
                        }
                    }
                
                    $delimeter = $delA[$delKey];
                
                    //echo '$delimeter=' . $delimeter;
                    //exit;
                          return $delimeter; 
        
      }
     

}