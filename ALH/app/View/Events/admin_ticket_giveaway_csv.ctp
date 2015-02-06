<?php
//pr($report_setting);die;
ini_set('max_execution_time', 600); //increase max_execution_time to 10 min if data set is very large
$filename = "TicketGiveaway_list.xls"; //create a file
$xls_file = fopen('php://output', 'w');
//header('Content-type: application/vnd.ms-excel');
header('Content-type: application/excel');
header('Content-Disposition: attachment; filename="' . $filename . '"');
?>

<table>
    <tr>
        <td colspan="3" bgcolor="#246F97"></td>
        <td colspan="2" bgcolor="#246F97"><b><font color="#FFFFFF" size = "3;">Ticket Giveaway Request Records</font></b></td>
        <td colspan="3" bgcolor="#246F97"></td>   
    </tr>
    <?php if (!empty($datas)) { ?>
        <tr>
            <td><b><font color = "#246F97">Sr. No</font></b></td>
            <td><b><font color = "#246F97">Event Title</font></b></td>
            <td><b><font color = "#246F97">Event Starts Date</font></b></td>
            <td><b><font color = "#246F97">Event Ends Date</font></b></td>
            <td><b><font color = "#246F97">First Name</font></b></td>
            <td><b><font color = "#246F97">Last Name</font></b></td>
            <td><b><font color = "#246F97">Email</font></b></td>
            <td><b><font color = "#246F97">Zip</font></b></td>
            <td><b><font color = "#246F97">Phone</font></b></td>
            <td colspan="4"><b><font color = "#246F97">Why I Want To Join This</font></b></td>
            <td><b><font color = "#246F97">Status</font></b></td>
            <td><b><font color = "#246F97">Created Date</font></b></td>
            <td><b><font color = "#246F97">Modified Date</font></b></td>
        </tr>
        <?php
        $i = 0;
        foreach ($datas as $data) {
            $i++;
            ?>
            <tr>
                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php echo $i; ?></td>
                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($data['Event']['title']))
                            echo $data['Event']['title'];
                        else
                            echo "N/A";
                        ?></td>
                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($data['Event']['start_date']))
                            echo date('m/d/Y', strtotime($data['Event']['start_date']));
                        else
                            echo "N/A";
                        ?></td>
                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($data['Event']['end_date']))
                            echo date('m/d/Y', strtotime($data['Event']['end_date']));
                        else
                            echo "N/A";
                        ?></td>
                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($data['TicketGiveaway']['first_name']))
                            echo $data['TicketGiveaway']['first_name'];
                        else
                            echo "N/A";
                        ?></td>
                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($data['TicketGiveaway']['last_name']))
                            echo $data['TicketGiveaway']['last_name'];
                        else
                            echo "N/A";
                        ?></td>
                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($data['TicketGiveaway']['email']))
                            echo $data['TicketGiveaway']['email'];
                        else
                            echo "N/A";
                        ?></td>
                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($data['TicketGiveaway']['zip']))
                            echo $data['TicketGiveaway']['zip'];
                        else
                            echo "N/A";
                        ?></td>
                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($data['TicketGiveaway']['phone']))
                            echo $data['TicketGiveaway']['phone'];
                        else
                            echo "N/A";
                        ?></td>


                <td colspan="4" bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($data['TicketGiveaway']['reason']))
                            echo $data['TicketGiveaway']['reason'];
                        else
                            echo "N/A";
                        ?></td>

                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if ($data['TicketGiveaway']['status'] == 1)
                            echo "Active";
                        else
                            echo "Inactive";
                        ?></td>
                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($data['TicketGiveaway']['created']))
                            echo date('m/d/Y', strtotime($data['TicketGiveaway']['created']));
                        else
                            echo "N/A";
                        ?></td>
                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($data['TicketGiveaway']['modified']))
                            echo date('m/d/Y', strtotime($data['TicketGiveaway']['modified']));
                        else
                            echo "N/A";
                        ?></td>

            </tr>                 
            <?php
        }
    } else {
        echo "<tr><td>No record found</td></tr>";
    }
    ?>
</table>
<?php
fclose($xls_file);
exit;
?>