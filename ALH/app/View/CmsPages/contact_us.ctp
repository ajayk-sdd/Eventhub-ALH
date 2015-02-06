<!--Sign Up Modal Starts-->

<section class="inner-content">
    <div class="center-block">
        <div class="repo-list-wrap">
            <h1>Contact Us</h1>
	    <?php echo "<p  style='color: #7900A0;
    text-align: center;'>".$message."</p>"; ?>
	    
            <div class="repo-list reli-II">
                
                <?php echo $this->Form->create("CmsPage", array("action" => "contactUs", "id" => "contact_us"));
                ?>
                <dl>
                  
                    <dt>Name: </dt>
                    <dd>
			<?php echo $this->Form->input("name", array("type" => "text", "label" => false, "div" => false, "class" => "validate[required,custom[onlyLetterSp]] text-input", "id" => "")); ?>
                    </dd>

                    <dt>Email: </dt>
                    <dd>
			<?php echo $this->Form->input("email", array("type" => "text", "label" => false, "div" => false, "class" => "validate[required,custom[email]] text-input", "id" => "")); ?>
                    </dd>

                    <dt>Subject: </dt>
                    <dd>
		        <?php $options = array('Advertising Related'=>'Advertising Related','Billing Questions'=> 'Billing Questions', 'General Enquiry'=>'General Enquiry','Services/Business Enquiry'=>'Services/Business Enquiry');
			      echo $this->Form->input('subject', array("label" => false, "div" => false, 'type' => 'select', 'options' => $options));?>
		    </dd>

                    <dt>Message: </dt>
                    <dd>
			<?php echo $this->Form->input("message", array("type" => "textarea", "label" => false, "div" => false, "class" => "validate[required,,minSize[20]] form_input", "id" => "short_description","cols"=>"44","rows"=>"6"));?>
                    </dd>
                   
                </dl>
                <div style="text-align:center;">
                    <input type="submit" value="Send">
                    <input type="button" onclick="javascript:history.back();" value="Go Back"> 
                </div>
                <div class="clear"></div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</section>

<!--Sign Up Modal Ends-->
<script type="text/javascript">
    $(document).ready(function(){
        
       $("#contact_us").validationEngine();
    });
</script>