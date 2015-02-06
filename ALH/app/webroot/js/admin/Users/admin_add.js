$(document).ready(function(){
       $("#addUserForm").validationEngine();
       $('#email').watermark('Enter Email');
       $('#uname').watermark('Enter Username');
       $('#pwd').watermark('Enter Password');
       
       
});