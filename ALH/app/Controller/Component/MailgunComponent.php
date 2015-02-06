<?php
class MailgunComponent extends Component {
   private $mg_api = 'key-be2dd8dfb0504d24789b2f5d1749b7cf';
    private $mg_version = 'api.mailgun.net/v2/';
    private $mg_domain = "sandbox179ebcab213346269b4225643e1136a8.mailgun.org";
  
   // private $mg_api = 'key-be2dd8dfb0504d24789b2f5d1749b7cf';
   // private $mg_version = 'api.mailgun.net/v2/';
   // private $mg_domain = "alisthub.com";
    
    public function send_mail($from, $to, array $options = null) { 
        $mg_message_url = "https://".$this->mg_version.$this->mg_domain."/messages";
        $options['from'] = $from;
        $options['to'] = $to;
        if(isset($options['recipient-variables']))
        {
        $options['recipient-variables'] = json_encode($options['recipient-variables']);
        }
        //pr($options);
        return $this->_curl_request($mg_message_url, $options);
    }
    
    
    
    public function _curl_request($url, array $options) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        
        curl_setopt ($ch, CURLOPT_MAXREDIRS, 3);
        curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_VERBOSE, 0);
        curl_setopt ($ch, CURLOPT_HEADER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        
        curl_setopt($ch, CURLOPT_USERPWD, 'api:' . $this->mg_api);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        curl_setopt($ch, CURLOPT_POST, true); 
        //curl_setopt($curl, CURLOPT_POSTFIELDS, $params); 
        curl_setopt($ch, CURLOPT_HEADER, false); 
        
        //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $options);
                    
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result,TRUE);
      
    }
    
    
    public function get_mail($nxt=null) {
        //$mg_message_url = "https://".$this->mg_version.$this->mg_domain."/events?event=delivered&message-id=".$messageId;
      if(!empty($nxt))
      {
        $mg_message_url = $nxt;
      }
      else
      {
      $mg_message_url = "https://".$this->mg_version.$this->mg_domain."/events?event=delivered&limit=100";
      }
      // $options['event'] = "sent";
       // $options['event'] = "opened";
        return $this->_curl_requests($mg_message_url);
    }
    
    public function get_mail_opened($nxt=null) {
        //$mg_message_url = "https://".$this->mg_version.$this->mg_domain."/events?event=delivered&message-id=".$messageId;
      if(!empty($nxt))
      {
        $mg_message_url = $nxt;
      }
      else
      {
      $mg_message_url = "https://".$this->mg_version.$this->mg_domain."/events?event=opened&limit=100";
      }
      // $options['event'] = "sent";
       // $options['event'] = "opened";
        return $this->_curl_requests($mg_message_url);
    }
    
     public function get_mail_clicked($nxt=null) {
        //$mg_message_url = "https://".$this->mg_version.$this->mg_domain."/events?event=delivered&message-id=".$messageId;
      if(!empty($nxt))
      {
        $mg_message_url = $nxt;
      }
      else
      {
      $mg_message_url = "https://".$this->mg_version.$this->mg_domain."/events?event=clicked&limit=100";
      }
      // $options['event'] = "sent";
       // $options['event'] = "opened";
        return $this->_curl_requests($mg_message_url);
    }
    
    public function _curl_requests($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        
        curl_setopt ($ch, CURLOPT_MAXREDIRS, 3);
        curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_VERBOSE, 0);
        curl_setopt ($ch, CURLOPT_HEADER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        
        curl_setopt($ch, CURLOPT_USERPWD, 'api:' . $this->mg_api);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        curl_setopt($ch, CURLOPT_HTTPGET, true); 
        //curl_setopt($curl, CURLOPT_POSTFIELDS, $params); 
        curl_setopt($ch, CURLOPT_HEADER, false); 
        
        //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_URL, $url);
        
                    
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result,TRUE);
      
    }
    
}
?>