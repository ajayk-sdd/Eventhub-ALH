<html>
<head>
<title>A Test Event</title>
</head>

<body>
<form method="POST">
User: <input type="text" name="user" size="10" /> <br />
Password: <input type="password" name="password" size="15" /> <br />
Event ID: <input type="text" name="id" size="20" value="E0-001-000249321-2" /> <br />
<input type="submit" value="Show Event" />
</form>

<?php if ($_REQUEST['id']) { ?>

<p>An example event:</p>

<pre>
<?php
    require 'Eventful.php';
    
    // Enter your application key here. (See http://api.eventful.com/keys/)
    $app_key = 'qGt8LCwr2CVprzVW';
    
    // Authentication is required for some API methods.
    $user     = $_REQUEST['user'];
    $password = $_REQUEST['password'];

    $ev = &new Services_Eventful($app_key);
    echo "<pre>";print_r($ev);die;
    if ($user and $password)
    {
      $l = $ev->login($user, $password);
      
      if ( PEAR::isError($l) )
      {
          print("Can't log in: " . $l->getMessage() . "\n");
      }
    }
    
    // All method calls other than login() go through call().
    $args = array(
      'id' => $_REQUEST['id'],
    );
    $event = $ev->call('events/get', $args);
    
    if ( PEAR::isError($event) )
    {
        print("An error occurred: " . $event->getMessage() . "\n");
        print_r( $ev );
    }
    
    // The return value from a call is a SimpleXML data object.
    echo "<pre>";print_r( $event );
        
?>
</pre>

<?php } ?>

</body>
</html>