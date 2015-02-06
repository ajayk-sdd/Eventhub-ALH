
<section id="cont_wrapper">
    <!--Content Starts-->
    <section class="content">
        <!--Main Heading Starts-->
        <h1 class="main_heading"> <?php echo $this->Html->link('Packages', '/admin/Packages/list'); ?></h1>
        <!--Main Heading Ends-->
        <!--Conetnt Info Starts Here-->
        <section class="content_info">
            <?php
            echo $this->Session->flash();
            echo $this->Form->create("Package", array("action"=>"add","id"=>"addPackage"));
            echo $this->Form->input("Package.id", array("type"=>"hidden"));
            ?>
            <ul class="form">
              
                <li>
                    <?php echo $this->Form->input("name", array("type" => "text", "label" => "Name:*", "div" => false, "class" => "validate[required,maxSize[100]] form_input", "id" => "name")); ?>
                </li>
                <li>
                    <?php echo $this->Form->input("slug", array("type" => "text", "label" => "Slug:*", "div" => false, "class" => "validate[required,maxSize[100]] form_input","id"=>"slug")); ?>
                </li>
                <li>
                    <?php echo $this->Form->input("description", array("type" => "textarea", "label" => "Enter  Description:*", "div" => false, "class" => "validate[required,maxSize[250]] form_input", "id" => "description"));?>
                </li>
                <li>
                    <?php echo $this->Form->input("sku", array("type" => "text", "label" => "SKU:*", "div" => false, "class" => "validate[required] form_input")); ?>
                </li>
                <li>
                    <?php echo $this->Form->input("upc", array("type" => "text", "label" => "UPC:*", "div" => false, "class" => "validate[required] form_input")); ?>
                </li>
                <li>
                    <?php echo $this->Form->input("price", array("type" => "text", "label" => "Price:*", "div" => false, "class" => "validate[required,custom[price]] form_input")); ?>
                </li>
                <li>
                    <?php echo $this->Form->input("point", array("type" => "text", "label" => "Point:*", "div" => false, "class" => "validate[required,custom[integer]] form_input")); ?>
                </li>
                <li>
                   <?php echo $this->Form->input("meta_description", array("type" => "textarea", "label" => "Meta Description:*", "div" => false, "class" => "validate[required,maxSize[250]] form_input", "id" => "metadescription"));?>
                </li>
                 <li>
                    <?php echo $this->Form->input("meta_keyword", array("type" => "text", "label" => "Meta Keyword:*", "div" => false, "class" => "validate[required] form_input")); ?>
                </li>
                <li>
                 <section class="select_new">
                    
                        <?php $status = array("0"=>"InActive","1"=>"Active");
                        echo $this->Form->input("status", array("type"=>"select", "empty"=>"Select Status", "options"=>$status,"class"=>"validate[required]","div"=>false,"label"=>"Status :*","default"=>1));?>
                  </section>
                </li>
		<li>
		 <section class="select_new">
		<?php 
		    echo $this->Form->input("Service", array("class"=>"","div"=>false,"label"=>"Services :*"));
		   /* echo $this->Form->input('Service',array(
'label' => __('Service',true),
'type' => 'select',
'multiple' => 'checkbox',
'options' => $services,
'selected' => $this->Html->value('Service.Service'),
));*/


		?>
		 </section>
		</li>
             <section class="login_btn">
			   
                <span class="blu_btn_lt">
                    <?php echo $this->Form->input("Submit", array("type"=>"submit","label"=>false,"div"=>false,"class"=>"blu_btn_rt"));?>
                </span>
	     </section>
              
            </ul>

<?php echo $this->Form->end(); ?>
            <section class="clr_bth"></section>
        </section>
        <!--Conetnt Info Ends Here-->
    </section>
    <!--Content Ends-->
</section>
<!--Content Wrapper Ends-->
<script type="text/javascript">
$(document).ready(function(){
       $("#addPackage").validationEngine();
});
</script>
