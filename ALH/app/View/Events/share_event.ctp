<section class="inner-content">
    <div class="center-block">
        <div class="em-sec">
            <h1>Event Marketing</h1>
            <div class="breadcrumb">
                <ul>
                    <li>Step 1: Event Details</li>
                    <li>Step 2: Confirm Details</li>
                    <li>Step 3: Event Marketing</li>
                    <li class="active">Step 4: Share Your Event</li>
                </ul>
            </div>
            <p class="cong-msg">Now that your event page is live, share it with your friends and social networks.  Copy and paste the link below anywhere you'd like people to know about your event.  Then send emails, and share the event page easily on FaceBook by clicking the button below</p>
            <div class="clear"></div>
            <div class="event-final-desc">
                 <div id="result" style="margin-bottom: 10px;color: green"></div>
                <h3>My Event URL</h3>
                <input type="text" value="<?php echo "http://".$url; ?>">
                <br> <br>
                <h3>Invite My Friends Via Email</h3>
                <a href="javascript:void(0);" class="event-share-link" onclick="javascript:$('#openinviter').show(600);">Share Via Email</a>
                <br>
                <div id = "openinviter" style="display:none;" class="emsi-part em-pa-lt">
                    <h2>Import Emails using third party (Gmail, Yahoo etc)</h2>
                    <br/>
                <!-- Include the script anywhere on your page -->
		<script>
		// set any options here, for this example, we'll simply populate the contacts in the textarea above
		window.csPageOptions = {
		  textarea_id:"contact_list"
		};
		// Asynchronously include the widget library.
		(function(u){
		  var d=document,s='script',a=d.createElement(s),m=d.getElementsByTagName(s)[0];
		  a.async=1;a.src=u;m.parentNode.insertBefore(a,m);
		})('//api.cloudsponge.com/widget/4d63f2bd9a40094c7f2895c2d70a0d9eee26ffcc.js');
		</script>
               
                               
                <a class="cs_import btn-all">Click Here to fetch User Email-Id</a>
                
                <?php echo $this->Form->input("contact_list_email", array('type' => 'textarea', "label" => false, "div" => false, "class" => "validate[required] form_input email-textarea", "id" => "contact_list", "style" => "width:100%","readonly" => "readonly")); ?>
                 
                
               
                     <br><br>
                    <label>
                   
                        <input type="button" value="Submit" onclick="javascript:fetchEmail();" style="background-color: #1273C4; color: #FFFFFF; padding: 5px 12px;border-radius: 3px;"> 
                       
                    </label>
                </div>
                <br/>
               
                <h3>Share Event to Facebook</h3>
               <a id="shareButton" href="javascript:void(0);" class="event-share-link">FB Share</a>
                <!--div class="fb-share-button event-share-link fb-share" data-href="<?php echo 'http://'.$url; ?>" data-type="button"></div-->
                <!--                <a href="#" class="fb-share-button event-share-link fb-share">FB Share</a>-->
            </div>
        </div>
    </div>
</section>
<script>
    function fetchEmail() {
        $("#load").show();
        var list = $("#contact_list").val();
        if (list.trim() == "") {
            alert("List is empty.");
            $("#load").hide();
        } else {
            jQuery.ajax({
                url: "/Events/fetch/" + list,
                type: "post",
                success: function(result) {
                    $("#load").hide();
                    $("#openinviter").hide();
                    $("#result").html(result);
                   // $("#message").val("<?php echo $url; ?>");
                  
                }
            });
        }


    }
</script>


<script>
    $('#shareButton').click(function() {
    FB.ui({
        method: 'feed',
        link: '<?php echo 'http://'.$url; ?>',
        caption: 'ALIST HUB',
    }, function(response){
console.log(response);
        if (response === null || response === undefined) {
              $("#result").css({"color":"red"});
             $("#result").html('Event Sharing via Facbook has been failed.');
             
        } else {
            $("#result").css({"color":"green"});
            $("#result").html('Event has been Successfully Shared via Facebook.');
          
        }
    });
});
    </script>
               
