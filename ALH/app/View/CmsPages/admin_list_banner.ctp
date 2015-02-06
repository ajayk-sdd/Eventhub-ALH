<section id="cont_wrapper">
    <section class="content">
        <h1 class="main_heading"><?php echo $this->Html->link('Add Banner', '/admin/CmsPages/addBanner'); ?></h1>
        <section class="content_info">
            <?php echo $this->Session->flash(); ?>
            
            <div class="notification">- Simply drag and drop the banner to set the order.</div>
            <section class="tbldata">
                <?php echo $this->Form->create('Common', array('Banner', 'action' => 'selectMultiple?model=Banner')); ?>

                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabulardata">
               
                    <tr>
                        <th style="width:4%"><?php echo $this->Form->checkbox('selectAll', array('type' => 'checkbox', 'class' => 'textbox', 'label' => '', 'id' => 'selectall', 'name' => 'selectAll')); ?></th>


                        <th style="width:20%"><?php echo $this->Paginator->sort('Banner.banner', 'Banner Image'); ?>
                            
                            <th style="width:20%"><?php echo $this->Paginator->sort('Banner.background_image', 'Background Image'); ?>


                        <th style="width:18%"><?php echo $this->Paginator->sort('Banner.location', 'Location'); ?>
                            <span class="<?php echo ('Banner.url' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "url ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>



                        <th style="width:6%"><?php echo $this->Paginator->sort('Banner.status', 'Status'); ?>
                            <span class="<?php echo ('Banner.status' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "status ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:6%;">Action</th>
                    </tr>
                    <tbody id="make-sortable">
                    <?php foreach ($banners as $data): //pr($data);?>
                        <tr id="li_<?php echo $data['Banner']['id']; ?>">
                            <td><?php echo $this->Form->checkbox('checkbox', array('type' => 'checkbox', 'class' => 'textbox case', 'label' => '', 'value' => $data['Banner']['id'], 'name' => 'IDs[]')); ?></td>


                            <td>
                                <?php $size = getimagesize($data["Banner"]["image_name"]); ?>
                                <img class="" alt="" src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/users/thumbnail?file=<?php echo $data["Banner"]["image_name"]; ?>&amp;width=300&amp;height=50&amp;maxw='<?php echo $size[0]; ?>'&amp;maxh='<?php echo $size[1]; ?>'"></td>
                            
                                 <td>
                                <?php $size = getimagesize($data["Banner"]["background_image"]); ?>
                                <img class="" alt="" src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/users/thumbnail?file=<?php echo $data["Banner"]["background_image"]; ?>&amp;width=300&amp;height=50&amp;maxw='<?php echo $size[0]; ?>'&amp;maxh='<?php echo $size[1]; ?>'"></td>
                            <td>
                                <?php
                                $str = "";
                                foreach ($data['BannerImage'] as $banner):
                                    $str .= $banner['location'] . ',';
                                endforeach;
                                $str = rtrim($str, ",");
                                echo $str;
                                ?>
                            </td>

                            <td>
                                <?php
                                $model = "Banner";
                                if ($data['Banner']['status'] == 1) {

                                    echo "<img src='/img/admin/active.png' title='Change Status' id='" . $data['Banner']['id'] . "' value='" . $data['Banner']['status'] . "' dir='" . $model . "' class='statusImg' rel='" . base64_encode($data['Banner']['id']) . "'/>";
                                    ?>
                                    <span class="loader" id="load_<?php echo $data['Region']['id']; ?>"><?php echo $this->html->image('/img/admin/loader.gif'); ?></span>
                                    <?php
                                } else {
                                    echo "<img src='/img/admin/inactive.png' title='Change Status' id='" . $data['Banner']['id'] . "' value='" . $data['Banner']['status'] . "' dir='" . $model . "' class='statusImg' rel='" . base64_encode($data['Banner']['id']) . "'/>";
                                    ?>
                                    <span class="loader" id="load_<?php echo $data['Region']['id']; ?>"><?php echo $this->html->image('/img/admin/loader.gif'); ?></span>
                                <?php }
                                ?>
                            </td>

                            <td>
                                <?php
                                echo $this->Html->link($this->html->image('/img/admin/view.png'), array("controller" => "CmsPages", "action" => "viewBanner", base64_encode($data['Banner']['id'])), array('escape' => false, 'title' => 'View', 'class' => "edidlt"));
                                echo $this->Html->link($this->html->image('/img/admin/edit.png'), array("controller" => "CmsPages", "action" => "editBanner", base64_encode($data['Banner']['id'])), array('escape' => false, 'title' => 'Edit', 'class' => "edidlt"));
                                echo $this->Html->link($this->html->image('/img/admin/delete.png'), array("controller" => "Commons", "action" => "Delete", base64_encode($data['Banner']['id']), 'Banner'), array('escape' => false, 'title' => 'Delete', 'class' => "edidlt", 'onclick' => "return confirm('Are you sure you want to delete this banner ?');"));
                                ?>
                            </td>    
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table><br/>
                <?php echo $this->Form->submit("Deactivate", array('class' => "blu_btn mar_rt", 'div' => false, 'id' => 'loginButton', 'title' => 'Deactivate', 'name' => 'deactivate', 'onclick' => "return atleastOneChecked('Deactivate selected Region ?');")); ?>
                <?php echo $this->Form->submit("Activate", array('class' => "blu_btn mar_rt", 'div' => false, 'id' => 'loginButton', 'title' => 'Activate', 'name' => 'activate', 'onclick' => "return atleastOneChecked('Activate selected Region ?');")); ?>
                <?php echo $this->Form->submit("Delete", array('class' => "blu_btn mar_rt", 'div' => false, 'id' => 'loginButton', 'title' => 'Activate', 'name' => 'delete', 'onclick' => "return atleastOneChecked('Deleted selected Region ?');")); ?>
                <?php echo $this->Form->end(); ?>
            </section>

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
            <section class="clr_bth"></section>
        </section>
    </section>
</section>
<script type="text/javascript">
$(document).ready(function(){
    jQuery("#make-sortable").sortable({ opacity: 0.6, cursor: 'move', update: function() {
        var order = jQuery(this).sortable("serialize");
            jQuery.post("/CmsPages/setOrder",order, function(theResponse){
                if(theResponse=="true")
                {
                  return false;
                }
            });
        }
      }); 
});
</script>

