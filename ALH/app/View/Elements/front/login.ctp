<!--Forgot Modal Starts-->
<div class="modal fade" id="forgot_password" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header login-popup-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <!--<h4 class="modal-title" id="myModalLabel">signin to your account</h4>-->
            </div>
            <div class="modal-body login_body login-popup-body">
                <h2>A List Hub</h2>
                <blockquote>Members: Forgot Password</blockquote>

                <?php //echo $this->Form->create("User", array("action" => "forgot_password", "id" => "LoginForm"));?>
                <ul class="form_login">
                    <li>
                        <div id="msg-box"></div>
                        <div class="input_feilds">

                            <?php echo $this->Form->input('email', array('label' => false, 'class' => 'validate[required,custom[email]] input', 'div' => false, 'id' => 'emailForgot', 'maxlength' => 50)); ?>


                        </div>
                    </li>

                    <li> <span class="loader" id="load-forget" style="display: none;float:none"><img src="/img/admin/loader.gif" alt=""></span>
                        <?php echo $this->Form->button('Submit', array('class' => 'violet_btn', 'id' => 'forgotButton')); ?>
                        <br><br>

                    </li>
                    <li>  <p> <span>Not a member yet ?</span> <?php echo $this->Html->link('Sign Up', "javascript:void(0);", array('class' => 'sign-up underline')); ?></p></li>


                </ul>  	
            </div>
            <?php //echo $this->Form->end();?>
            <div class="modal-footer signin-popup-footer">

            </div>
        </div>
    </div>
</div>

<!--Sign In Modal Starts-->
<div class="modal fade" id="sign_in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header login-popup-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <!--<h4 class="modal-title" id="myModalLabel">signin to your account</h4>-->
            </div>
            <div class="modal-body login_body login-popup-body">
                <h2>A List Hub</h2>
                <blockquote>Members: Sign In Here</blockquote>
                <a href="javascript:void(0);" onclick="javascript:facebookLogin('/Users/index');"><img src="/img/front/fb_icon.png" alt=""/><img src="/img/front/loginfb.png"></a>
                <div class="loader" id="loader_login" style="display: none;float: left; width: 100%;"><img src="/img/admin/loader.gif" alt=""></div>

                <h5>Or</h5>
                <h5 style="color: red;" id="warning">&nbsp;&nbsp;</h5>
                <?php echo $this->Form->create("User", array("action" => "login", "id" => "LoginForm")); ?>
                <ul class="form_login">
                    <li>
                        <div class="input_feilds">

                            <?php echo $this->Form->input('username', array("placeholder" => "Username / Email", 'label' => false, 'class' => 'validate[required,maxSize[50]] input', 'div' => false, 'id' => 'adminUser', 'value' => @$unameCookie, 'maxlength' => 50)); ?>
                        </div>
                    </li>
                    <li>
                        <div class="input_feilds">

                            <?php echo $this->Form->input('password', array('placeholder' => 'Password', 'label' => false, 'class' => 'validate[required] input', 'div' => false, 'id' => 'adminPass', 'value' => @$passCookie, "pattern" => ".{6,20}", "title" => "6-20 characters", "maxlength" => "20")); ?>
                        </div>
                    </li>
                    <li>
                           <?php  echo $this->Form->checkbox('rememberme', array('value' => 1,'style' => 'float:left')) . "<div style='float:left;margin-left:3px;margin-top:-2px'> Remember Me</div>"; ?>

                        <?php echo $this->Html->link('Sign in', "javascript:void(0);", array('class' => 'violet_btn',"id" => "login", "onclick" => "javascript:login();")); ?>
                       
                        <span class="loader" id="load_login" style="display: none; margin-right: 6px;"><img src="/img/admin/loader.gif" alt=""></span>
                    </li>
                    <li>
                        <?php echo $this->Html->link('Forgot your Password?', "javascript:void(0);", array('class' => 'forgot signup-link last underline', 'id'=>'modal-forgot', 'data-toggle'=>'modal', 'data-target'=>'#forgot_password', 'style'=>'display:none;')); ?>
                        <?php echo $this->Html->link('Forgot your Password?', "javascript:void(0);", array('class' => 'forgot underline', 'id'=>'fpassword')); ?>
                    </li>
                 
                    <li>  <p> <span>Not a member yet ?</span>  <?php echo $this->Html->link('Sign Up', "javascript:void(0);", array('class' => 'sign-up underline')); ?></p></li>




                </ul>  	
            </div>
            <?php echo $this->Form->end(); ?>
            <div class="modal-footer signin-popup-footer">

            </div>
        </div>
    </div>
