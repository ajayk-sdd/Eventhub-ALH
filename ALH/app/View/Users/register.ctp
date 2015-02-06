<section class="inner-content">
    <div class="center-block">
        <div class="repo-list-wrap">
            <h1>Update Profile</h1>
            <div class="repo-list reli-II">
                <?php
                echo $this->Form->create("User", array("action" => "register", "id" => "addEvent"));
                echo $this->Form->input("list", array("type" => "hidden", "div" => false, "value" => "roles"));
                echo $this->Form->input("User.id", array("type" => "hidden"));
                ?>
                <dl>
                  
                    <dt>Email-Id</dt>
                    <dd><?php echo $this->Form->input("email", array("type" => "text", "label" => FALSE, "div" => FALSE, "class" => "validate[required,custom[email]] ", "id" => "email")); ?></dd>

                    <dt>First Name</dt>
                    <dd><?php echo $this->Form->input("first_name", array("type" => "text", "label" => FALSE, "div" => false, "class" => "validate[required] ", "id" => "fname")); ?>
                    </dd>

                    <dt>Last Name</dt>
                    <dd><?php echo $this->Form->input("last_name", array("type" => "text", "label" => FALSE, "div" => false, "class" => "validate[required] ", "id" => "lname")); ?>
                    </dd>

                    <dt>Phone</dt>
                    <dd><?php echo $this->Form->input("phone_no", array("type" => "text", "label" => FALSE, "div" => false, "class" => "validate[required] ", "id" => "phone_no")); ?>
                    </dd>
                    <dt>Address</dt>
                    <dd><?php echo $this->Form->input("address", array("type" => "textarea", "label" => FALSE, "div" => false, "class" => "validate[required] reg_add", "id" => "address")); ?>
                    </dd>
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
