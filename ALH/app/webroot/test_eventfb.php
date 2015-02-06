<?php /*<?php function calendar($page_id){?>

<?php
//we have to set timezone to Tel Aviv
date_default_timezone_set('Asia/Tel_Aviv');

//requiring FB PHP SDK
$path = $_SERVER['DOCUMENT_ROOT'];
$path .='/app/webroot/facebook-php-sdk/src/facebook.php';
include ($path);

//initializing keys
$facebook = new Facebook(array(
'appId' => '585437454897001',
'secret' => 'd3d6730eea1823f1b58afbc7a2f21fa9',
'cookie' => true,
));

echo $access_token = $facebook->getAccessToken();

$facebook->setAccessToken($access_token);
// make SURE that the uid (Even ID) is a STRING not an INT

$fql = "SELECT
name, pic, start_time, end_time, location, description
FROM
event
WHERE
eid IN ( SELECT eid FROM event_member WHERE uid = $page_id )
ORDER BY
start_time desc";

$param = array(
'method' => 'fql.query',
'query' => $fql,
'callback' => ''
);
//add this: AND start_time >= now()


$fqlResult = $facebook->api($param);

//looping through retrieved data
foreach( $fqlResult as $keys => $values ){
$month = date( 'n', $values['start_time'] );
$day = date('d', $values['start_time']);
$title = $values['name'];
$i++;


$pubcrawl[$i]['day']	= $day;
$pubcrawl[$i]['month']	= $month;
$pubcrawl[$i]['title']	= $title;


}

// demo purposes only
/*
$pubcrawl[0]['day'] = 12;
$pubcrawl[0]['month'] = 05;
$pubcrawl[0]['title'] = "Pubcrawl 1";
$pubcrawl[1]['day'] = 12;
$pubcrawl[1]['month'] = 05;
$pubcrawl[1]['title'] = "Pubcrawl 2";
$pubcrawl[2]['day'] = 26;
$pubcrawl[2]['month'] = 05;
$pubcrawl[2]['title'] = "Pubcrawl 3";
$pubcrawl[3]['day'] = 02;
$pubcrawl[3]['month'] = 06;
$pubcrawl[3]['title'] = "Pubcrawl 4";
*/
// */
?>
<?php
/*
$monthNames = Array("January", "February", "March", "April", "May", "June", "July",
"August", "September", "October", "November", "December");

if (!isset($_GET["m"])) $_GET["m"] = date("n");

$currentMonth = $_GET["m"];

$p_month = $currentMonth-1;
$n_month = $currentMonth+1;

if ($p_month == 0 ) {
$p_month = 12;
}

if ($n_month == 13 ) {
$n_month = 1;
}
$days=array('1'=>"Sunday",'2'=>"Monday",'3'=>"Tuesday",'4'=>"Wednesday",'5'=>"Thurs",'6'=>"Friday",'7'=>"Saturday");

?>
<table width="960">
<tr align="center">
<td>
<table width="100%">
<tr>
<td width="50%" align="left"> <h3> <a href="<?php echo $_SERVER["PHP_SELF"] . "?m=". $p_month?>"><?php echo $monthNames[$p_month-1];?></a></h3></td>
<td width="50%" align="right"><h3><a href="<?php echo $_SERVER["PHP_SELF"] . "?m=". $n_month?>"><?php echo $monthNames[$n_month-1];?></a> </h3></td>
</tr>
</table>
</td>
</tr>
<tr>
<td align="center">
<table id = "main_calendar" width="100%">
<tr align="center">
<td colspan="7" class="headings" ><h1><?php echo $monthNames[$currentMonth-1]?></h1></td>
</tr>
<tr >
<?php for($i=1;$i<=7;$i++){ ?>
<td align="center" class="headings" ><h3><?php echo $days[$i]; ?></h3></td>
<?php } ?>
</tr>
<?php
$timestamp = mktime(0,0,0,$currentMonth,1,$currentYear);
$maxday = date("t",$timestamp);
$thismonth = getdate ($timestamp);
$startday = $thismonth['wday'];
$end_here = -1;
for ($i=0; $i<50; $i++) {
if ($i == $end_here) break;
//the date being processed
$date = $i-$startday+1;
$lastday = $maxday+$startday;


if(($i % 7) == 0 ) {
echo "<tr>";
$week_tracker++;
}
if ($i==$lastday) $end_here = $week_tracker*7;

//last month
if($i < $startday){
echo "<td class='not_current_month'> </td>";
}
elseif ($i <$lastday) {
echo "<td class = 'current_month' align='center' valign='middle'>";
// Check if there is an event on a given day & echo it
foreach ($pubcrawl as $each_event){
if (($date == $each_event['day'])&&($currentMonth==$each_event['month'])){
echo $each_event['title'];
}
}
echo "<span class = 'day_number'>$date</span>";

echo "</td>";
}
else{

echo "<td class='not_current_month'> </td>";
}


if(($i % 7) == 6 ) echo "</tr>";
}
?>
</table>
</td>
</tr>
</table>
<?php }
calendar('100005801825209'); 
?>

<?php if(isset($logged_in)){ ?>
	<script>
		$(document).ready(function(){
			parent.location.href = '<?php echo $this->Html->url(array('controller'=>'dashboard','action'=>'index','admin'=>false));?>';
		});
	</script>
<?php } ?>
<script src="https://code.jquery.com/jquery.js"></script> 
<div id="sticky-footer-wrapper">
	<div class="with-facebook  static-content-wrapper" id="popup-inner">
		<div id="login-box-header">
			<h3>Login and start learning!</h3>
		</div>

		<div class="clearfix table popup-signup-box" id="login-box-container">
			<div class="loginpopup-top" id="login-fb-wrapper">

					<div class="fb-connect-button-wrapper fb-wrapper">
<a href="javascript:void(0)" onclick="login()" class="fb-connect-button">facebook</a>
					
					</div>

				
				
			</div>
			
		</div>
		<div class="donthvaccunt">
		Don't have an account?
		<a href="#" class="opensignup" style="display:inline; margin-left: 5px;" class="btn btn-primary ud-popup">
		Signup </a>
		</div>
	</div>
</div>
<script>
		$(document).ready(function(){
				$("#UserLoginForm").validationEngine();
				$(".f_field").focus();
				$(document).on('click','.opensignup',function(e){
						window.parent.$('#check_popup').val('2');
						window.parent.$.colorbox.close();
				})
				
				$(document).on('click','.fgpwd',function(e){
						window.parent.$('#check_popup').val('3');
						window.parent.$.colorbox.close();
				})
		});
</script>
<script>
  window.fbAsyncInit = function() {
        FB.init({
            appId: '204006409809581', // App ID
            //channelUrl : '//WWW.YOUR_DOMAIN.COM/channel.html', // Channel File
            status: true, // check login status
            cookie: true, // enable cookies to allow the server to access the session
            xfbml: true  // parse XFBML
        });
    };


    // Load the SDK asynchronously
    (function(d) {
	
        var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
        if (d.getElementById(id)) {
            return;
        }
	
        js = d.createElement('script');
        js.id = id;
        js.async = true;
        js.src = "//connect.facebook.net/en_US/all.js";
        ref.parentNode.insertBefore(js, ref);
    } (document));

    // Here we run a very simple test of the Graph API after login is successful.
    // This testAPI() function is only called in those cases.
    function testAPI() {
        console.log('Welcome!  Fetching your information.... ');
        FB.api('/me', function(response) {
	    
            $.post("",{"data[User]":response},function(data) {

				if(data==2){
						parent.location.reload();
						//window.location.href="<?php echo $this->Html->url(array('controller'=>'dashboard','action'=>'index'));?>";
				   }
            });
        });
    }

    function login() { alert("fsdf");
        FB.getLoginStatus(function(response) {
            
            if (response.status === 'connected') {
                var uid = response.authResponse.userID;
                var accessToken = response.authResponse.accessToken;
		alert(uid);
                testAPI();
            } else if (response.status === 'not_authorized') {
                FB.login(function(response) {
                // handle the response
                testAPI();
                }, {scope: 'email,user_likes'});
            } else {
                // the user isn't logged in to Facebook.
                FB.login(function(response) {
                    // handle the response
                    testAPI();
                }, {scope: 'email,user_likes,user_photos'});
            }
        });
    }
</script><?php */ ?>

