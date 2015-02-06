<?php
$clientid = '999653454951-9llk1o0ef9h1rr1d049en5rccsno0r2e.apps.googleusercontent.com';
$clientsecret = 'L8kvc6Syi4sJzl_u0tIg_MIh';
$redirecturi = 'http://192.155.246.146:7048/oauth2callback'; 
$maxresults = 50; // Number of mail id you want to display.
?>
<a href="https://accounts.google.com/o/oauth2/auth?client_id=
<?php print $clientid;?>&redirect_uri=<?php print $redirecturi; ?>
&scope=https://www.google.com/m8/feeds/&response_type=code">
Invite Friends From Gmail</a>
<?php
if(isset($_GET["code"]) && !empty($_GET["code"])){
$authcode = $_GET["code"];
$fields=array(
'code'=> urlencode($authcode),
'client_id'=> urlencode($clientid),
'client_secret'=> urlencode($clientsecret),
'redirect_uri'=> urlencode($redirecturi),
'grant_type'=> urlencode('authorization_code') );

$fields_string = '';
foreach($fields as $key=>$value){ $fields_string .= $key.'='.$value.'&'; }
$fields_string = rtrim($fields_string,'&');

$ch = curl_init();//open connection
curl_setopt($ch,CURLOPT_URL,'https://accounts.google.com/o/oauth2/token');
curl_setopt($ch,CURLOPT_POST,5);
curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($ch);
curl_close($ch);

$response = json_decode($result);
$accesstoken = $response->access_token;
if( $accesstoken!='')
$_SESSION['token']= $accesstoken;
$xmlresponse= file_get_contents('https://www.google.com/m8/feeds/contacts/
default/full?max-results='.$maxresults.'&oauth_token='. $_SESSION['token']);

$xml= new SimpleXMLElement($xmlresponse);
$xml->registerXPathNamespace('gd', 'http://schemas.google.com/g/2005');
$result = $xml->xpath('//gd:email');
$count = 0;
foreach ($result as $title) {
$count++;
echo $count.". ".$title->attributes()->address . "<br><br>";
}}
?>