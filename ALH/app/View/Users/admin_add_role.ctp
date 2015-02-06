<!--Content Wrapper Starts-->
            <section id="cont_wrapper">
            	<!--Content Starts-->
                <section class="content">
                	<!--Main Heading Starts-->
                    <h1 class="main_heading">Form</h1>
                    <!--Main Heading Ends-->
                    <!--Conetnt Info Starts Here-->
                    <section class="content_info">
		<?php echo $this->Form->create("User", array("action"=>"addRole","id"=>"AddroleForm"));?>
                    	<?php echo $this->Form->input("Role.id", array("type"=>"hidden"));?>
                        <ul class="form">
				<li>
					<label>Role :</label>
					<?php echo $this->Form->input("Role.name", array("type"=>"text","label"=>false,"div"=>false,"class"=>"validate[required] form_input","id"=>"AddRole"));?>
				</li>
				 <section class="login_btn">
				 <span class="blu_btn_lt">
					<?php echo $this->Form->input("Reset", array("type"=>"reset","label"=>false,"div"=>false,"class"=>"blu_btn_rt"));?>
				</span>
				<span class="blu_btn_lt">
					
					<?php echo $this->Form->input("Submit", array("type"=>"submit","label"=>false,"div"=>false,"class"=>"blu_btn_rt"));?>
				</span>
				</section>
				
                        </ul>
			
		<?php echo $this->Form->end();?>
                        <section class="clr_bth"></section>
                    </section>
                    <!--Conetnt Info Ends Here-->
                </section>
                <!--Content Ends-->
            </section>
<?php echo $this->Html->script('/js/admin/Users/admin_add_role');?>