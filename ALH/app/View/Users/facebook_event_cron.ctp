<section class="inner-content">
    <div class="center-block">
        <div class="repo-list-wrap">
         <?php
         define('BASE_URL','http://'.$_SERVER['HTTP_HOST']);
         define('PARENT_URL',"http://www.alisthub.com");
?>

 <script>
            var base_url = '<?php echo BASE_URL; ?>';
        </script>
 <?php
         echo $this->Html->script('jquery-1.10.2');
          echo $this->Html->script('jquery-ui');
          echo $this->Html->css('front/style');
         echo $this->Html->script('/js/Front/facebook');
?>
                <script type="text/javascript">
  
         setTimeout(function () {
                $( "#foo" ).trigger( "click" );
            }, 4000);
    
    //facebookEventsList(<?php echo $reply;?>,<?php echo $id;?>,<?php echo $after;?>);
   
                
            </script>
                <?php
                echo $this->Html->image('../img/logo.png')
                ?>
                <a id="foo" style="visibility: hidden;" onclick="javascript:facebookEvents();" href="javascript:void(0);" class="violet_button">Click Here</a>
   <div style="font-size:19px;color:#7900A0"> <span id="event_came" style="font-weight:normal;">Please wait while Events are loding from Facebook. It can take few minutes. Please do not refresh & close the window during the process.<br><br></span>
   <div align="center"  style="display: none;float:none;" id="load_fb" class="loader"><img style="border:0px;width:auto;" alt="" src="/img/loader-fb.gif"></div>
   </div>
            
        </div>
    </div>
</section>
