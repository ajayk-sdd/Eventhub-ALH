<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php
	echo $this->Html->css('admin/style');
	echo $this->Html->css('validationEngine.jquery');
?>
<title><?php echo $title_for_layout;?></title>
<script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<?php 
	echo $this->Html->script('jquery-1.10.2');
	echo $this->Html->script('jquery-ui-1.10.4.min');
	echo $this->Html->script('admin/custom');
	echo $this->Html->script('jquery.watermark.min');
	echo $this->Html->script('jquery.validationEngine');
	echo $this->Html->script('jquery.validationEngine-en');
	
?>
</head>
<body>
	<?php echo $this->fetch('content'); ?>
</body>
</html>
