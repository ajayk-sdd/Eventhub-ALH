<section id="cont_wrapper">
    <!--Content Starts-->
    <section class="content">
        <!--Main Heading Starts-->
        <h1 class="main_heading"> <?php echo $this->Html->link('Lists', '/admin/MyLists/list'); ?></h1>
        <!--Main Heading Ends-->
        <!--Conetnt Info Starts Here-->
        <section class="content_info">
            <div style="width:80%;margin:auto">
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
                <dl><dt style="float:left;margin-right: 100px;height: 70px;">Select File From your Computer: </dt>
                    <dd>   <?php echo $this->Form->input("file", array('type' => 'file', "label" => false, "div" => false, "class" => "validate[required] form_input")); ?>
                    <br><br>
                        Please <a href="<?php echo CHILD_URL.'/app/webroot/files/EmailCsv/Sample_Contact.csv'?>" download='Sample_Contact.csv'>Click Here</a> to download CSV file format. 
                   <br><br> </dd>
                </dl>
                <div style="text-align:center;">
                    <?php echo $this->Form->input("Submit", array("type" => "submit", "label" => false, "div" => false, "class" => "blu_btn_rt")); ?>
                </div>
                <?php
                echo $this->Form->end();
                ?>
                <!--------------------------------------------------------------------------------------------------->
               
                <!------------------------------------------- Import Email using Cloudspong------------------------------>
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
                
                <?php echo $this->Form->input("MyList.contact_list", array('type' => 'textarea', "label" => false, "div" => false, "class" => "validate[required] form_input email-textarea", "id" => "contact_list", "style" => "width:100%","readonly" => "readonly")); ?>
                
                 <div style="text-align:center;"><br>
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
                        <div style="font-size:13px;color:#999;margin-top:5px">Write multiple email which should be seperated by comma.</div>
                    </dd>
                </dl>
                <div style="text-align:center;">
                <div class="sub-btn">
                    <input class="blu_btn_rt" type="submit" value="Submit">
                </div>
                </div>
                <div class="clear"></div>
                <?php
                echo $this->Form->end();
                ?>
            </div>
</section>
<?php
echo $this->Html->script("/js/admin/tiny_mce/tiny_mce");
echo $this->Html->script('/js/Front/MyList/add');
?>