<?php //pr($datas);die;        ?>
<div class="center-block">
    <div class="head-new">
        <h1>Email Lists</h1>
        <?php echo $this->Session->flash();?>
    </div>
    <?php echo $this->html->link("Add New Segment", array("controller" => "MyLists", "action" => "segment",  base64_encode($mylist_id)), array("class" => "btn-all")); ?>
    <div class="clear"></div> <div class="clm-wrap">
        <table style="width:100%">
            <thead>
                <tr>
                    <th width="10%">Sr. No</th>
                    <th width="30%"><?php echo $this->Paginator->sort('Segment.name', 'Name'); ?>
                        <span class="<?php echo ('Segment.name' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "list_name ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                    <th width="32%"><?php echo $this->Paginator->sort('Segment.created', 'Created DateTime'); ?>
                        <span class="<?php echo ('Segment.created' == $this->Paginator->sortKey()) ? $this->Paginator->sortDir() : 'none'; ?><?php if ($this->Paginator->sortKey() == "created ASC") { ?> asc <?php } ?>">&nbsp;&nbsp;&nbsp;</span></th>
                    <th width="30%">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (empty($datas)) {
                    echo "<tr><td colspan='6'><center>No Data Found</center></td></td>";
                } else {
                    $i = 0;
                    foreach ($datas as $data) {
                        $i++;
                        ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $data["Segment"]["name"]; ?></td>
                            <td><?php echo $data["Segment"]["created"]; ?></td>
                            <td style="min-width: 246px;"><?php
                                echo "&nbsp;" . $this->html->link("Edit", array("controller" => "MyLists", "action" => "editSegment", base64_encode($data["Segment"]["id"])));
                                echo "&nbsp" . $this->html->link("Delete", array("controller" => "MyLists", "action" => "deleteSegment", base64_encode($data["Segment"]["id"])), array("onclick" => "javascript:return confirm('Are you sure you want to delete this segment?');"));
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