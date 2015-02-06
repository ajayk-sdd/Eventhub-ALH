<h2>Feedback Form</h2>
<?php
// display form if user has not clicked submit
if (!isset($_POST["submit"])) {
  ?>
  <form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
  From: <input type="text" name="from"><br>
  Subject: <input type="text" name="subject"><br>
  Message: <textarea rows="10" cols="40" name="message"></textarea><br>
  <input type="submit" name="submit" value="Submit Feedback">
  </form>
  <?php
} else {    // the user has submitted the form
  // Check if the "from" input field is filled out
  if (isset($_POST["from"])) {
    $from = $_POST["from"]; // sender
	$to = "dev12sebiz@gmail.com";
    $subject = $_POST["subject"];

  $message = "<p>This email contains HTML Tags!</p>
<table>
<tr>
<th>Firstname</th>
<th>Lastname</th>
</tr>
<tr>
<td>John</td>
<td>Doe</td>
</tr>
</table>";
 $tracker = 'http://192.168.0.5:8045/test-email2.php';
    
    //Add the tracker to the message.
    $message .= '<img border="0" src="'.$tracker.'" width="1" height="1" />';


// Always set content-type when sending HTML email
 $headers = "From: $from  <".$from.">\r\n";
    $headers.= "Return-Path: " . $from . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "Disposition-Notification-To:dev12sebiz@gmail.com"."\r\n";

mail($to,$subject,$message,$headers);
    echo "Thank you for sending us feedback";
  }
}
?>
