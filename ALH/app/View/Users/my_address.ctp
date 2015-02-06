<style type="text/css">
    a.tooltips {
        position: relative;
        display: inline;
    }
    a.tooltips span {
        position: absolute;
        width:380px;
        color: #FFFFFF;
        background: #000000;
        height: 30px;
        line-height: 30px;
        text-align: center;
        visibility: hidden;
        border-radius: 6px;
    }
    a.tooltips span:after {
        content: '';
        position: absolute;
        top: 50%;
        right: 100%;
        margin-top: -8px;
        width: 0; height: 0;
        border-right: 8px solid #000000;
        border-top: 8px solid transparent;
        border-bottom: 8px solid transparent;
    }
    a:hover.tooltips span {
        visibility: visible;
        opacity: 0.8;
        left: 100%;
        top: 50%;
        margin-top: -15px;
        margin-left: 15px;
        z-index: 999;
    }

</style>

<section class="inner-content">
    <div class="center-block">
        <div class="em-sec">
            <div class="profile-whole">
                <h1>My Account</h1>
            </div>
            <ul class="tabs profile-tabs">
                <li><a href="/Users/viewProfile">Profile & Preferences</a></li>
                <li><a href="/Events/MyEventList">Added Events</a></li>
                <li><a href="/brands/brandList" >List Subscriptions</a></li>
                <li><a href="/Users/BillingInfo">Billing Info</a></li>
                <li><a href="/Sales/orderList">Order History</a></li>
                <li class="active"><a href="/Users/myAddress">ADDRESSES</a></li>
                <li><a href="/Users/track">Track</a></li>
            </ul>
            <div class="content_outer">
                <div id="div3" class="content">

                    <div class="repo-list-wrap">
                        <div class="repo-list reli-II brnd-detail">
                            <?php
                            echo $this->Session->flash();
                            echo $this->Form->create("User", array("action" => "myAddress", "id" => "billingInfo", "class" => "event-form"));
                            //echo $this->Form->input("list", array("type" => "hidden", "div" => false, "value" => "roles"));
                            echo $this->Form->input("Address.id", array("type" => "hidden"));
                            echo $this->Form->input("Address.user_id", array("type" => "hidden", "value" => AuthComponent::user("id")));
                            //echo $this->Form->input("User.status", array("type" => "hidden"));
                            ?>
                            <dl>
                               <dt>Address Name: </dt>
                                <dd>
                                    <?php
                                    echo $this->Form->input("Address.name", array("type" => "text", "label" => false, "div" => false, "class" => "validate[required] ", "id" => "name"));
                                    ?>
                                </dd>

                                <dt>First Name: </dt>
                                <dd>
                                    <?php
                                    echo $this->Form->input("Address.first_name", array("type" => "text", "label" => false, "div" => false, "class" => "", "id" => "first_name", "value" => AuthComponent::user("first_name")));
                                    ?>
                                </dd>

                                <dt>Last Name: </dt>
                                <dd>
                                    <?php
                                    echo $this->Form->input("Address.last_name", array("type" => "text", "label" => false, "div" => false, "class" => "", "id" => "first_name", "value" => AuthComponent::user("last_name")));
                                    ?>
                                </dd>
                                <dt>Address Line 1:</dt>
                                <dd>
                                    <?php
                                    echo $this->Form->input("Address.billing_address_1", array("type" => "text", "label" => false, "div" => false, "class" => "", "id" => "billing_address_1"));
                                    ?>
                                </dd>
                                <dt>Address Line 2:</dt>
                                <dd>
                                    <?php
                                    echo $this->Form->input("Address.billing_address_2", array("type" => "text", "label" => false, "div" => false, "class" => "", "id" => "billing_address_2"));
                                    ?>
                                </dd>
                                <dt>State:</dt>
                                <dd>
                                    <?php
                                    echo $this->Form->input("Address.state", array("type" => "select", "label" => false, "div" => false, "class" => "sltbx-sm validate[required] ", "id" => "slctState", "options" => $zip, "empty" => "Select State"));
                                    ?>
                                    <span class="ajaxLoader" style="display:none;"><img src="/img/admin/loader.gif"</span>
                                </dd>
                                <dt>City: </dt>
                                <dd>
                                    <?php
                                   echo $this->Form->input("Address.city",array("div"=>FALSE,"label"=>FALSE,"id"=>"specify", "class" => "validate[required] "));
                                    ?>
                                </dd>
                                <dt>Zip: </dt>
                                <dd>
                                    <?php
                                    echo $this->Form->input("Address.zip", array("type" => "text", "label" => false, "div" => false, "class" => "validate[required] ", "id" => "my_zip"));
                                    ?>
                                </dd>
                                <dt>Country: </dt>
                                <dd>
                                    <?php
                                    $state = array('United States' => 'United States');
                                    echo $this->Form->input('Address.country', array("label" => false, "div" => false, 'type' => 'select', 'options' => $state, 'default'=>'United States', 'empty' => 'Select Country', "class" => "validate[required]"));
                                    ?>
                                </dd>
                            </dl>

                            <div class="go-back">
                                <input type="submit" value="Submit"> <input type="button" onclick="javascript:history.back();" value="Go Back"> 
                            </div>
                            <?php echo $this->Form->end(); ?>
                            <div class="clear"></div>

                        </div>
                    </div>
                    <div class="clear"></div>
                    <a href="/Users/myAddress" id="addNew" class="btn-all">Add New</a>
                    <div class="clear"></div>
                    <div class="clm-wrap">

                        <table>
                            <thead>
                                <tr>
                                    <th style="width:10%;">Name</th>
                                    <th style="width:10%;">First Name</th>
                                    <th style="width:12%;">Last Name</th>
                                    <th style="width:5%;">Billing Address 1</th>
                                    <th style="width:15%;">Billing Address 2</th>
                                    <th style="width:15%;">City</th>
                                    <th style="width:5%;">State</th>
                                    <th style="width:10%;">Zip</th>
                                    <th style="width:15%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($addresses)) {
                                    foreach ($addresses as $data):
                                        ?>
                                        <tr>
                                            <td><?php echo $data['Address']['name']; ?></td>
                                            <td><?php echo $data['Address']['first_name']; ?></td>
                                            <td><?php echo $data['Address']['last_name']; ?></td>
                                            <td><?php echo $data['Address']['billing_address_1']; ?></td>
                                            <td><?php echo $data['Address']['billing_address_2']; ?></td>
                                            <td><?php echo $data['Address']['city']; ?></td>
                                            <td><?php echo $data['Address']['state']; ?></td>
                                            <td><?php echo $data['Address']['zip']; ?></td>
                                            <td>
                                                <?php
                                                echo $this->Html->link('Edit', array("controller" => "Users", "action" => "myAddress", base64_encode($data['Address']['id'])), array('escape' => false, 'title' => 'Edit', 'class' => "edidlt"));

                                                echo $this->Html->link('Delete', array("controller" => "Commons", "action" => "Delete", base64_encode($data['Address']['id']), 'Address', 'admin' => true), array('escape' => false, 'title' => 'Delete', 'class' => "edidlt", 'onclick' => "return confirm('Are you sure you want to delete this address ?');"));
                                                ?>
                                            </td>    
                                        </tr>
                                        <?php
                                    endforeach;
                                }else {
                                    ?>
                                    <tr><td colspan='8' style="text-align:center;">No Card Added</td></tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="clear"></div>
                </div>
            </div></div></div>



</section>
<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('/js/jquery-ui-1.10.4.min'); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#billingInfo").validationEngine();

//        var state = $('#slctState').val();
//        if (state != "") {
//            jQuery.ajax({
//                url: '/Events/findCity/' + state,
//                success: function(data) {
//                    $(".city").html(data);
//                    $('.ajaxLoader').hide();
//                }
//            });
//        }
        $("#slctState").change(function() {
            $('.ajaxLoader').show();
            var stateab = $('#slctState').val();

            var availableTags = "";
            jQuery.ajax({
                url: '/Events/findCity/' + stateab,
                async: false,
                success: function(data) {
                    availableTags = data;
                    $('.ajaxLoader').hide();
                }
            });
            $("#specify").autocomplete({
                source: JSON.parse(availableTags)
            });
        });
        $(".city").change(function() {
            var zip = $("option:selected", this).attr("rel");
            $("#my_zip").val(zip);
        });
    });
//    $(function() {
//        var availableTags = $("#my_city").val();
//        $("#cities").autocomplete({
//            source: availableTags
//        });
//    });
</script>
