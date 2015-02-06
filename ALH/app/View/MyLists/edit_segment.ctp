<div class="center-block">
    <div class="em-sec">

        <h1>Create Segment</h1>
        <?php echo $this->Session->flash(); ?>
        <div class="list-view-head">

            <?php
            echo "Subscribers match";
            echo $this->Form->input('any', array('options' => array("OR" => "Any", "AND" => "All"), 'type' => 'select', "div" => FALSE, "label" => FALSE));
            echo "of the following:<br>";

            $items = array("campaign_activity" => "Campaign Activity", "date_Added" => "Date Added", "info_changed" => "Info Changed", "location" => "Location");
            echo $this->Form->input('item', array('options' => $items, 'type' => 'select', "div" => FALSE, "label" => FALSE, "onchange" => "javascript:selectItem(this.value);"));

            echo $this->Form->input("list_id", array("type" => "hidden", "value" => $segment["Segment"]["my_list_id"], "id" => "my_list_id"));

            echo "<span id = 'container'></span>";

            echo "<span id = 'text'></span>";
            //echo $this->Form->input('text', array('type' => "text", "div" => FALSE, "label" => FALSE));
            ?>
            <input type="button" value="Preview Segment" onclick="javascript:previewSegment();" class="mhrn_button">
            <div id="saveSegmentSection" style="display:block; float: right;">
                <input type="text" id="nameSegment" placeholder="Name Segment" required="required" value="<?php echo $segment["Segment"]['name'];?>">
                <input type="button" value="Save Segment" onclick="javascript:submitSegmentForm();" class="mhrn_button">
            </div>
        </div>

<!--p class="">Showing Search Results for: <strong>"bikram yoga"</strong></p-->


        <?php echo $this->Session->flash(); ?>

        <!-- search panel start here -->
        <div style="clear:both;"></div>
        <div class="search-panel" id="segmentHere" style="height:400px; overflow: auto;">
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

                    <?php 
                    $emails = $segment["SegmentEmail"];
                    foreach ($emails as $email) { ?>
                        <tr id = "<?php echo $email["id"]; ?>">
                            <td style="width:50%;"><?php
                                echo $email["email"];
                                echo $this->Form->input("email", array("type" => "hidden", "value" => $email["email"], "class" => $email["id"], "name" => "data[SegmentEmail][email][]"));
                                ?></td>
                            <td style="width:50%;"><a href="javascript:void(0);" onclick="deleteFromSegment(<?php echo $email["id"]; ?>);" class="mhrn_button">Delete</a></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<script>
    var item = $("#item").val();
    //alert(item);
    selectItem(item);
    function selectItem(item) {
        $.ajax({
            url: '/MyLists/selectItem/' + item,
            success: function(data) {
                //$('#item_load_' + id).hide();
                $("#container").html(data);
                selectContains();

            }
        });
    }
    function selectContains() {
        var item = $("#item").val();
        var contain = $("#contain").val();
        $.ajax({
            url: '/MyLists/selectContain/' + item + '/' + contain,
            success: function(data) {
                //$('#item_load_' + id).hide();
                if (item != "location") {
                    $("#text").html(data);
                    $('#text_value').datepicker();
                } else {
                    $("#text").html(data);
                }

            }
        });
    }
    function previewSegment() {
        var item = $("#item").val();
        var contain = $("#contain").val();
        var text_value = $("#text_value").val();
        var my_list_id = $("#my_list_id").val();
        var zip_value = $("#zip_value").val();

        // to replce / from date
        if (text_value != "") {
            text_value = text_value.replace("/", "-");
            text_value = text_value.replace("/", "-");
        }
        if (zip_value != "") {
            var url = '/MyLists/previewSegment/' + my_list_id + '/' + item + '/' + contain + '/' + text_value + '/' + zip_value;
        } else {
            var url = '/MyLists/previewSegment/' + my_list_id + '/' + item + '/' + contain + '/' + text_value;
        }
        $.ajax({
            url: url,
            success: function(data) {
                //$('#item_load_' + id).hide();
                $("#segmentHere").html(data);
                $("#saveSegmentSection").show();
                //$(".dataTable").DataTable();

            }
        });
    }
    function deleteFromSegment(key) {
        var check = confirm("Are you sure you want to delete this?");
        if (check) {
            $("#" + key).remove();
        }
    }
    function submitSegmentForm() {
        var nameSegment = $("#nameSegment").val();
        var mylistid = "<?php echo $segment['Segment']['my_list_id']; ?>";
        var segmentId = "<?php echo $segment['Segment']['id'];?>"
        if (nameSegment != "") {
            $("#segmentName").val(nameSegment);
            $("#myListId").val(mylistid);
            $("#segmentId").val(segmentId);
            $("#segmentform").submit();
        } else {
            alert("Segment name is required.");
        }
    }
</script>