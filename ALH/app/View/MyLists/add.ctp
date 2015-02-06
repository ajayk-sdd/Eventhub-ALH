<section class="inner-content">
    <div class="center-block">
        <div class="repo-list-wrap width100">
            <h1>Add List</h1>
            <div style="clear:both;"></div>
            <div class="repo-list reli-II" style="float: left;">
                <?php
                echo $this->Session->flash();
                echo $this->Form->create("MyList", array("action" => "add", "id" => "addListForm", 'enctype' => 'multipart/form-data'));
                echo $this->Form->input("MyList.id", array("type" => "hidden"));
                echo $this->Form->input("MyList.user_id", array('type' => 'hidden', 'value' => AuthComponent::user("id")));
                if(!empty($this->data))
                {
                    $btnText = "Update";
                   
                }else
                {
                    $btnText = "Add";
                }
                
                 if(!empty($this->data['MyList']['contact_information']))
                   {
                   
                   $addr = $this->data['MyList']['contact_information'];
                   }
                   else
                   {
                 
                    $addr = $userAddress;
                   }
                ?>
                      <dl>

                    <dt>List Name: </dt>
                    <dd><?php echo $this->Form->input("MyList.list_name", array("type" => "text", "label" => false, "div" => false, "class" => "validate[required] form_input")); ?></dd>
                    
                    <dt>Default From Email: </dt>
                    <dd><?php echo $this->Form->input("MyList.from_email", array("type" => "text", "label" => false, "div" => false, "class" => "validate[required] form_input")); ?></dd>
                    
                    <dt>Default From Name: </dt>
                    <dd><?php echo $this->Form->input("MyList.from_name", array("type" => "text", "label" => false, "div" => false, "class" => "validate[required] form_input")); ?></dd>
                    
                    <dt>Remind people how they got on your list: </dt>
                    <dd><?php echo $this->Form->input("MyList.reuse", array("type" => "select","empty"=>"Reuse a reminder from other list", "label" => false, "div" => false, "class" => "form_input","options"=>$reminds,"onchange"=>"javascript:$('#MyListRemindMe').val(this.value);")); ?></dd>
                    
                    <dt>Remind Me About List: </dt>
                    <dd><?php echo $this->Form->input("MyList.remind_me", array("type" => "textarea","rows"=>6,"cols"=>104, "label" => false, "div" => false,'placeholder'=>'Write a short reminder about how the recipient joined your list.', "class" => "validate[required] form_input")); ?></dd>
                    
                    <dt>Contact Information: </dt>
                    <dd>
                       
                        <div style="border:1px solid #e1e1e1;padding:10px;" class="add-box"><?php echo $addr; ?><div style="float:right"><a href="javascript:void(0);" class="btn-all" style="float:none" id="showSlidebtn">Edit</a></div></div>
                   
                   <div id="addSlider"> <?php
                  
                   echo $this->Form->input("MyList.contact_information", array("type" => "textarea","rows"=>6,"cols"=>104, "label" => false, "div" => false, "class" => "validate[required] form_input","value"=>"$addr")); ?></div></dd>
                    
                    <dt>&nbsp;</dt>
                    <dd><?php echo "<div style='float:left'>".$this->Form->input($btnText, array("type" => "submit", "label" => false, "div" => false, "class" => ".anc_link"))."</div>"; echo '<input type="button" onclick="javascript:history.back();" value="Go Back">'; ?></dd>

                </dl>
                <?php echo $this->Form->end();?>
            </div>

        </div>
    </div>
</section>

<style>
    <?php if(empty($userAddress) && empty($this->data['MyList']['contact_information']))
    {
        echo '.add-box
    {
        display: none;
    }';
    }
    else
    {
        echo '#addSlider
    {
        display: none;
    }';
    }
    ?>
</style>
<script type="text/javascript">
    $(document).ready(function(){
        
       $("#addListForm").validationEngine();
       
       $("#showSlidebtn").click(function(){
  $("#addSlider").slideToggle();
});
    });
</script>