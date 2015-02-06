<?php

class TestsController extends AppController {

    var $helpers = array('Html', 'Form', 'Cache');
    public $components = array('Cookie', 'Email', 'Mailgun');
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
        $this->Auth->allow('testLogin', 'postEventsOnFacebook', 'checkmail','ckeditor','email_status','email_open_status','email_clicked_status','clickdatassd');
    }

    function postEventsOnFacebook() {
        $this->layout = 'frontend';
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://192.168.0.5:8045/open/OpenInviter/example.php?email_box=sdd.sdei@gmail.com&password_box=smartdata2014&provider_box=gmail");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $output = curl_exec($ch);

        curl_close($ch);

        pr($output);
        die;
        return $output;
    }

    public function contact() {
        $this->layout = "frontend";
        include('/var/www/html/eventhubsvn/eventhubsvn/app/webroot/open/OpenInviter/openinviter.php');
        $inviter = new OpenInviter();
        $oi_services = $inviter->getPlugins();
        if (!$inviter->login("sdd.sdei@gmail.com", "smartdata2014")) {
            $internal = $inviter->getInternalError();
            $ers['login'] = ($internal ? $internal : "Login failed. Please check the email and password you have provided and try again later !");
        } elseif (false === $contacts = $inviter->getMyContacts())
            $ers['contacts'] = "Unable to get contacts !";
        else {
            $import_ok = true;
            $step = 'send_invites';
            $_POST['oi_session_id'] = $inviter->plugin->getSessionID();
            $_POST['message_box'] = '';
        }
    }

    public function test() {
        $this->layout = "frontend";
        include('openinviter.php');
        $inviter = new OpenInviter();
        $oi_services = $inviter->getPlugins();
    }

    public function testmail() {
        $this->layout = false;
        $this->loadModel("OpenRate");
        $data["OpenRate"]["my_list_id"] = 1;
        $data["OpenRate"]["event_id"] = 1;
        $this->OpenRate->save($data);
        $id = $this->OpenRate->getLastInsertID();


        $email = "dev12sebiz@gmail.com";
$er = array("dev"=>"dev12sebiz@gmail.com","test"=>"test.sebiz12@gmail.com");

        $url = "http://" . $_SERVER["HTTP_HOST"] . "/tests/checkmail?id=$id&email=$email";
       
        $click_url = "http://" . $_SERVER["HTTP_HOST"] . "/tests/clickmail?id=$id&email=$email";

        $this->set('url', $url);
        $this->set('click_url', $click_url);
        $this->set('mailData', "sdfgsdfgsdfgsdfgsdfg");
        $this->Email->to = $er;
        $this->Email->subject = "check link";
        $this->Email->from = "prateek@prateek.com";
        $this->Email->template = "testmail";
        $this->Email->sendAs = 'html';
        $ress = $this->Email->send();
      
       function normalize_array($arr) {
    for ($res = array(), $i = 0; $i < count($arr); $i+=2) {
        $key = strtr($arr[$i],array(': '=>'','-'=>'_'));
        $res[$key] = $arr[$i+1];
    }
    return $res;
}
	

        $mailData = normalize_array(preg_split('~([\w-]+: )~',$ress['headers'],-1,PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY));
        print_r($mailData['Message_ID']);
        $msg1 = explode('<', $mailData['Message_ID']); 
        $msg2 = explode('>',$msg1[1]);
        $msgId = $msg2[0];
        
      
        if($ress)
        {
        echo "done";
        }
        else
        {
        echo "no";
        }
    
        die;
    }

         public function clickdatassd() {
        $this->layout = FALSE;
        
        $this->loadModel("OpenRate");

      $sd = serialize($_REQUEST);
        $detail["OpenRate"]["id"] = '';
        $detail["OpenRate"]["count"] = 101;
	$detail["OpenRate"]["textcon"] = $sd;
       
        $this->OpenRate->save($detail);
        die;
     }
     
    public function checkmail() {
        $this->layout = FALSE;
        $id = $this->params->query['id'];
        $email = $this->params->query['email'];

        $this->loadModel("OpenRate");

        $detail = $this->OpenRate->findById($id);
        $detail["OpenRate"]["id"] = $detail["OpenRate"]["id"];
        $detail["OpenRate"]["count"] = 1;
       
        $this->OpenRate->save($detail);
        die;
    }
 
  public function clickmail() {
        $this->layout = FALSE;
        $id = $this->params->query['id'];
        $email = $this->params->query['email'];
       
         $AS = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=72.14.204.103'));
      
        $this->loadModel("OpenRate");
        $this->loadModel("UsStateRegion");
        $regionId = $this->UsStateRegion->find("first",array('conditions'=>array('UsStateRegion.state_code'=>$AS['geoplugin_regionCode']),'fields'=>array('UsStateRegion.region_id')));
        
        $detail = $this->OpenRate->findById($id);
        $detail["OpenRate"]["id"] = $detail["OpenRate"]["id"];
        $detail["OpenRate"]["click_through"] = 1;
        if(isset($regionId['UsStateRegion']['region_id']) & !empty($regionId['UsStateRegion']['region_id']))
        {
        $detail["OpenRate"]["region_id"] = $regionId['UsStateRegion']['region_id'];
        }
        else
        { $detail["OpenRate"]["region_id"] = 5;
            }
          
        $this->OpenRate->save($detail);
        die;
       
    }

    public function testLogin() {
        $this->layout = "front/home";
    }

    public function index() {
        $this->layout = FALSE;
    }
    
    public function ckeditor(){
        $this->layout = "front/home";
        $this->loadModel("EventTemplate");
        $this->set("data",$this->EventTemplate->findById(1));
    }
    
    public function send_simple_message() {
        $this->layout = FALSE;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  curl_setopt($ch, CURLOPT_USERPWD, 'api:pubkey-e2f617eefbaae97a303672101ea39d61');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
  curl_setopt($ch, CURLOPT_URL, 
              'https://api.mailgun.net/v2/samples.mailgun.org/messages');
  curl_setopt($ch, CURLOPT_POSTFIELDS, 
                array('from' => 'Dwight Schrute <dwight@example.com>',
                      'to' => 'dev12sebiz <dev12sebiz@gmail.com>',
                      'subject' => 'The Printer Caught Fire',
                      'text' => 'We have a problem.'));
  $result = curl_exec($ch);
  curl_close($ch);
  pr($result);
  //return $result;
}

  public function email_status()
  {
     $this->layout = FALSE;
     $this->loadModel("CampaignEmail");
     $this->mail_send();
     die;
    
  }
  
    public function email_open_status()
    {
     $this->layout = FALSE;
     $this->loadModel("CampaignEmail");
     $this->mail_opened();
     die;
    }
    
     public function email_clicked_status()
     {
     $this->layout = FALSE;
     $this->loadModel("CampaignEmail");
     $this->mail_clicked();
     die;
    }
    
    public function locationTest($chk = NULL)
    {
	 $this->layout = FALSE;
	 if(isset($chk) && $chk=="two")
	 {
	    echo '<script language="JavaScript" src="http://www.iplocationtools.com/iplocationtools.js?key=687c657c7769616b216d7e7c"></script>
<script language="JavaScript">
<!--
document.write(ip2location_city()+ ", " + ip2location_zip_code() + ", " + ip2location_region() + ", " + ip2location_country_long());
//-->
</script>';
	 }
	 else
	 {
         $ip_request = file_get_contents('http://api.ipinfodb.com/v3/ip-city/?key=%2039bd416ded13373d097fa0c5a1a28f35b5a385f0ba63239da28b9275c1f670f5&ip='.$_SERVER["REMOTE_ADDR"].'&format=json');
         $df = json_decode($ip_request);
	 echo "<pre>";
        print_r($df);
       echo "</pre>";
	 }
	
       die;
    }
    
    

}
?>