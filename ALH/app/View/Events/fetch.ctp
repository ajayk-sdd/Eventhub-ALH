<?php
if (!empty($op)) {
    if (isset($op["login"])) {
        echo "<label style='color:red'>" . $op["login"] . "</label>";
    } else if (isset($op["contacts"])) {
        echo "<label style='color:red'>" . $op["contacts"] . "</label>";
    } else if (isset($op["inviter"])) {
        echo "<label style='color:red'>" . $op["inviter"] . "</label>";
    } else {
        $op = array_values($op);
        echo $this->Form->end();
        echo $this->Form->create("Event", array("action" => "invite", "id" => "inviteForm", 'enctype' => 'multipart/form-data'));
        echo $this->Form->input("provider", array("type" => "hidden", "id" => "event_provider"));
        ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabulardata">

            <?php
            echo $this->Form->input("message", array("type" => "textarea", "id" => "message", "label" => false));
            echo $this->Form->input("Invite", array("type" => "submit", "label" => FALSE, "div" => FALSE,"style"=>"background-color: #1273C4;
                               color: #FFFFFF;
                               padding: 10px 25px;"));
            $i = 0;
            foreach ($op as $op1):
                if ($i % 2 == 0)
                    echo "<tr>";
                $i++;
                ?>
                <td><?php echo $this->Form->checkbox('checkbox', array('type' => 'checkbox', 'class' => 'textbox case', 'label' => '', 'value' => $op1, 'name' => 'IDs[]', 'checked' => 'checked')); ?></td>
                <td><?php echo $op1; ?></td>
                <?php
                if ($i % 2 == 0)
                    echo "</tr>";
            endforeach;
            ?>  
        </table>
            <?php
            echo $this->Form->end();
        }
    } else {
        echo "<label style='color:red'>Unable to import, please check your credentials", "default</label>";
    }
    ?>
