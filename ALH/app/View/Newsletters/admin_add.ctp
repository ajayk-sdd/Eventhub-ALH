<!--Content Wrapper Starts-->
            <section id="cont_wrapper">
            	<!--Content Starts-->
                <section class="content">
                	<!--Main Heading Starts-->
                    <h1 class="main_heading"> <?php echo $this->Html->link('Newsletters','/admin/Newsletters/add');?></h1>
                    <!--Main Heading Ends-->
                    <!--Conetnt Info Starts Here-->
                    <section class="content_info">
		<?php echo $this->Session->flash();
		    echo $this->Form->create("Newsletter", array("action"=>"add","id"=>"addUserForm"));
		   
                    echo $this->Form->input("Newsletter.id", array("type"=>"hidden"));
		 ?>
                    	<ul class="form">
                            <li>
				<?php echo $this->Form->input("Newsletter.email", array("type"=>"text","label"=>"Email :*","div"=>false,"class"=>"validate[required,custom[email]] form_input","id"=>"email"));?>
                            </li>
			    
			    
			    <li>
				<section class="select_new">
				
				    <?php $status = array("0"=>"InActive","1"=>"Active");
				    echo $this->Form->input("status", array("type"=>"select", "empty"=>"Select Status", "options"=>$status,"class"=>"","div"=>false,"label"=>"Status :*","default"=>1));?>
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