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
        echo $this->Html->css('front/style');
        echo $this->Html->css('front/jquery.bxslider');
        echo $this->Html->css('front/media');
        echo $this->Html->script('jquery-1.10.2');
       

        //echo $this->Html->script('Front/lib');
       

        // new css added
        
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



       
    </head>

    <body>
        <input type="hidden" value="0" id = "I_will_warn_you">
        <header class="header">
            <div class="logo"> <?php echo $this->Html->image('../img/front/logo.png'); ?> </div>
        </header>
        <?php //echo $this->Element('front/login'); ?>
        <?php //echo $this->Element('front/register'); ?>
        <section class="inner-content">
            <?php echo $this->fetch('content'); ?>
            <!--Bottom Details Section Ends-->
        </section>
        <!--Footer Section Starts-->
        <footer>
            <?php //echo $this->Element('front/footer'); ?>

        </footer>
        <!--Footer Section Ends-->


    </body>
</html>