</div>
<?php echo $this->Html->script('/js/Front/facebook'); ?>
<script type="text/javascript">
    function login() {
        var username = $("#adminUser").val();
        var password = $("#adminPass").val();
        if (username.trim() == "" || password.trim() == "") {
            $("#warning").html("Username and password required");
        } else {
            $("#warning").html("Checking....");
            $("#load_login").show();
            $.ajax({
                type: "POST",
                url: "/Users/loginAjax",
                data: {username: username, password: password},
                success: function(data) {
                    if(data.trim() == 1){
                        $("#warning").html('<b style="color:green;">Login successful,Redirecting....<br><img src="/img/admin/loader.gif" alt=""></b>');
                        $("#load_login").hide();
                        $('.form_login').css("display","none");
                        window.location = window.location;
                    } else {
                        $("#warning").html(data);
                        $("#adminPass").val("");
                        $("#load_login").hide();
                    }
                },
                statusCode: {
                    404: function() {
                        $("#warning").html("Something went wrong, please try again");
                        $("#load_login").hide();
                    }
                }
            });
        }
    }
    $(document).ready(function() {
        
            $("#emailForgot").on('keypress', function (e) {
            if(e.which == 13)
            {
                  // check keycode condition       
                  $('#forgotButton').trigger('click');
            }
             });
          
            $("#adminUser").on('keypress', function (e) {
            if(e.which == 13)
            {
                // check keycode condition       
                $('#login').trigger('click');
                login();
            }
            });
            $("#adminPass").on('keypress', function (e) {
            if(e.which == 13)
            {
                // check keycode condition       
                $('#login').trigger('click');
                login();
            }
            });
            
        $("#LoginForm").validationEngine();
        $('#adminUser').watermark('Username / Email');
        $('#adminPass').watermark('Password');
        $('#emailForgot').watermark('Email');

        $('.sign-up').click(function() {
            $("#sign_in").modal('toggle');
            $('#headSignUp').trigger('click');

        });
        $('#fpassword').click(function() {
            $("#sign_in").modal('toggle');
            $('#modal-forgot').trigger('click');

        });
        $('#forgotButton').click(function() {
            var email = $("#emailForgot").val();
            if (!email) {
                //alert("dfgd");
                $("#msg-box").html("Please Enter Email Address");
                $("#msg-box").css({"color": "red", "margin-bottom": "10px", "margin-top": "-2px"});
                return false;
            }
            var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
            if (!filter.test(email)) {
                $("#msg-box").html("Please Enter Valid Email Address");
                $("#msg-box").css({"color": "red", "margin-bottom": "10px", "margin-top": "-2px"});
                return false;
            }
            $("#load-forget").show();
            $.post("<?php echo $this->Html->url(array('controller' => 'Users', 'action' => 'forgot_password')); ?>", {email: email}, function(response) {
                if (response == 'Success') {
                    $("#load-forget").hide();
                    $("#msg-box").html("Password Reset link has been sent to your Email-Id. Please check!");
                    $("#msg-box").css({"color": "green", "margin-bottom": "10px", "margin-top": "-2px"});
                    $("#emailForgot").val("");
                    setTimeout(function () {
                    $('#msg-box').fadeOut('fast');
                    }, 4000);
                }
                else {
                    $("#load-forget").hide();
                    $("#msg-box").html("Email-Id does not Exist.");
                    $("#msg-box").css({"color": "red", "margin-bottom": "10px", "margin-top": "-2px"});
                    setTimeout(function () {
                    $('#msg-box').fadeOut('fast');
                    }, 4000);
                    return false;

                }
            });
        });


    });
</script>
<!--Sign In Modal Ends-->