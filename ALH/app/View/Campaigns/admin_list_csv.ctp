<?php
//pr($report_setting);die;
ini_set('max_execution_time', 600); //increase max_execution_time to 10 min if data set is very large
$filename = "Campaign_list.xls"; //create a file
$xls_file = fopen('php://output', 'w');
//header('Content-type: application/vnd.ms-excel');
header('Content-type: application/excel');
header('Content-Disposition: attachment; filename="' . $filename . '"');
?>

<table>
    <tr>
        <td colspan="3" bgcolor="#246F97"></td>
        <td colspan="2" bgcolor="#246F97"><b><font color="#FFFFFF" size = "3;">Campaigns Records</font></b></td>
        <td colspan="3" bgcolor="#246F97"></td>   
    </tr>
    <?php if (!empty($campaigns)) { ?>
        <tr>
            <td><b><font color = "#246F97">Sr. No</font></b></td>
            <td><b><font color = "#246F97">Username</font></b></td>
            <td colspan="2"><b><font color = "#246F97">Title</font></b></td>
            <td colspan="2"><b><font color = "#246F97">Subject</font></b></td>
            <td colspan="2"><b><font color = "#246F97">From Name</font></b></td>
            <td colspan="2"><b><font color = "#246F97">From Email</font></b></td>
            <td colspan="2"><b><font color = "#246F97">Reply To</font></b></td>
            <td colspan="2"><b><font color = "#246F97">Date To Send</font></b></td>
            <td><b><font color = "#246F97">Status</font></b></td>
            <td><b><font color = "#246F97">Current Status</font></b></td>
            <td colspan="2"><b><font color = "#246F97">Created Date</font></b></td>
            <td colspan="2"><b><font color = "#246F97">Modified Date</font></b></td>
        </tr>
        <?php
        $i = 0;
        foreach ($campaigns as $campaign) {
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
                        if (!empty($campaign['User']['username']))
                            echo $campaign['User']['username'];
                        else
                            echo "N/A";
                        ?></td>
                <td colspan="2" bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($campaign['Campaign']['title']))
                            echo $campaign['Campaign']['title'];
                        else
                            echo "N/A";
                        ?></td>
                <td colspan="2" bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($campaign['Campaign']['subject_line']))
                            echo $campaign['Campaign']['subject_line'];
                        else
                            echo "N/A";
                        ?></td>
                <td colspan="2" bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($campaign['Campaign']['from_name']))
                            echo $campaign['Campaign']['from_name'];
                        else
                            echo "N/A";
                        ?></td>
                <td colspan="2" bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($campaign['Campaign']['from_email']))
                            echo $campaign['Campaign']['from_email'];
                        else
                            echo "N/A";
                        ?></td>
                <td colspan="2" bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($campaign['Campaign']['reply_email']))
                            echo $campaign['Campaign']['reply_email'];
                        else
                            echo "N/A";
                        ?></td>
                <td colspan="2" bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($campaign['Campaign']['date_to_send']))
                            echo date("l, F d, Y", strtotime($campaign['Campaign']['date_to_send']));
                        else
                            echo "N/A";
                        ?></td>
                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($campaign['Campaign']['status']))
                            if ($campaign['Campaign']['status'] == 1)
                                echo 'Active';
                            else
                                echo 'Inactive';
                        else
                            echo "N/A";
                        ?></td>
                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"> <?php
                        $today = date("Y/m/d");
                        if ($campaign['Campaign']['date_to_send'] > $today) {
                            echo "Upcoming";
                        } else {
                            echo "Done";
                        }
                        ?></td>

                <td colspan="2" bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($campaign['Campaign']['created']))
                            echo date("l, F d, Y", strtotime($campaign['Campaign']['created']));
                        else
                            echo "N/A";
                        ?></td>
                <td colspan="2" bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($campaign['Campaign']['modified']))
                            echo date("l, F d, Y", strtotime($campaign['Campaign']['modified']));
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