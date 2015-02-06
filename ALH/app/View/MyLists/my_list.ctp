<?php //pr($mylists);die;             ?>
<style>
    .clm-wrap table th, .clm-wrap table td{
        min-width: 0px !important;
    }
</style>
<div class="center-block">
    <div class="head-new">
        <h1>Email Lists</h1>
        <?php echo $this->Session->flash(); ?>
    </div>
    <?php
    echo $this->html->link("Add New List", array("controller" => "MyLists", "action" => "add"), array("class" => "btn-all", "style"=>"float:right"));
    echo "Sort By: ".$this->Form->input("sort", array("type" => "select", "div" => FALSE, "label" => FALSE, "options" => array("sort:MyList.list_name/direction:asc" => "Name ASC", "sort:MyList.list_name/direction:desc" => "Name DESC", "sort:MyList.created/direction:asc" => "Created ASC", "sort:MyList.created/direction:desc" => "Created DESC"), "onchange" => "javascript:sort(this.value);", "empty" => "Select"));
    ?>
    <div class="clear"></div> <div class="clm-wrap">
        <table>
            <thead>
                <tr>
                    <th width="6%">Sr. No</th>
                    <th width="18%"><?php echo $this->Paginator->sort('MyList.list_name', 'List Name'); ?>
                        <span class="<?php echo ('MyList.list_name' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "list_name ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                    <th width="14%">Total Subscriber</th>
                    <th width="12%">Open Rate</th>
                    <th width="14%">Click Rate</th>
                    <th width="24%">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (empty($mylists)) {
                    echo "<tr><td colspan='6'><center>No Data Found</center></td></td>";
                } else {
                    $i = 0;
                    foreach ($mylists as $mylist) {
                        $i++;
                        ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $mylist["MyList"]["list_name"] . "<br>Created " . date("F d, y", strtotime($mylist["MyList"]["created"])); ?></td>
                            <td><?php echo $mylist["MyList"]["total_mail"]; ?></td>
                            <td><?php
                                if ($mylist["MyList"]["total_mail"] > 0) {
                                    echo round((($mylist["MyList"]["open_mail"] / $mylist["MyList"]["total_mail"]) * 100), 2) . "%";
                                } else {
                                    echo "0%";
                                }
                                ?></td>
                            <td><?php
                                if ($mylist["MyList"]["total_mail"] > 0) {
                                    echo round((($mylist["MyList"]["click_mail"] / $mylist["MyList"]["total_mail"]) * 100), 2) . "%";
                                } else {
                                    echo "0%";
                                }
                                ?></td>
                            <td style="min-width: 246px;"><?php
//                                echo "&nbsp;" . $this->html->link("View", array("controller" => "MyLists", "action" => "view", base64_encode($mylist["MyList"]["id"])));
//                                echo "&nbsp;" . $this->html->link("Edit", array("controller" => "MyLists", "action" => "add", base64_encode($mylist["MyList"]["id"])));
//                                echo "&nbsp"  . $this->html->link("Delete", array("controller" => "MyLists", "action" => "delete", base64_encode($mylist["MyList"]["id"])), array("onclick" => "javascript:return confirm('Are you sure you want to delete this list?');"));
//                                echo "&nbsp;" . $this->html->link("CSV", array("controller" => "MyLists", "action" => "exportCsv", base64_encode($mylist["MyList"]["id"])));
                                echo "&nbsp;" . $this->html->link("Add Contact", array("controller" => "MyLists", "action" => "listuserDetail", base64_encode($mylist["MyList"]["id"])));
                                $mylist_id = base64_encode($mylist["MyList"]["id"]);
                                echo $this->Form->input('view', array('options' => array("view" => "List Contact", "add" => "Edit", "delete" => "Delete", "exportCsv" => "Export", "importForList" => "Import", "replicate" => "Replicate"), 'type' => 'select', "div" => FALSE, "label" => FALSE, "empty" => "Choose Action", "onchange" => "javascript:viewAction(this.value,'$mylist_id');"));
                                ?></td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="clear"></div>

    <div class="event-pagination paginate-list">
        <span class="peginationTxt"><?php echo $this->Paginator->counter(array('format' => 'List %start% - %end% of %count%')); ?></span>
        <?php
        echo $this->Paginator->first('', null, null, array());

        echo $this->Paginator->prev('', null, null, array());
        //echo $this->Paginator->numbers(array('separator' => ''));
        echo $this->Paginator->next('', null, null, array());
        echo $this->Paginator->Last('', null, null, array());
        //echo $this->Paginator->counter(array('format' => 'Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%'));
        ?>
        <div class="clear"></div>
    </div>
</div>
<script>
    function viewAction(selectedAction, myListId) {
        if (selectedAction.trim() != "") {
            if (selectedAction == "delete") {
                if (confirm("Are you sure you want to delete this list.")) {
                    window.location = "<?php echo "http://" . $_SERVER["HTTP_HOST"]; ?>/MyLists/" + selectedAction + "/" + myListId;
                } else {

                }
            } else {
                window.location = "<?php echo "http://" . $_SERVER["HTTP_HOST"]; ?>/MyLists/" + selectedAction + "/" + myListId;
            }

        }
    }
    function sort(selectedAction) {
        if (selectedAction.trim() != "") {
            window.location = "<?php echo "http://" . $_SERVER["HTTP_HOST"]; ?>/MyLists/myList/" + selectedAction;
        } else {

        }
    }
</script>