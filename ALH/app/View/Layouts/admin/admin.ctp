<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title><?php echo $title_for_layout;?></title>
<script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<?php
	echo $this->Html->css('admin/style');
        //echo $this->Html->css('admin/jqtransform');
	echo $this->Html->css('validationEngine.jquery');
	echo $this->Html->css('jquery-ui');
	echo $this->Html->script('jquery-1.10.2');
	echo $this->Html->script('jquery-ui-1.10.4.min');
	echo $this->Html->script('admin/custom');
	echo $this->Html->script('jquery.watermark.min');
	echo $this->Html->script('jquery.validationEngine');
	echo $this->Html->script('jquery.validationEngine-en');
        echo $this->Html->css('jquery.datetimepicker');
	echo $this->Html->script('jquery.datetimepicker');
        echo $this->Html->css('admin/jquery.dataTables');
	echo $this->Html->script('admin/jquery.dataTables');
	 echo $this->Html->script('jquery.textareaCounter.plugin');
         echo $this->Html->script('tinymce.min');
?>

</head>
<body>
	<section id="main_wrapper">
		<?php  echo $this->element('admin/header');
			//echo $this->element('admin/menubar');
			echo $this->fetch('content');
			
		?>
		<section class="push"></section>
	</section>
	
	<?php echo $this->element('admin/footer');?>
	
</body>
</html>
