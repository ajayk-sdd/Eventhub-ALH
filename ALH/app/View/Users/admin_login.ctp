<section id="login_wrapper">
<?php echo $this->Session->flash();
	echo $this->Form->create("User", array("action"=>"login","id"=>"LoginForm"));?>
        <ul class="login_form">
        	<li>
            	<label>Username / Email :</label>
		<?php echo $this->Form->input('username',array('label'=>false,'class'=>'validate[required,minSize[3],maxSize[50]] input','div'=>false,'id'=>'adminUser','value'=>$unameCookie,'maxlength'=>50));?>
            </li>
            <li>
            	<label>Password :</label>
		<?php echo $this->Form->input('password',array('label'=>false,'class'=>'validate[required] input','div'=>false,'id'=>'adminPass','value'=>$passCookie));?>
            </li>
        </ul>
        <section class="rmbr_frgt">
        <?php echo "<p class='rmbr'>".$this->Form->checkbox('rememberme', array('value' => 1,'checked'=>'checked'))."<span>Remember Me</span></p>";?>
            <p class="pswrd"><a id="paswrd" href="#">Forgot password ?</a></p>
        </section>
        <section class="login_btn">
        	<span class="blu_btn_lt"><?php echo $this->Form->submit('Login',array('class'=>'blu_btn_rt')); ?></span>
        </section>
        <section class="clr_bth"></section>
<?php echo $this->Form->end();?>
    </section>
    <!--Forgot Wrapper Starts-->
    <?php echo $this->Form->create("User", array("action"=>"forgotPassword","id"=>"ForgotPasswordForm"));?>
    <section style="display:none;" id="forgot_wrapper">   
    	<p class="send">Please send us your email and we'll reset your password.</p>
        <ul class="login_form">
        	<li>
            	<label>Email :</label>
              	<?php echo $this->Form->input('email',array('label'=>false,'class'=>'"validate[required,custom[email]] input','div'=>false,'id'=>'adminForgot'));?>
            </li>
        </ul>
        <section class="login_btn">
        	<span class="blu_btn_lt"><input class="blu_btn_rt" value="Submit" type="submit"/></span>
        </section>
        <p class="back"><a id="back" href="#">Back to login form</a></p>
        <section class="clr_bth"></section>
    </section>
<?php echo $this->Html->script('/js/admin/Users/admin_login');?>
	