<section id="cont_wrapper">
    <section class="content">
        <h1 class="main_heading"><?php echo "Email List"; ?></h1>
<section class="content_info">
            <?php echo $this->Session->flash(); ?>
            <section class="tbldata">

 <ul class="form" style="width:100%">
                <li>
                    <label>List Name :</label><span><?php echo $listdetail['MyList']['list_name']; ?></span>
                </li>
                <li>
                    <label>List Owner :</label><span><?php echo $this->html->link($listdetail['User']['username'], array("controller" => "users", "action" => "view", base64_encode($listdetail['User']["id"]))); ?></span>
                </li>
                <!--li>
                    <label>Dedicated Send Points:</label><span> <?php //echo $listdetail['MyList']['dedicated_send_points']; ?></span>
                </li>
                <li>
                    <label>Multi Event Points :</label><span><?php //echo $listdetail['MyList']['multi_event_points']; ?></span>
                </li>
                <li>
                    <label>Max Email Per Week :</label><span><?php //echo $listdetail['MyList']['max_email_per_week']; ?></span>
                </li-->
                <li>
                    <label>Status :</label><span><?php
                        if ($listdetail['MyList']['status'] == 1)
                            echo 'Active';
                        else
                            echo 'Inactive';
                        ?></span>
                </li>
               
                <li><label>Category :</label><span>
                <?php foreach ($listdetail["ListCategory"] as $listcategory) { ?>
                   <?php
                            echo $listcategory["Category"]["name"]." , ";
                            ?>
                <?php } ?></span>
		</li>
                <li><label>Vibe :</label><span>
                <?php foreach ($listdetail["ListVibe"] as $listvibe) { ?>
                    <?php
                            echo $listvibe["Vibe"]["name"]." , ";
                            ?>
                <?php } ?></span>
		</li>
                <li><label>Region :</label><span>
                <?php foreach ($listdetail["ListRegion"] as $listregion) { ?>
                  
                       <?php
                            echo $listregion["Region"]["name"]." , ";
                            ?>
                    
                <?php } ?></span>
</li>

                <li>
                    <span class="blu_btn_lt">
                        <input type="reset" id="MyListReset" class="blu_btn_rt" onclick="javascript:history.back();" value="Go Back"></span>
                </li>
            </ul>

 <h1 class="main_heading" style="width:100%;float:left;margin-bottom:10px"><?php echo "List Detail"; ?></h1>

		<!------------------------------------ search starts --------------------->
		 <div class="srch_box">
                
                <?php echo $this->Form->create("MyList", array("action" => "listView/".$list_id, 'enctype' => 'multipart/form-data', "novalidate" => "novalidate")); 
                            echo $this->Form->input('ListEmail.email', array('label' => "List Email:", 'div' => false, 'maxlength' => '150', 'autocomplete' => 'off', 'class' => 'form_input', 'placeholder' => 'Enter Email'));
                            
                            echo $this->Form->input("ListEmail.limit", array('type' => 'select', 'options' => array("10" => "10", "20" => "20", "50" => "50", "100" => "100"), 'div' => false, 'label' => "Show Per Page:"));
			    echo $this->Form->input("ListEmail.order", array('type' => 'select', 'options' => array("ListEmail.email" => "List Email", "MyList.status" => "Status"), 'div' => false, 'label' => "Order by:"));
			    echo $this->Form->input("ListEmail.direction", array('type' => 'select', 'options' => array("ASC" => "Ascending", "DESC" => "Descending"), 'div' => false, 'label' => "Direction:")); 
                            echo $this->Form->submit('Search', array('label' => false, 'div' => false, "class" => "blu_btn mar_rt"));
                           
                            echo $this->html->link('Clear Search', array('controller' => 'MyLists', 'action' => 'listView/'.$list_id), array("class" => "blu_btn mar_rt"));
                            
                            echo $this->html->link('Download CSV', array('controller' => 'MyLists', 'action' => 'exportCsv/'.$list_id), array("class" => "blu_btn mar_rt"));
                            echo $this->Form->end();
		?>
	     </div>
                <!--------------------------------- search ends ------------------------------->


 <?php echo $this->Form->create('Common', array('users', 'action' => 'selectMultiple?model=ListEmail'));?>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabulardata">
                    <tr>
                        <th style="width:2%"><?php echo $this->Form->checkbox('selectAll', array('type' => 'checkbox', 'class' => 'textbox', 'label' => '', 'id' => 'selectall', 'name' => 'selectAll')); ?></th>
                        <th style="width:14%;"><?php echo $this->Paginator->sort('ListEmail.email', 'Email'); ?>
                            <span class="<?php echo ('ListEmail.email' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "email ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:12%;">Status
                           </th>
                       
                    </tr>
		
