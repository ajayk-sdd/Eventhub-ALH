<section id="login_wrapper">
<?php echo $this->Session->flash();
	echo $this->Form->create("User", array("action"=>"reset","id"=>"ChangePwdForm"));?>
        <ul class="login_form">
            <li>
	<?php
		echo $this->Form->input("token", array("type"=>"hidden","value"=>$token));
		echo $this->Form->input("userId", array("type"=>"hidden","value"=>$userId));
		echo $this->Form->input("password", array("type"=>"password","label"=>"Password :*","div"=>false,"class"=>"validate[required] form_input","id"=>"pwd_reset"));
	?>
            </li>
            <li>
		<?php echo $this->Form->input("cpassword", array("type"=>"password","label"=>"Conf. Password :*","div"=>false,"class"=>"validate[required,equals[pwd_reset]] form_input","id"=>"cpwd_reset"));?>
				
            </li>
        </ul>
        <section class="login_btn">
        	<span class="blu_btn_lt"><?php echo $this->Form->submit('Change Password',array('class'=>'blu_btn_rt')); ?></span>
        </section>
        <section class="clr_bth"></section>
<?php echo $this->Form->end();?>
    </section>
<?php echo $this->Html->script('/js/admin/Users/admin_login');?>
	