<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Display Facebook Events to You Website</title>
 
    <!-- Just adding some style -->
    <style type='text/css'>
    body{
        font-family: "Proxima Nova Regular","Helvetica Neue",Arial,Helvetica,sans-serif;
    }
 
    .clearBoth{
        clear: both;
    }
 
    .event{
        background-color: #E3E3E3;
        margin: 0 0 5px 0;
        padding: 5px;
    }
 
    .eventImage{
        margin: 0 8px 0 0;
    }
 
    .eventInfo{
        padding:5px 0;
    }
 
    .eventName{
        font-size: 26px;
    }
 
    .floatLeft{
        float:left;
    }
 
    .pageHeading{
        font-weight: bold;
        margin: 0 0 20px 0;
    }
    </style>
</head>
 
<body>
<?php
//we have to set timezone to California
date_default_timezone_set('America/Los_Angeles');
 
//requiring FB PHP SDK
require 'facebook-php-sdk/src/facebook.php';
 
//initializing keys
$facebook = new Facebook(array(
    'appId'  => '585437454897001',
    'secret' => 'd3d6730eea1823f1b58afbc7a2f21fa9',
    'cookie' => true,
    'version' => 'v1.0',
    'access_token' => '585437454897001|d3d6730eea1823f1b58afbc7a2f21fa9'
));
 
