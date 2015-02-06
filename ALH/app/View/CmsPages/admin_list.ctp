<section id="cont_wrapper">
    <section class="content">
        <h1 class="main_heading"><?php echo $this->Html->link('Add Cms Page', '/admin/CmsPages/add'); ?></h1>
        <section class="content_info">
            <?php echo $this->Session->flash(); ?>
            <section class="tbldata">
                <?php echo $this->Form->create('User', array('users', 'action' => 'select_multiple')); ?>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabulardata">
                    <tr>
                        <th style="width:10%">S.No</th>
                        <th style="width:18%;"><?php echo "Title"; ?></th>
                        <th style="width:18%;"><?php echo "Slug"; ?></th>
                        <th style="width:18%;"><?php echo "Date Created"; ?></th>
                        <th style="width:18%;"><?php echo "Status"; ?></th>
                        <th style="width:18%;">Action</th>
                    </tr>
                    <?php $Sno = 1;
                        foreach ($Cmsdata as $user): ?>
                        <tr>
                            <td><?php echo $Sno;?></td>
                            <td><?php echo $user['CmsPage']['title']; ?></td>
                            <td><?php echo $user['CmsPage']['slug']; ?></td>
                            <td><?php echo $user['CmsPage']['created']; ?></td>
                            
                            <td> <?php $model = "CmsPage";
                                if($user['CmsPage']['status'] == 1) {
                       
                                   echo "<img src='/img/admin/active.png' title='Change Status' id='".$user['CmsPage']['id']."' value='".$user['CmsPage']['status']."' dir='".$model."' class='statusImg' rel='".base64_encode($user['CmsPage']['id'])."'/>";?>
                                <span class="loader" id="load_<?php echo $user['CmsPage']['id'];?>"><?php echo $this->html->image('/img/admin/loader.gif');?></span>
                            <?php  }else {
                                   echo "<img src='/img/admin/inactive.png' title='Change Status' id='".$user['CmsPage']['id']."' value='".$user['CmsPage']['status']."' dir='".$model."' class='statusImg' rel='".base64_encode($user['CmsPage']['id'])."'/>";?>
                                   <span class="loader" id="load_<?php echo $user['CmsPage']['id'];?>"><?php echo $this->html->image('/img/admin/loader.gif');?></span>
                               <?php }
                               ?>
                            </td>
                            <td>
                                <?php echo $this->Html->link($this->html->image('/img/admin/edit1.png'), array("controller" => "CmsPages", "action" => "add", base64_encode($user['CmsPage']['id'])), array('escape' => false, 'title' => 'Edit', 'class' => "edidlt")); ?>
                           <?php echo $this->Html->link($this->html->image('/img/admin/delete.png'), array("controller" => "Commons", "action" => "Delete", base64_encode($user['CmsPage']['id']),'Cms_page'), array('escape' => false, 'title' => 'Delete', 'class' => "edidlt", 'onclick' => "return confirm('Are you sure you want to delete this ?');")); ?>
                           
                            </td>    
                        </tr>
                    <?php $Sno++;
                    endforeach; ?>  
                </table>
                <?php echo $this->Form->end(); ?>
            </section>
            <section class="clr_bth"></section>
        </section>
    </section>
</section>
<?php echo $this->Html->script('/js/admin/CmsPages/admin_list');?>
