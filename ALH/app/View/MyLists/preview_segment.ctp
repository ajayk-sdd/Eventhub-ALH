<?php
echo $this->Form->create("MyList", array("action" => "saveSegment", "id" => "segmentform", "name" => "segmentform"));
echo $this->Form->input("myListId", array("type" => "hidden", "id" => "myListId"));
echo $this->Form->input("name", array("type" => "hidden", "id" => "segmentName"));
echo $this->Form->input("id", array("type" => "hidden", "id" => "segmentId"));
?>
<table id="event" class="display clm-wrap dataTable" style="width:100%;">
    <thead>
        <tr>
            <th style="text-align: left;">Email</th>
            <th style="text-align: left;">action</th>
        </tr>
    </thead>
    <tbody>

        <?php foreach ($emails as $key => $value) { ?>
            <tr id = "<?php echo $key; ?>">
                <td style="width:50%;"><?php
                    echo $value;
                    echo $this->Form->input("email", array("type" => "hidden", "value" => $value, "class" => $key, "name" => "data[SegmentEmail][email][]"));
                    ?></td>
                <td style="width:50%;"><a href="javascript:void(0);" onclick="deleteFromSegment(<?php echo $key; ?>);" class="mhrn_button">Delete</a></td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>
<?php echo $this->Form->end(); ?>