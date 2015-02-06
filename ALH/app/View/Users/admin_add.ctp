<!--Content Wrapper Starts-->
            <section id="cont_wrapper">
            	<!--Content Starts-->
                <section class="content">
                	<!--Main Heading Starts-->
                    <h1 class="main_heading"> <?php echo $this->Html->link('Users','/admin/Users/add');?></h1>
                    <!--Main Heading Ends-->
                    <!--Conetnt Info Starts Here-->
                    <section class="content_info">
		<?php echo $this->Session->flash();
		    echo $this->Form->create("User", array("action"=>"add","id"=>"addUserForm"));
		    echo $this->Form->input("list", array("type"=>"hidden","div"=>false,"value"=>"roles"));
                    echo $this->Form->input("User.id", array("type"=>"hidden"));
		 ?>
                    	<ul class="form">
			     <li>
				<?php echo $this->Form->input("first_name", array("type"=>"text","label"=>"First Name:*","div"=>false,"class"=>"validate[required] form_input","id"=>"first_name"));?>
                            </li>
			      <li>
				<?php echo $this->Form->input("last_name", array("type"=>"text","label"=>"Last Name:*","div"=>false,"class"=>"validate[required] form_input","id"=>"last_name"));?>
                            </li>
			       <li>
				<?php echo $this->Form->input("phone_no", array("type"=>"text","label"=>"Phone No:*","div"=>false,"class"=>"validate[required,custom[phone]] form_input","id"=>"phone_no"));?>
                            </li>
                            <li>
				<?php echo $this->Form->input("email", array("type"=>"text","label"=>"Email:*","div"=>false,"class"=>"validate[required,custom[email]] form_input","id"=>"email"));?>
                            </li>
			    <li>
				<?php echo $this->Form->input("username", array("type"=>"text","label"=>"Username:*","div"=>false,"class"=>"validate[required] form_input","id"=>"uname"));?>
                            </li>
                            <?php if(isset($this->data['User']['id']) && !empty($this->data['User']['id'])){}else{?>
			    <li>
				<?php echo $this->Form->input("password", array("type"=>"password","label"=>"Password:*","div"=>false,"class"=>"validate[required,maxSize[20],minSize[6]] form_input","id"=>"pwd"));?>
                            </li> 
                            <?php }?>
			     <li>
			     <section class="select_new">
                            	<?php echo $this->Form->input("role_id", array("type"=>"select", "empty"=>"Select Role", "options"=>$roles,"class"=>"validate[required]","div"=>false,"label"=>"Role:*"));?>
			     </section>
                            </li>
			    
			    <li>
				<section class="select_new">
				
				    <?php $status = array("0"=>"InActive","1"=>"Active");
				    echo $this->Form->input("status", array("type"=>"select", "empty"=>"Select Status", "options"=>$status,"class"=>"","div"=>false,"label"=>"Status :*","default"=>1)); ?>
				</section>
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
            <!--Content Wrapper Ends-->
<?php echo $this->Html->script('/js/admin/Users/admin_add');?>