
<?php

echo $this->Session->flash();
echo $this->Form->create("User", array("action" => "forgot_password", "id" => "LoginForm"));
?>
<?php echo $this->Form->input('email', array("placeholder" => "Email", 'label' => "Email", 'class' => 'validate[required,minSize[3],maxSize[50]] input', 'div' => false, 'id' => 'adminUser', 'maxlength' => 50)); ?>
<?php echo $this->Form->submit('Submit', array('class' => 'blu_btn_rt')); ?>
<?php echo $this->Form->end(); ?>
   
