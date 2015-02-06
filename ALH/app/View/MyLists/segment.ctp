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

            echo $this->Form->input("list_id", array("type" => "hidden", "value" => $mylist_id, "id" => "my_list_id"));

            echo "<span id = 'container'></span>";

            echo "<span id = 'text'></span>";
            //echo $this->Form->input('text', array('type' => "text", "div" => FALSE, "label" => FALSE));
            ?>
            <input type="button" value="Preview Segment" onclick="javascript:previewSegment();" class="mhrn_button">
            <div id="saveSegmentSection" style="display:none; float: right;">
                <input type="text" id="nameSegment" placeholder="Name Segment" required="required">
                <input type="button" value="Save Segment" onclick="javascript:submitSegmentForm();" class="mhrn_button">
            </div>
        </div>

<!--p class="">Showing Search Results for: <strong>"bikram yoga"</strong></p-->


        <?php echo $this->Session->flash(); ?>

        <!-- search panel start here -->
        <div class="search-panel" id="segmentHere" style="height:400px; overflow: auto;">

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
        var mylistid = "<?php echo $mylist_id; ?>";
        if (nameSegment != "") {
            $("#segmentName").val(nameSegment);
            $("#myListId").val(mylistid);
            $("#segmentform").submit();
        } else {
            alert("Segment name is required.");
        }
    }
</script>