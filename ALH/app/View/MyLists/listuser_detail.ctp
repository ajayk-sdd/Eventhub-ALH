<?php
#pr($listemail);
?>
<section class="inner-content">
    <div class="center-block">
        <div class="repo-list-wrap">
            <h1>Edit User Detail</h1>
            <div class="repo-list reli-II">
                <?php echo $this->Session->flash(); ?>
                <?php
                
                echo $this->Form->create("MyLists", array("action" => "listuserDetail/".  base64_encode($list_id)."/".base64_encode($listemail), "id" => "editUser"));
                echo $this->Form->input("ListEmail.my_list_id", array("type" => "hidden","value" => $list_id));
                echo $this->Form->input("ListEmail.id", array("type" => "hidden"));
                ?>
                <dl>
                    <dd>&nbsp;</dd>
                    <div class="clear"></div>
                    <dt>Email-Id</dt>
                    <dd><?php echo $this->Form->input("ListEmail.email", array("type" => "text", "label" => FALSE, "div" => FALSE, "class" => "validate[required,custom[email]] ", "id" => "email")); ?></dd>

                    <dt>First Name</dt>
                    <dd><?php echo $this->Form->input("ListEmail.first_name", array("type" => "text", "label" => FALSE, "div" => false, "class" => "validate[required] ", "id" => "fname")); ?>
                    </dd>

                    <dt>Last Name</dt>
                    <dd><?php echo $this->Form->input("ListEmail.last_name", array("type" => "text", "label" => FALSE, "div" => false, "class" => "validate[required] ", "id" => "lname")); ?>
                    </dd>

                    <dt>Phone</dt>
                    <dd><?php echo $this->Form->input("ListEmail.phone", array("type" => "text", "label" => FALSE, "div" => false, "class" => "validate[required] ", "id" => "phone_no")); ?>
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