//just a heading
echo "<div class='pageHeading'>";
    echo "This event list is synchronized with this ";
    echo "<a href='https://www.facebook.com/pages/COAN-Dummy-Page/221167777906963?sk=events'>";
        echo "COAN Dummy Page Events";
    echo "</a>";
    echo " | ";
    echo "<a href='http://www.codeofaninja.com/2011/07/display-facebook-events-to-your-website.html'>";
        echo "Tutorial Link: Display Facebook Events To Your Website with PHP, FQL and jQuery";
    echo "</a>";
echo "</div>";
 
/*
 *-Query the events
 *
 *-We will select:
 *  -name, start_time, end_time, location, description
 *  -but there are other data that you can get on the event table
 *      -https://developers.facebook.com/docs/reference/fql/event/
 * 
 *-As you will notice, we have TWO select statements here because
 *-We can't just do "WHERE creator = your_fan_page_id".
 *-Only eid is indexable in the event table
 *  -So we have to retrieve list of events by eids
 *      -And this was achieved by selecting all eid from
 *          event_member table where the uid is the id of your fanpage.
 *
 *-Yes, you fanpage automatically becomes an event_member once it creates an event
 *-start_time >= now() is used to show upcoming events only
 */
$fql = "SELECT
            name, pic, start_time, end_time, location, description
        FROM
            event
        WHERE
            eid IN ( SELECT eid FROM event_member WHERE uid = 100005801825209 )
        AND
            start_time >= now()
        ORDER BY
            start_time desc";
 
$param  =   array(
    'method'    => 'fql.query',
    'query'     => $fql,
    'callback'  => ''
);
 
$fqlResult   =   $facebook->api($param);
 
//looping through retrieved data
foreach( $fqlResult as $keys => $values ){
    /*
     * see here http://php.net/manual/en/function.date.php
     * for the date format I used.
     * The pattern string I used 'l, F d, Y g:i a'
     * will output something like this: July 30, 2015 6:30 pm
     */
 
    /*  
     * getting start date,
     * 'l, F d, Y' pattern string will give us
     * something like: Thursday, July 30, 2015
     */
    $start_date = date( 'l, F d, Y', strtotime($values['start_time']) );
 
    /*
     * getting 'start' time
     * 'g:i a' will give us something
     * like 6:30 pm
     */
    $start_time = date( 'g:i a', $values['start_time'] );
 
    //printing the data
    echo "<div class='event'>";
 
        echo "<div class='floatLeft eventImage'>";
            echo "<img src={$values['pic']} width='150px' />";
        echo "</div>";
 
        echo "<div class='floatLeft'>";
            echo "<div class='eventName'>{$values['name']}</div>";
 
            /*
             * -the date is displaying correctly, but the time? uh, sometimes it is late by an hour.
             * -it might also depend on what country you are in
             * -the best solution i can give is to include the date only and not the time
             * -you should put the time of your event in the description.
             */
            echo "<div class='eventInfo'>{$start_date} at {$start_time}</div>";
            echo "<div class='eventInfo'>{$values['location']}</div>";
            echo "<div class='eventInfo'>{$values['description']}</div>";
        echo "</div>";
 
        echo "<div class='clearBoth'></div>";
    echo "</div>";
 
}
 
?>
 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script type='text/javascript'>
    //just to add some hover effects
    $(document).ready(function(){
        $('.event').hover(
            function () {
                $(this).css('background-color', '#CFF');
            },
            function () {
                $(this).css('background-color', '#E3E3E3');
            }
        );
    });
</script>
 
</body>
</html>