<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <title><?php echo $title_for_layout; ?></title>
        <script>
            var base_url = '<?php echo BASE_URL; ?>';
        </script>
        <?php
		//pr($this->params);
        echo $this->Html->css('front/style');
		if($this->params["params"]["action"] != "viewEvent"){
		echo $this->Html->css('front/jquery.bxslider');	
			}
        
        echo $this->Html->css('front/media');
        echo $this->Html->css('front/jquery.timepicker');
        echo $this->Html->script('jquery-1.10.2');
        echo $this->Html->script('admin/jquery.jqtransform');
        echo $this->Html->css('jquery.datetimepicker');
        echo $this->Html->script('jquery.datetimepicker');
        echo $this->Html->script('jquery.textareaCounter.plugin');
        echo $this->Html->script('jquery.watermark.min');
        echo $this->Html->script('masked');
	echo $this->Html->script('jquery-ui');

        //echo $this->Html->script('Front/lib');
        echo $this->Html->script('Front/jquery.bxslider.min');
        echo $this->Html->script('Front/jquery.timepicker.min');
        echo $this->Html->script('custom');
        echo $this->Html->script('Front/modal');
        echo $this->Html->css('validationEngine.jquery');
        echo $this->Html->script('jquery.validationEngine');
        echo $this->Html->script('jquery.validationEngine-en');
        echo $this->Html->script('tinymce.min');
        // new js added
        //echo $this->Html->script('Front/modal');
        echo $this->Html->script('Front/jquery.elastislide');
        echo $this->Html->script('Front/modernizr.custom.17475');

        // new css added
        echo $this->Html->css('elastislide');
        ?>

        <script>
            $(document).ready(function() {
                $('.mobile-nav').click(function(e) {
                    e.preventDefault();
                    $('.nav').slideToggle();
                });
                $('.bxslider').bxSlider({
                    captions: true,
                    mode: 'fade',
                    auto: true,
                    pager: true
                });
                $('.slider1').bxSlider({
                    slideWidth: 135,
                    minSlides: 1,
                    maxSlides: 8,
                    slideMargin: 10,
                    controls: true,
                    pager: false
                });
            });
        </script>



        <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Ubuntu:400,700' rel='stylesheet' type='text/css'>
        <script
            src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&sensor=false">
        </script>
        <script type="text/javascript">var switchTo5x = true;</script>
        <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
        <script type="text/javascript">stLight.options({publisher: "42c4e715-8240-4e83-913a-258cfed42fb7", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
              <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
              <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
            <![endif]-->
    </head>

    <body>
        <input type="hidden" value="0" id = "I_will_warn_you">
        <header class="header">
            <?php echo $this->Element('front/header'); ?>
        </header>
        <?php echo $this->Element('front/login'); ?>
        <?php echo $this->Element('front/register'); ?>
        <section class="inner-content">
            <?php echo $this->fetch('content'); ?>
            <!--Bottom Details Section Ends-->
        </section>
        <!--Footer Section Starts-->
        <footer>
            <?php echo $this->Element('front/footer'); ?>

        </footer>
        <!--Footer Section Ends-->


    </body>
</html>