<?php 
 foreach ($list_emails as $listemail) { ?>
                     <tr>
                            <td><?php echo $this->Form->checkbox('checkbox', array('type' => 'checkbox', 'class' => 'textbox case', 'label' => '', 'value' => $listemail['ListEmail']['id'], 'name' => 'IDs[]')); ?></td>
			    <td><?php echo $listemail["ListEmail"]["email"];?></td>
			    <td><?php $model = "ListEmail";
                                if($listemail["ListEmail"]['status'] == 1) {
                       
                                   echo "<img src='/img/admin/active.png' title='Change Status' id='".$listemail["ListEmail"]['id']."' value='".$listemail["ListEmail"]['status']."' dir='".$model."' class='statusImg' rel='".base64_encode($listemail["ListEmail"]['id'])."'/>";?>
				   <span class="loader" id="load_<?php echo $listemail["ListEmail"]['id'];?>"><?php echo $this->html->image('/img/admin/loader.gif');?></span>
                                <?php }else {
                                   echo "<img src='/img/admin/inactive.png' title='Change Status' id='".$listemail["ListEmail"]['id']."' value='".$listemail["ListEmail"]['status']."' dir='".$model."' class='statusImg' rel='".base64_encode($listemail["ListEmail"]['id'])."'/>";?>
				   <span class="loader" id="load_<?php echo $listemail["ListEmail"]['id'];?>"><?php echo $this->html->image('/img/admin/loader.gif');?></span>
			<?php }
                               ?></td>
			     
		     </tr>	
                <?php } ?>
		</table>
<br/>
                <?php echo $this->Form->submit("Deactivate", array('class' => "blu_btn mar_rt", 'div' => false, 'id' => 'loginButton', 'title' => 'Deactivate', 'name' => 'deactivate', 'onclick' => "return atleastOneChecked('Deactivate selected users?');")); ?>
                <?php echo $this->Form->submit("Activate", array('class' => "blu_btn mar_rt", 'div' => false, 'id' => 'loginButton', 'title' => 'Activate', 'name' => 'activate', 'onclick' => "return atleastOneChecked('Activate selected users?');")); ?>
                <?php echo $this->Form->submit("Delete", array('class' => "blu_btn mar_rt", 'div' => false, 'id' => 'loginButton', 'title' => 'Activate', 'name' => 'delete', 'onclick' => "return atleastOneChecked('Deleted selected users?');")); ?>
                <?php echo $this->Form->end(); ?>
<div class="pagination_new">
                <?php
                echo $this->Paginator->first('<< First', null, null, array('class' => 'disabled'));
                echo $this->Paginator->prev('<< Previous', null, null, array('class' => 'disabled'));
                echo $this->Paginator->numbers(array('separator' => ''));
                echo $this->Paginator->next('Next >>', null, null, array('class' => 'disabled'));
                echo $this->Paginator->Last('Last >>', null, null, array('class' => 'disabled'));
                //echo $this->Paginator->counter(array('format' => 'Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%'));
                ?>
            </div>
</section>
</section>
