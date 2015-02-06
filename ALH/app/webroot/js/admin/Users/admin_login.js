$(document).ready(function(){
       $("#LoginForm").validationEngine();
       $("#ForgotPasswordForm").validationEngine();
       $("#ChangePwdForm").validationEngine();
       $('#adminUser').watermark('Enter Username / Email');
       $('#adminPass').watermark('Enter Password');
       $('#adminForgot').watermark('Enter Email');
       $('#pwd_reset').watermark('Enter Password');
       $('#cpwd_reset').watermark('Confirm Password');
});