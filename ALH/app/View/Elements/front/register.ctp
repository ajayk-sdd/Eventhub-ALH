<!--Sign Up Modal Starts-->
<div class="modal fade" id="sign_up" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header login-popup-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <!--<h4 class="modal-title" id="myModalLabel">signin to your account</h4>-->
            </div>
            <div class="modal-body login-popup-body">
                <h2>A List Hub</h2>
                <blockquote>Not a member?</blockquote>
                <img src="/img/front/loginfb.png">

                <h5>Or</h5>
                <div class="inner_modal">
                    <?php
                    echo $this->Form->create("User", array("action" => "register", "id" => "addUserForm"));
                    echo $this->Form->input("list", array("type" => "hidden", "div" => false, "value" => "roles"));
                    echo $this->Form->input("User.id", array("type" => "hidden"));
                    echo $this->Form->input("User.status", array("type" => "hidden", "value" => 0));
                    ?>
                    <ul class="form_login form-signup-logn">
                        <li>

                            <?php echo $this->Form->input("User.first_name", array("type" => "text", "label" => false, "div" => false, "class" => "validate[required,custom[onlyLetterSp]] text-input", "id" => "first_name", "pattern" => ".{2,16}", "title" => "2-16 characters", "maxlength" => "16")); ?>
                        </li>
                        <li>

                            <?php echo $this->Form->input("User.last_name", array("type" => "text", "label" => false, "div" => false, "class" => "validate[required,custom[onlyLetterSp]] text-input", "id" => "last_name", "pattern" => ".{2,16}", "title" => "2-16 characters", "maxlength" => "16")); ?>
                        </li>
                        <li>
                            <span style="color: red; float: left; margin-left: 12px;" id="usernameExist"></span>
                            <?php echo $this->Form->input("User.username", array("type" => "text", "label" => false, "div" => false, "class" => "validate[required,minSize[4],maxSize[16],custom[onlyLetterNumber]] text-input", "id" => "username", "pattern" => ".{4,16}", "title" => "4-16 characters", "maxlength" => "16")); ?>
                            <span class="loader" id="load_username" style="display: none; margin-right: -12px;"><img src="/img/admin/loader.gif" alt=""></span>
                        </li>
                        <!--li>
                            
                        <?php //echo $this->Form->input("User.zip", array("type" => "text", "label" => false, "div" => false, "class" => "validate[required] text-input", "id" => "zip","pattern"=>".{5,6}","title"=>"5-6 characters","maxlength"=>"6")); ?>
                        </li-->
                        <li>
                            <span style="color: red; float: left; margin-left: 12px;" id="emailExist"></span>
                            <?php echo $this->Form->input("User.email", array("type" => "text", "label" => false, "div" => false, "class" => "validate[required,custom[email]] text-input", "id" => "email")); ?>
                            <span class="loader" id="load_email" style="display: none; margin-right: -12px;"><img src="/img/admin/loader.gif" alt=""></span>
                        </li>
                        <li>

                            <?php echo $this->Form->input("User.password", array("type" => "password", "label" => false, "div" => false, "class" => "validate[required] text-input", "id" => "password", "pattern" => ".{6,20}", "title" => "6-20 characters", "maxlength" => "20")); ?>
                        </li>
                        <!--li>
                         

                        <?php //echo $this->Form->input("User.phone_no", array("type" => "text", "label" => false, "div" => false, "class" => "validate[required] text-input phone_no", "id" => "phone_no")); ?>
                        </li-->
                        <li>
                            <?php echo $this->Form->input("Get Started", array("type" => "submit", "label" => false, 'class' => 'violet_btn')); ?>
                        </li>
                        <p> <span>Already a member ?</span>   <?php echo $this->Html->link('Sign in here', "javascript:void(0);", array('class' => 'underline',"id" => "sign-in")); ?></p>


                    </ul>
                    <?php echo $this->Form->end(); ?>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>
<!--Sign Up Modal Ends-->

<script type="text/javascript">
    $(document).ready(function() {
        $('.phone_no').mask('(999) 999-9999');

        $("#addUserForm").validationEngine();
        $('#first_name').watermark('First Name');
        $('#last_name').watermark('Last Name');
        $('#username').watermark('Username');
        $('#zip').watermark('Zip Code');
        $('#email').watermark('Email');
        $('#password').watermark('Password');
        $('#phone_no').watermark('Phone No');
        $('#sign-in').click(function() {
            $("#sign_up").modal('toggle');
            $('#headSignIn').trigger('click');

        });
        $("#email").change(function() {
            var email = $(this).val();
            $(".violet_btn").css("display","none");
            if (email.trim() == "") {
                $("#emailExist").html("Email address required.");
            } else if (validateEmail(email)){
                $("#emailExist").html("Checking....");
                $("#load_email").show();
                $.ajax({
                    type: "POST",
                    url: "/Users/checkEmail/"+email,
                    data: {email: email},
                    success: function(data) {
                        if (data.trim() == 1) {
                            $("#emailExist").html('<b style="color:green;">Email Available.</b>');
                            $("#load_email").hide();
                            $(".violet_btn").css("display","block");
                        } else {
                            $("#emailExist").html(data);
                            $("#load_email").hide();
                            $("#email").val("");
                            $(".violet_btn").css("display","block");
                        }
                    },
                    statusCode: {
                        404: function() {
                            $("#emailExist").html("Something went wrong, please try again");
                        }
                    }
                });
            } else {
                $("#emailExist").html("Enter valid email.");
            }
        });
        
        $("#username").change(function() {
            var user = $(this).val();
            $(".violet_btn").css("display","none");
            if (user.trim() == "") {
                $("#usernameExist").html("Username required.");
            } else if($.isNumeric(user) == true)
                {
                    
                    $("#usernameExist").html("Invalid Username. It should be alphanumric.");
                     $("#load_username").hide();
                }
                else if(user.length < 4 || user.length > 16)
                {
                    $("#usernameExist").html("Invalid Username. Min 4 and Max 16 character allowed.");
                     $("#load_username").hide();
                }
                else
                {
                
                $("#usernameExist").html("Checking....");
                $("#load_username").show();
                $.ajax({
                    type: "POST",
                    url: "/Users/checkUserName/"+user,
                    data: {user: user},
                    success: function(data) {
                        if (data.trim() == 1) {
                            $("#usernameExist").html('<b style="color:green;">Username Available.</b>');
                            $("#load_username").hide();
                            $(".violet_btn").css("display","block");
                        } else {
                            $("#usernameExist").html(data);
                            $("#load_username").hide();
                            $("#username").val("");
                            $(".violet_btn").css("display","block");
                        }
                    },
                    statusCode: {
                        404: function() {
                            $("#usernameExist").html("Something went wrong, please try again");
                        }
                    }
                });
            } 
        });
    });
</script>