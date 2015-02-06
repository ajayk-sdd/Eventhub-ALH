<section id="cont_wrapper">
    <section class="content">
        <h1 class="main_heading"><?php echo $this->Html->link('Purchased Packages', 'javascript:void(0);'); ?></h1>
        <section class="content_info">
            <?php //echo $this->Session->flash(); ?>
            <section class="tbldata">
                <?php /* ?> <div class="srch_box">
                  <!------------------------------------ search starts --------------------->
                  <?php echo $this->Form->create("User", array("action" => "list", 'enctype' => 'multipart/form-data', "novalidate" => "novalidate"));
                  echo $this->Form->input('User.username', array('label' => "Username:", 'div' => false, 'maxlength' => '50', 'autocomplete' => 'off', 'class' => 'input form_input', 'placeholder' => 'Enter Username'));

                  echo $this->Form->input('User.email', array('label' => "Email:", 'div' => false, 'maxlength' => '50', 'autocomplete' => 'off', 'class' => 'form_input', 'placeholder' => 'Enter Email'));
                  echo $this->Form->input("User.limit", array('type' => 'select', 'options' => array("10" => "10", "20" => "20", "50" => "50", "100" => "100"), 'div' => false, 'label' => "Show Per Page:"));
                  echo $this->Form->input("User.order", array('type' => 'select', 'options' => array("User.username" => "Username", "User.email" => "Email"), 'div' => false, 'label' => "Order by:"));
                  echo $this->Form->input("User.direction", array('type' => 'select', 'options' => array("ASC" => "Ascending", "DESC" => "Descending"), 'div' => false, 'label' => "Direction:"));
                  echo $this->Form->submit('Search', array('label' => false, 'div' => false, "class" => "blu_btn mar_rt"));

                  echo $this->html->link('Clear Search', array('controller' => 'Users', 'action' => 'list'), array("class" => "blu_btn mar_rt"));

                  echo $this->html->link('Download CSV', array('controller' => 'Users', 'action' => 'list_csv'), array("class" => "blu_btn mar_rt"));
                  echo $this->Form->end();
                  ?>
                  </div><?php */ ?>
                <!--------------------------------- search ends ------------------------------->
                <?php echo $this->Form->create('Common', array('users', 'action' => 'selectMultiple?model=User')); ?>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabulardata">
                    <tr>
                        <th style="width:10%">Package</th>
                        <th style="width:16%;">Amount</th>
                        <th style="width:16%;">Transaction Id</th>
                        <th style="width:16%;">User Name</th>
                        <th style="width:16%;">Email</th>
                        <th style="width:10%;">Status</th>
                        <th style="width:18%;">Action</th>


                    </tr>
                    <?php foreach ($sales as $data): ?>
                        <tr>
                            <td><?php echo $data['Package']['name'] ?></td>
                            <td>$ <?php echo $data['Payment']['amount']; ?></td>
                            <td><?php echo $data['Payment']['transaction_id']; ?></td>
                            <td><?php echo $data['User']['first_name'] . ' ' . $data['User']['last_name']; ?></td>
                            <td> <?php echo $data['User']['email']; ?></td>
                            <td><?php
                                $model = "Payment";
                                if ($data['Payment']['status'] == 1) {

                                    echo "<img src='/img/admin/active.png' title='Change Status' id='" . $data['Payment']['id'] . "' value='" . $data['Payment']['status'] . "' dir='" . $model . "' class='statusImg' rel='" . base64_encode($data['Payment']['id']) . "'/>";
                                    ?>
                                    <span class="loader" id="load_<?php echo $data['Payment']['id']; ?>"><?php echo $this->html->image('/img/admin/loader.gif'); ?></span>
                                <?php } else {
                                    echo "<img src='/img/admin/inactive.png' title='Change Status' id='" . $data['Payment']['id'] . "' value='" . $data['Payment']['status'] . "' dir='" . $model . "' class='statusImg' rel='" . base64_encode($data['Payment']['id']) . "'/>";
                                    ?>
                                    <span class="loader" id="load_<?php echo $data['Payment']['id']; ?>"><?php echo $this->html->image('/img/admin/loader.gif'); ?></span>
                        <?php }
                        ?></td>
                            <td><?php echo $this->Html->link($this->html->image('/img/admin/view.png'), array("controller" => "Sales", "action" => "view", base64_encode($data['Payment']['id'])), array('escape' => false, 'title' => 'View', 'class' => "edidlt")); ?></td>
                        </tr>
                <?php endforeach; ?>  
                </table><br/>

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

