<?php //pr($this->data);    ?>
<!--Content Wrapper Starts-->
<section id="cont_wrapper">
    <!--Content Starts-->
    <section class="content">
        <!--Main Heading Starts-->
        <h1 class="main_heading"> <?php echo $this->Html->link('Brands', '/admin/Brands/add'); ?></h1>
        <!--Main Heading Ends-->
        <!--Conetnt Info Starts Here-->
        <section class="content_info">
            <?php
            echo $this->Session->flash();
            echo $this->Form->create("Brand", array("action" => "add", "id" => "addBrandForm", 'enctype' => 'multipart/form-data'));
            echo $this->Form->input("Brand.id", array("type" => "hidden"));
            ?>
            <ul class="form">
                <li>
                    <?php echo $this->Form->input("Brand.user_id", array('type' => 'select', 'empty' => 'Select', 'options' => $user, 'div' => false, 'label' => "User", 'class' => 'validate[required] form_input')); ?>
                </li>
                <li>
                    <?php echo $this->Form->input("Brand.name", array("type" => "text", "label" => "Name :*", "div" => false, "class" => "validate[required,maxSize[100]] form_input", "id" => "name")); ?>
                </li>
                <li>
                    <?php echo $this->Form->input("Brand.sub_title", array("type" => "text", "label" => "Sub Title :*", "div" => false, "class" => "validate[required] form_input", "id" => "sub_title")); ?>
                </li>
                <?php  if (!empty($this->data["Brand"]["logo"])) { ?>
                    <li>
                        <label>Current Logo</label>
                        <?php
                        echo $this->html->image("brand/small/" . $this->data["Brand"]["logo"]);
                        ?>
                    </li>
                    <li>
                        <?php echo $this->Form->input("Brand.logo", array("type" => "file", "label" => "Upload Logo", "div" => false, "id" => "logo")); ?>
                    </li>
                <?php } else {?>
                <li>
                    <?php echo $this->Form->input("Brand.logo", array("type" => "file", "label" => "Upload Logo", "div" => false, "class" => "validate[required,checkFileType[jpg|jpeg|gif|JPG|png|PNG],checkFileSize[2000]] form_input", "id" => "logo")); ?>
                </li>
                <?php }?>
                <li>
                    <?php echo $this->Form->input("Brand.description", array('id' => 'description', 'type' => 'textarea', "class" => "validate[required,maxSize[250]] form_input", "div" => false, 'label' => "Description :")); ?>
                </li>
                
                <li><label for="categories">Categories :</label>
                   <label class="input_tag">
                    
                   <?php
		       $j = 0;
		      
                    if (!empty($this->data['BrandCategory'])) {
                        foreach ($this->data['BrandCategory'] as $ec) {
                            $brand_cate[] = $ec["category_id"];
                        }
                    } else {
                        $brand_cate = array();
                    }


		     foreach ($categories as $cat):
		     echo '<div style="float:left;margin-right:10px;margin-bottom:5px;clear:none;padding:0px">';
		     if (in_array($cat['Category']['id'], $brand_cate))
			 echo $this->Form->input("BrandCategory.category_id[]", array("checked" => true, "name" => "data[BrandCategory][category_id][]", "type" => "checkbox", "label" => $cat['Category']['name'], "div" => false, "class" => "validate[required] form_input", "value" => $cat['Category']['id']));
		      else
			 echo $this->Form->input("BrandCategory.category_id[]", array("name" => "data[BrandCategory][category_id][]", "type" => "checkbox", "label" => $cat['Category']['name'], "div" => false, "class" => "validate[required] form_input", "value" => $cat['Category']['id']));
		     $j++;
		     echo '</div>';
		     
		     endforeach;
		     ?>
                </label> 
                </li>

                 <li><label for="Vibes">Vibes :</label>
                   <label class="input_tag">
                    
                   <?php
		     
		     $i = 0;
                    if (!empty($this->data["BrandVibe"])) {
                        foreach ($this->data["BrandVibe"] as $ev) {
                            $user_vib[] = $ev["vibe_id"];
                        }
                    } else {
                        $user_vib = array();
                    }
                    
                    
		     foreach ($vibes as $vibe):
		     echo '<div style="float:left;margin-right:10px;margin-bottom:5px;clear:none;padding:0px">';
		      if (in_array($vibe['Vibe']['id'], $user_vib))
			 echo $this->Form->input("BrandVibe.vibe_id", array("checked" => true, "name" => "data[BrandVibe][vibe_id][]", "type" => "checkbox", "label" => $vibe['Vibe']['name'], "div" => false, "class" => "validate[required] form_input", "value" => $vibe['Vibe']['id']));
		     else
		     echo $this->Form->input("BrandVibe.vibe_id", array("name" => "data[BrandVibe][vibe_id][]", "type" => "checkbox", "label" => $vibe['Vibe']['name'], "div" => false, "class" => "validate[required] form_input", "value" => $vibe['Vibe']['id']));
		     $i++;
		     echo '</div>';
		     endforeach;
		     ?>
                </label> 
                </li>
                 
                <section class="login_btn">
                    <span class="blu_btn_lt">
                        <?php echo $this->Form->input("Reset", array("type" => "reset", "label" => false, "div" => false, "class" => "blu_btn_rt")); ?>
                    </span>
                    <span class="blu_btn_lt">
                        <?php echo $this->Form->input("Submit", array("type" => "submit", "label" => false, "div" => false, "class" => "blu_btn_rt")); ?>
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
<?php
//echo $this->Html->script("/js/admin/tiny_mce/tiny_mce");
echo $this->Html->script('/js/admin/Brand/admin_add');
?>