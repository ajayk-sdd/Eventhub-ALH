<section id="cont_wrapper">
    <section class="content">
        <h1 class="main_heading"><?php echo $this->Html->link('Set Point', 'javascript:void(0);'); ?></h1>
        <section class="content_info">
            <?php echo $this->Session->flash(); ?>
            <section class="tbldata">
                <?php //echo $this->Form->create('Common', array('Regions', 'action' => 'selectMultiple?model=Region'));?>

                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabulardata">
                    <tr>
                        <th>Current Points Price Per Unit(USD)</th>
                        <th>Last Modified</th>
                        <th>Set New Price</th>
                        <th>Action</th>
                    </tr>
                    <tr>
                        <td>
                            <span id = "point_price"><?php echo $point["Point"]["price"]; ?></span>
                        </td>
                        <td>
                            <?php echo date("m/d/Y", strtotime($point["Point"]["modified"])); ?>
                        </td>
                        <td>
                            <input type="text" id="change" placeholder="Enter New Price" class="form_input">
                        </td>
                        <td>
                            <input type="button" class="blu_btn mar_rt" value="Update" onclick="javascript:update_point_price();">
                        </td>
                    </tr>
                </table>
                <?php //echo $this->Form->end(); ?>
            </section>
            <input type="button" class="blu_btn mar_rt" value="Back" onclick="javascript:history.back();">
            <section class="clr_bth"></section>
        </section>
    </section>
</section>
<script>
    function update_point_price() {
        $('#load').show();
        var price = $("#change").val();
        var check = check_float("change");
        if (price.trim() == "") {
            alert("Field cannot be left blank");
            $('#load').hide();
        } else if (check == 0) {
            alert("Please enter a valid number");
            $('#load').hide();
        } else {
            jQuery.ajax({
                url: '/admin/Points/changePrice/' + price,
                success: function(data) {
                    $('#load').hide();
                    if (data == 1) {
                        alert("Price set sucessfully");
                        $("#point_price").html(price);
                    } else {
                        alert("Unable to set price, try again");
                    }
                }
            });
        }
    }
    function check_float(id) {


        var entered_value = $("#" + id).val();
        var regexPattern = /^\d{0,8}(\.\d{1,2})?$/;
        //Allow only Number as well 0nly 2 digit after dot(.)

        if (regexPattern.test(entered_value)) {
            return 1;
        } else {
            return 0;
        }


    }
</script>
