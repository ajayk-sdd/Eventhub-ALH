<section class="inner-content">
    <div class="center-block">
        <div class="repo-list-wrap width100">
            <h1>Add Contacts To List</h1>
            <div style="clear:both;"></div>
            <div class="repo-list reli-II">
                <?php
                echo $this->Session->flash();
                //echo $mylist_id;
                ?>
                <!------------------------------------ import using csv files --------------------------------------->
                <h2>Import Emails using CSV file</h2></center><br>
                <?php
                echo $this->Form->create("MyList", array("action" => "importCsv", "id" => "addCsvForm", 'enctype' => 'multipart/form-data'));
                echo $this->Form->input("MyList.id", array("type" => "hidden", "value" => $mylist_id));
                ?>
                <dl><dt>Select File From your Computer: </dt>
                    <dd>   <?php echo $this->Form->input("file", array('type' => 'file', "label" => false, "div" => false, "class" => "validate[required] form_input")); ?>
                    </dd>
                </dl>
                <div style="text-align:center;">
                    <?php echo $this->Form->input("Submit", array("type" => "submit", "label" => false, "div" => false, "class" => "blu_btn_rt")); ?>
                </div>
                <?php
                echo $this->Form->end();
                ?>
                <!--------------------------------------------------------------------------------------------------->
                <!------------------------------------------- import using open inviter------------------------------>
                <br><hr><br><center>
                    <h2>Import Emails using third party (Gmail, Yahoo etc)</h2></center><br>
                
                <!-- Include the script anywhere on your page -->
                <script>
                    // set any options here, for this example, we'll simply populate the contacts in the textarea above
                    window.csPageOptions = {
                      textarea_id:"contact_list"
                    };
                    // Asynchronously include the widget library.
                    (function(u){
                      var d=document,s='script',a=d.createElement(s),m=d.getElementsByTagName(s)[0];
                      a.async=1;a.src=u;m.parentNode.insertBefore(a,m);
                    })('//api.cloudsponge.com/widget/4d63f2bd9a40094c7f2895c2d70a0d9eee26ffcc.js');
                </script>
               
                <?php
                
                     echo $this->Form->create("MyList", array("action" => "import", "id" => "addEmailForm", 'enctype' => 'multipart/form-data'));
                     echo $this->Form->input("MyList.id", array("type" => "hidden", "value" => $mylist_id));
                   
                ?>
                
                <a class="cs_import btn-all">Click Here to fetch User Email-Id</a>
                
                <?php echo $this->Form->input("MyList.contact_list_email", array('type' => 'textarea', "label" => false, "div" => false, "class" => "validate[required] form_input email-textarea", "id" => "contact_list", "style" => "width:100%","readonly" => "readonly")); ?>
                                 
                <script type="text/javascript" src="//api.cloudsponge.com/address_books.js"></script>
                
                 <div style="text-align:center;">
                    <?php echo $this->Form->input("Submit", array("type" => "submit", "label" => false, "div" => false, "class" => "blu_btn_rt")); ?>
                </div>   
                <?php
                echo $this->Form->end();
                ?>
                
                <!--------------------------------------------------------------------------------------------------->
                <!---------------------------------- from copy paste contact ---------------------------------------->
                <br><hr><br>
                <center><h2>Import Emails by copy and paste</h2></center>
                <?php
                echo $this->Form->create("MyList", array("action" => "importcopy", "id" => "addEmailFormCopy", 'enctype' => 'multipart/form-data'));
                echo $this->Form->input("MyList.id", array("type" => "hidden","value"=>$mylist_id));
                ?>
                <dl>
                    <dt>Email List: </dt>
                    <dd><?php echo $this->Form->input("copy_email_list", array('type' => 'textarea', "label" => false, "div" => false, "class" => "validate[required] form_input email-textarea", "style" => "width:100%")); ?>
                        <div style="font-size:13px;color:#999;margin-top:-15px">Write multiple email which should be seperated by comma.</div>
                    </dd>
                </dl>
                <div class="sub-btn">
                    <input class="blu_btn_rt" type="submit" value="Submit">
                </div>
                <div class="clear"></div>
                <?php
                echo $this->Form->end();
                ?>
                <!--------------------------------------------------------------------------------------------------->
            </div>

        </div>
    </div>
</section>
<?php
echo $this->Html->script("/js/admin/tiny_mce/tiny_mce");
echo $this->Html->script('/js/Front/MyList/add');
?>