<section class="inner-content">
    <div class="center-block">
        <div class="em-sec">
            <h1>Preview Compaign</h1>
            <div class="breadcrumb">
                <ul>
                    <li>Step 1: Design</li>
                    <li>Step 2: Set Up</li>
                    <li class="active">Step 3: Preview</li>
                    <li>Step 4: Recipients</li>
                </ul>
            </div>
            <div class="clear"></div>
            <div class="mo-sec-inner previewcampaign-whole">

                    <div class="previewcampgn-inner">
                    	<h3>Please review the details of your campaign before sending:</h3>
                        <table>
                        	<thead>
                            	<th>Events Choosen</th>
<!--                                <th>Date of Event</th>-->
                            </thead>
                            
                            <tbody>
                                <?php foreach ($event as $key => $value): ?>
                            	<tr>
                                    <td><a href="#" class="evntchoosen" id = "addedEvent<?php echo '_' . $key; ?>"> <?php echo $value; ?></a></td>
<!--                                    <td>2-14-5 <a href="#">Edit</a> <a href="#">Remove</a></td>-->
                                </tr>
                                <?php endforeach; ?>
                                
                                
                            </tbody>
                        </table>
                        
                        <div class="previewinner-sbmt-btn">
                         <a href="#" class="violet_button">Change</a>
                        </div>
                    </div>
              
            </div>
            
            
            <a href="/Campaigns/setUp/<?php echo base64_encode($campaign['Campaign']['id']); ?>" class="violet_button">Back</a>
            <a href="/Campaigns/campaignRecipient/<?php echo base64_encode($campaign['Campaign']['id']); ?>" class="violet_button">Next</a>
            <br/>
            
            <div class="offer-ld previewcampaign-img">
            	<h3>Preview Email</h3>
			<?php echo $campaign["Campaign"]["html"] ?>
                    
                </div>
            
            
            <span>Preview</span><br>
            <div style="border: 1px solid blueviolet; width: 640px; height: auto; padding: 20px;">
                <?php echo $campaign["Campaign"]["html"] ?>
            </div>

            <div class="clear"></div>
            <div class="breadcrumb">
                <ul>
                    <li>Step 1: Design</li>
                    <li>Step 2: Set Up</li>
                    <li class="active">Step 3: Preview</li>
                    <li>Step 4: Recipients</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!--Bottom Details Section Ends-->
<script>
    function remove_event(id) {
        jQuery.ajax({
            url: '/Campaigns/removeEvent/' + id,
            success: function(data) {
                if (data == 1) {
                    $("#addedEvent_" + id).remove();
                } else {
                    alert(data);
                }


            }
        });
    }
</script>