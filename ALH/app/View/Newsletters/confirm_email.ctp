<?php
//pr($datas);
?>


<div class="center-block">
    <div class="em-sec">
        <?php if ($message == "Thanks for confirm your email.") { ?>
            <h1>Thank You</h1>
        <?php } else {
            echo "<br><br>";
        } ?>
        <p><center><?php echo $message; ?></center></p>
        <p><center><a href="<?php echo "http://".$_SERVER['HTTP_HOST']; ?>">Back to Home</a><center></p>
    </div>
    <div class="clear"></div>
    <br> <br> <br>

</div>
