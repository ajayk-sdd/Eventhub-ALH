<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title><?php echo $title_for_layout;?></title>
<script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<?php
	echo $this->Html->css('admin/style');
        echo $this->Html->css('admin/jqtransform');
	echo $this->Html->script('jquery-1.10.2');
	echo $this->Html->script('admin/jquery.jqtransform');
?>
<script type="text/javascript">
	$(document).ready(function(){
		$('.jqTransformSelectWrapper').css('z-index','auto');
		$('.jqTransformSelectWrapper ul').css('z-index','1').css('border-radius','4px');
	});
	$(function(){
		$('.select_new, .radio_new, .check_new').jqTransform({imgPath:'images/'});
	});
</script>
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
