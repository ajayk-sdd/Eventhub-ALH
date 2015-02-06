<?php
/**
 *
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
            <?php echo $cakeDescription ?>:
            <?php echo $title_for_layout; ?>
        </title>
        <script>
            var base_url = '<?php echo BASE_URL; ?>';
        </script>
        <?php
        echo $this->Html->meta('icon');

        echo $this->Html->css('cake.generic');

        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
        echo $this->Html->css('admin/jqtransform');
        echo $this->Html->script('jquery-1.10.2');
        //echo $this->Html->script('custom');
        echo $this->Html->script('admin/jquery.jqtransform');
        echo $this->Html->css('jquery.datetimepicker');
        echo $this->Html->script('jquery.datetimepicker');
        echo $this->Html->script('jquery.textareaCounter.plugin');
        echo $this->Html->css('style');
        echo $this->Html->script('Front/jquery.bxslider.min');
        echo $this->Html->css('jquery.bxslider');
        echo $this->Html->script('custom');
        echo $this->Html->css('validationEngine.jquery');
        echo $this->Html->script('jquery.validationEngine');
	echo $this->Html->script('jquery.validationEngine-en');
        ?>
        <script
            src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&sensor=false">
        </script>
        <script type="text/javascript">var switchTo5x = true;</script>
        <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
        <script type="text/javascript">stLight.options({publisher: "42c4e715-8240-4e83-913a-258cfed42fb7", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
    </head>
    <body>
        <div id="container">
            <div id="header">
                <h1><?php echo $this->Html->link($cakeDescription, 'http://cakephp.org'); ?></h1>
            </div>
            <div id="content">

                <?php echo $this->Session->flash(); ?>

                <?php echo $this->fetch('content'); ?>
            </div>
            <div id="footer">
                <?php
                echo $this->Html->link(
                        $this->Html->image('cake.power.gif', array('alt' => $cakeDescription, 'border' => '0')), 'http://www.cakephp.org/', array('target' => '_blank', 'escape' => false)
                );
                ?>
            </div>
        </div>
        <?php echo $this->element('sql_dump'); ?>
    </body>
</html>
