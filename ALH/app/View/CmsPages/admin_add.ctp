<!--Content Wrapper Starts-->
            <section id="cont_wrapper">
            	<!--Content Starts-->
                <section class="content">
                	<!--Main Heading Starts-->
                    <h1 class="main_heading"> <?php echo $this->Html->link('Cms Page','/admin/CmsPages/list');?></h1>
                    <!--Main Heading Ends-->
                    <!--Conetnt Info Starts Here-->
                    <section class="content_info">
		    <?php echo $this->Session->flash();
			echo $this->Form->create("CmsPage", array("action"=>"add","id"=>"addCmsForm"));
			 echo $this->Form->input("CmsPage.id", array("type"=>"hidden"));
		     ?>
                    	<ul class="form">
                            <li>
				<?php echo $this->Form->input("title",array("class"=>"validate[required,maxSize[40]] form_input","div"=>false,"label"=>"Title :*",'id'=>"cmsTitle"));?>
                            </li>
			     <li>
				<?php echo $this->Form->input("slug",array("class"=>"validate[required,maxSize[40]] form_input","div"=>false,"label"=>"Slug :*",'id'=>"cmsSlug"));?>
                            </li>
			     <li>
				<section class="select_new">
				
				    <?php
                                    //$cmsData[0] = "Select Parent";
                                    //ksort($cmsData);
                                    //pr($cmsData);die;
				    echo $this->Form->input("parent", array("type"=>"select","empty"=>"Select Parent", "options"=>$cmsData,"class"=>"","div"=>false,"label"=>"Parent :"));?>
				</section>
                            </li>
			    
			    <li>
				<?php echo $this->Form->input("description",array('id'=>'EmailDescriptionEdit','class'=>'sample mceEditor','type'=>'textarea',"div"=>false,'label'=>"Description :"));?>
                            </li>
			    <li>
				<section class="select_new">
				
				    <?php $status = array("0"=>"InActive","1"=>"Active");
				    echo $this->Form->input("status", array("type"=>"select", "empty"=>"Select Status", "options"=>$status,"class"=>"","div"=>false,"label"=>"Status :*","default"=>1));?>
				</section>
                            </li>
			     <li>
				<?php echo $this->Form->input("meta_title",array('id'=>'MetaTitleEdit','class'=>'sample','type'=>'textarea',"div"=>false,'label'=>"Meta Title :","rows"=>"2","cols"=>"97"));?>
                            </li>
			      <li>
				<?php echo $this->Form->input("meta_description",array('id'=>'MetaDescriptionEdit','class'=>'sample','type'=>'textarea',"div"=>false,'label'=>"Meta Description :","rows"=>"5","cols"=>"97"));?>
                            </li>
			       <li>
				<?php echo $this->Form->input("meta_keyword",array('id'=>'MetaKeywordEdit','class'=>'sample','type'=>'textarea',"div"=>false,'label'=>"Meta Keyword :","rows"=>"5","cols"=>"97"));?>
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

