<!--Content Wrapper Starts-->
            <section id="cont_wrapper">
            	<!--Content Starts-->
                <section class="content">
                	<!--Main Heading Starts-->
                    <h1 class="main_heading">Add Newsletter</h1>
                    <!--Main Heading Ends-->
                    <!--Conetnt Info Starts Here-->
                    <section class="content_info">
		    <?php echo $this->Session->flash();
			echo $this->Form->create("Newsletter", array("action"=>"addEmail","id"=>"addCmsForm"));
			 echo $this->Form->input("NewsletterEmail.id", array("type"=>"hidden"));
		     ?>
                    	<ul class="form">
                            <li>
				<?php echo $this->Form->input("NewsletterEmail.subject",array("class"=>"validate[required,maxSize[100]] form_input","div"=>false,"label"=>"Subject :*",'id'=>"subject"));?>
                            </li>
			     <li>
				<?php echo $this->Form->input("NewsletterEmail.content",array('id'=>'EmailDescriptionEdit','class'=>'sample mceEditor','type'=>'textarea',"div"=>false,'label'=>"Description :"));?>
                            </li>
			    <li>
				<section class="select_new">
				
				    <?php $status = array("0"=>"InActive","1"=>"Active");
				    echo $this->Form->input("NewsletterEmail.status", array("type"=>"select", "empty"=>"Select Status", "options"=>$status,"class"=>"","div"=>false,"label"=>"Status :*","default"=>1));?>
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
<?php echo $this->Html->script("/js/admin/tiny_mce/tiny_mce");
echo $this->Html->script('/js/admin/CmsPages/admin_add');?>

