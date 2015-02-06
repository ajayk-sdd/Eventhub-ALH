<div class="center-block">
    <div class="logo"> <?php echo $this->Html->link($this->Html->image('../img/logo.png'), array("controller" => "Users", "action" => "index"), array('escape' => false)); ?> </div>
    <div class="signup-block">
        <?php if (!AuthComponent::user('id')) { ?>
            <?php echo $this->Html->link('Sign Up or Sign In', 'javascript:void(0);', array('class' => 'signup-link',"data-toggle"=>"modal", "data-target"=>"#sign_up","id"=>"headSignUp","style"=>"display:none")); ?>
            <?php echo $this->Html->link('Sign Up or Sign In', 'javascript:void(0);', array('class' => 'signup-link last',"data-toggle"=>"modal", "data-target"=>"#sign_in","id"=>"headSignIn")); ?>
        <?php } else {
            ?>
            <?php echo $this->Html->link(AuthComponent::user("username"), BASE_URL.'/users/viewProfile', array('class' => 'signup-link')); ?>
            <?php echo $this->Html->link('Log Out', BASE_URL.'/users/logout', array('class' => 'signup-link')); ?>
            <?php echo $this->Html->link($ALH_point.' Points', 'javascript:void(0);', array('class' => 'signup-link last')); ?>
        <?php }
        ?>
        <?php
        echo $this->Html->link($this->Html->image('../img/front/cart-icon.png'), array("controller"=>"Users","action"=>"cart"), array('escape' => false, 'class' => "cart-link"));
        ?>
    </div>
    <div class="clear"></div>
</div>
<div class="mobile-block">
    <div class="center-block"> <?php echo $this->Html->link('Menu', 'javascript:void(0);', array('class' => 'mobile-nav')); ?></div>
</div>
<!------------------------ nav below -------------------------------------------->
<?php
if (!AuthComponent::user('id')) {
    //echo $this->Element('front/logged_out_nav');
    echo $this->Element('front/premium_member_nav');
} else {
    if (AuthComponent::user("role_id") == 2) {
        echo $this->Element('front/premium_member_nav');
    } else if (AuthComponent::user("role_id") == 3) {
        echo $this->Element('front/premium_member_nav');
    } else if (AuthComponent::user("role_id") == 4) {
        echo $this->Element('front/premium_member_nav');
    } else if (AuthComponent::user("role_id") == 1) {
        echo $this->Element('front/premium_member_nav');
    } else {
        echo $this->Element('front/premium_member_nav');
    }
}
echo $this->Html->css('jquery-ui');
?>
<!------------------------ nav above --------------------------------------------->
<style>
    .ui-dialog{z-index: 9999999;}
</style>
<div id="dialog" title="Basic dialog" style="z-index: 999999;display: none;">
<p>Please click on Fetch Event button if you want to fetch your Facebook Event.</p>
</div>

