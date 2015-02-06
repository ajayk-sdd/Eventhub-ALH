<section class="inner-content">
    <div class="center-block">
        <div class="repo-list-wrap">
            <h1>Change Password</h1>
            <div class="repo-list reli-II">
                <?php
                echo $this->Session->flash();
                echo $this->Form->create("User", array("action" => "changePassword", "id" => "addEvent"));
                ?>
                <dl>
                   
                 <dt>Old Password</dt>
                    <dd><?php echo $this->Form->input('old_password', array('type' => 'password', 'label' => FALSE, 'class' => 'validate[required]', 'div' => false, 'id' => 'old_pass', 'placeholder' => "Enter Old Password", "pattern" => ".{6,20}", "title" => "6-20 characters", "maxlength" => "20")); ?></dd>

                    <dt>New Password</dt>
                    <dd><?php echo $this->Form->input('new_password', array('type' => 'password', 'label' => FALSE, 'class' => 'validate[required]', 'div' => false, 'placeholder' => 'Enter New Password', "pattern" => ".{6,20}", "title" => "6-20 characters", "maxlength" => "20")); ?></dd>

                    <dt>Confirm Password</dt>
                    <dd><?php echo $this->Form->input('re_password', array('type' => 'password', 'label' => FALSE, 'class' => 'validate[required]', 'div' => false, 'placeholder' => 'Confirm Password',  "pattern" => ".{6,20}", "title" => "6-20 characters", "maxlength" => "20")); ?></dd>

                </dl>
                <div style="text-align:center;">
                    <input type="submit" value="Save Changes">
                    <input type="button" onclick="javascript:history.back();" value="Go Back"> 
                </div>
                <div class="clear"></div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</section>
<?php echo $this->Html->script('/js/Front/Events/createEvent'); ?>
