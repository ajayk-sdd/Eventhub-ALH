<script>
    function go_to_csv() {
        window.location.assign("/admin/brands/list_csv");
    }
</script>
<section id="cont_wrapper">
    <section class="content">
        <h1 class="main_heading"><?php echo $this->Html->link('Add Brands', '/admin/Brands/add'); ?></h1>
        <section class="content_info">
            <?php echo $this->Session->flash(); ?>
            <section class="tbldata">
                <!------------------------------------ search starts --------------------->
                <?php echo $this->Form->create("Brand", array("action" => "list", 'enctype' => 'multipart/form-data', "novalidate" => "novalidate")); ?>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="search_table">
                    <tr>
                        <td>
                            <?php
                            echo $this->Form->input('Brand.name', array('label' => "Name", 'div' => false, 'maxlength' => '50', 'autocomplete' => 'off', 'class' => 'form_input', 'placeholder' => 'Enter Name'));
                            ?>
                        </td>
                        <td>
                            <?php
                            echo $this->Form->input('User.username', array('label' => "Username", 'div' => false, 'maxlength' => '50', 'autocomplete' => 'off', 'class' => 'form_input', 'placeholder' => 'Enter username'));
                            ?>
                        </td>
                        <td>
                            <?php
                            echo $this->Form->submit('Search', array('label' => false, 'div' => false, "class" => "blu_btn mar_rt"));
                            ?>
                        </td>
                        <td>
                            <?php
                            echo $this->html->link('Clear Search', array('controller' => 'Brands', 'action' => 'list'), array("class" => "blu_btn mar_rt"));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $this->Form->input("limit", array('type' => 'select', 'options' => array("10" => "10", "20" => "20", "50" => "50", "100" => "100"), 'div' => false, 'label' => "Show Per Page:")); ?>
                        </td>
                        <td>
                            <?php echo $this->Form->input("order", array('type' => 'select', 'options' => array("Brand.name" => "Name", "User.username" => "Username", "Brand.created" => "Created"), 'div' => false, 'label' => "Order by:")); ?>
                        </td>
                        <td>
                            <?php echo $this->Form->input("direction", array('type' => 'select', 'options' => array("ASC" => "Ascending", "DESC" => "Descending"), 'div' => false, 'label' => "Direction:")); ?>
                        </td>
                        <td>
                            <?php
                            //echo $this->Form->input("Download CSV", array('label' => false, 'class' => "blu_btn mar_rt", 'div' => false, 'id' => '', 'title' => 'Download CSV', 'type' => 'button', 'onclick' => 'go_to_csv()'));
                            echo $this->html->link('Download CSV', array('controller' => 'Brands', 'action' => 'list_csv'), array("class" => "blu_btn mar_rt"));
                            ?>
                        </td>
                    </tr>


                </table>
                <?php echo $this->Form->end(); ?>
                <!--------------------------------- search ends ------------------------------->
                <?php echo $this->Form->create('Common', array('Brands', 'action' => 'selectMultiple?model=Brand')); ?>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabulardata">
                    <tr>
                        <th style="width:10%"><?php echo $this->Form->checkbox('selectAll', array('type' => 'checkbox', 'class' => 'textbox', 'label' => '', 'id' => 'selectall', 'name' => 'selectAll')); ?></th>
                        <th style="width:18%;"><?php echo $this->Paginator->sort('Brand.name', 'Name'); ?>
                            <span class="<?php echo ('Brand.name' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "email ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:18%;"><?php echo $this->Paginator->sort('User.username', 'Username'); ?>
                            <span class="<?php echo ('User.username' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "username ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:18%;"><?php echo $this->Paginator->sort('Brand.logo', 'Logo'); ?>
                            <span class="<?php echo ('Role.logo' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "name ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                        <th style="width:18%;"><?php echo $this->Paginator->sort('Brand.status', 'Status'); ?>
                            <span class="<?php echo ('Brand.status' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "status ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>

                        <th style="width:18%;">Action</th>
                    </tr>
                    <?php
                    if(!empty($brands))
                    {
                    foreach ($brands as $brand): ?>
                        <tr>
                            <td><?php echo $this->Form->checkbox('checkbox', array('type' => 'checkbox', 'class' => 'textbox case', 'label' => '', 'value' => $brand['Brand']['id'], 'name' => 'IDs[]')); ?></td>
                            <td><?php echo $brand['Brand']['name']; ?></td>
                            <td><?php echo $brand['User']['username']; ?></td>
                            <td><?php echo $this->html->image("brand/small/" . $brand['Brand']['logo']); ?></td>
                            <td>
                                <?php
                                $model = "Brand";
                                if ($brand['Brand']['status'] == 1) {

                                    echo "<img src='/img/admin/active.png' title='Change Status' id='" . $brand['Brand']['id'] . "' value='" . $brand['Brand']['status'] . "' dir='" . $model . "' class='statusImg' rel='" . base64_encode($brand['Brand']['id']) . "'/>";
                                    ?>
                                    <span class="loader" id="load_<?php echo $brand['Brand']['id']; ?>"><?php echo $this->html->image('/img/admin/loader.gif'); ?></span>
                                    <?php
                                } else {
                                    echo "<img src='/img/admin/inactive.png' title='Change Status' id='" . $brand['Brand']['id'] . "' value='" . $brand['Brand']['status'] . "' dir='" . $model . "' class='statusImg' rel='" . base64_encode($brand['Brand']['id']) . "'/>";
                                    ?>
                                    <span class="loader" id="load_<?php echo $brand['Brand']['id']; ?>"><?php echo $this->html->image('/img/admin/loader.gif'); ?></span>
                                <?php }
                                ?>
                           </td>
                            <td>
                                <?php echo $this->Html->link($this->html->image('/img/admin/edit1.png'), array("controller" => "Brands", "action" => "add", base64_encode($brand['Brand']['id'])), array('escape' => false, 'title' => 'Edit', 'class' => "edidlt")); ?>
                                <?php echo $this->Html->link($this->html->image('/img/admin/view.png'), array("controller" => "Brands", "action" => "view", base64_encode($brand['Brand']['id'])), array('escape' => false, 'title' => 'View', 'class' => "edidlt")); ?>
                                <?php echo $this->Html->link($this->html->image('/img/admin/delete.png'), array("controller" => "Commons", "action" => "Delete", base64_encode($brand['Brand']['id']), 'Brand'), array('escape' => false, 'title' => 'Delete', 'class' => "edidlt", 'onclick' => "return confirm('Are you sure you want to delete this brand ?');")); ?>
                            </td>    
                        </tr>
                    <?php endforeach;
                    }
                    else
                    {
                    echo '<tr><td colspan="6"><center>No result found</center></td></tr>';
                    }
                    ?>  
                </table><br/>
                <?php echo $this->Form->submit("Deactivate", array('class' => "blu_btn mar_rt", 'div' => false, 'id' => 'loginButton', 'title' => 'Deactivate', 'name' => 'deactivate', 'onclick' => "return atleastOneChecked('Deactivate selected Brands?');")); ?>
                <?php echo $this->Form->submit("Activate", array('class' => "blu_btn mar_rt", 'div' => false, 'id' => 'loginButton', 'title' => 'Activate', 'name' => 'activate', 'onclick' => "return atleastOneChecked('Activate selected Brands?');")); ?>
                <?php echo $this->Form->submit("Delete", array('class' => "blu_btn mar_rt", 'div' => false, 'id' => 'loginButton', 'title' => 'Activate', 'name' => 'delete', 'onclick' => "return atleastOneChecked('Deleted selected Brands?');")); ?>
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
<script>
    $(document).ready(function() {
        
        $("#BrandLimit").change(function(){
            $("#BrandListForm").submit();
        });
       
    });
</script>