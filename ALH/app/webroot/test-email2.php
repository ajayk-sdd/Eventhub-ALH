<h2>Feedback Form</h2>
<?php define( 'THIS_ABSOLUTE_PATH', dirname( __FILE__ ) );
// display form if user has not clicked submit
    // the user has submitted the form
  // Check if the "from" input field is filled out

//Begin the header output
    header( 'Content-Type: image/gif' );
    
	$to = "dev12sebiz@gmail.com";
    $subject = "revert test";
  $message = "
<html>
<head>
<title>HTML email</title>
</head>
<body>
<p>This email contains HTML Tags!</p>
<table>
<tr>
<th>Firstname xdfgd</th>
<th>Lastname dfg</th>
</tr>
<tr>
<td>dfgfghfg</td>
<td>uiouio</td>
</tr>
</table>
</body>
</html>
";

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:image/gif" . "\r\n";

// More headers
$headers .= 'From: <dev12sebiz@gmail.com>' . "\r\n";
$headers .= 'Cc: dev12sebiz@gmail.com' . "\r\n";



mail($to,$subject,$message,$headers);
    echo "Thank you for sending us feedback";



    //Get the http URI to the image
    $graphic_http = 'http://192.168.0.5:8045/blank.gif';
    
    //Get the filesize of the image for headers
    $filesize = filesize( THIS_ABSOLUTE_PATH . '/blank.gif' );
    
    //Now actually output the image requested, while disregarding if the database was affected
    header( 'Pragma: public' );
    header( 'Expires: 0' );
    header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
    header( 'Cache-Control: private',false );
    header( 'Content-Disposition: attachment; filename="blank.gif"' );
    header( 'Content-Transfer-Encoding: binary' );
    header( 'Content-Length: '.$filesize );
    readfile( $graphic_http );
    
    //All done, get out!
    exit;
 
?>
