<table>
    <th>Event Title</th>
    <th>Scheduled Date</th>
    <th>Event Status</th>
    <th>Action</th>
    <?php foreach($events as $event):?>
    <tr class="tr_<?php echo $event['Event']['id'];?>">
        <td><?php echo $event['Event']['title'];?></td>
        <td><?php echo $newDate = date("m / d / Y", strtotime($event['Event']['start_date']));?></td>
        <td><?php echo $event['Event']['status'];?></td>
        <td><?php echo $this->Html->link('Delete','javascript:void(0);', array('escape' => false, 'title' => 'Delete', 'class' => "deleteEvent", 'dir'=>$event['Event']['id'])); ?></td>

    </tr>
    <?php endforeach;?>
</table>
<script type="text/javascript">
    $(document).ready(function(){
        $('.deleteEvent').click(function(){
            var eventId = $(this).attr('dir');
            $.ajax({
                url :'/Events/eventStatus/'+eventId,
                success:function(data) {
                    if(data=="Success"){
                        $(".tr_"+eventId).hide();
                    }
                    
                }
            });
        });
    });
</script>