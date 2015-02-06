<?php
//pr($report_setting);die;
ini_set('max_execution_time', 600); //increase max_execution_time to 10 min if data set is very large
$filename = "Event_list.xls"; //create a file
$xls_file = fopen('php://output', 'w');
//header('Content-type: application/vnd.ms-excel');
header('Content-type: application/excel');
header('Content-Disposition: attachment; filename="' . $filename . '"');
?>

<table>
    <tr>
        <td colspan="3" bgcolor="#246F97"></td>
        <td colspan="2" bgcolor="#246F97"><b><font color="#FFFFFF" size = "3;">Events Records</font></b></td>
        <td colspan="3" bgcolor="#246F97"></td>   
    </tr>
    <?php if (!empty($events)) { ?>
        <tr>
            <td><b><font color = "#246F97">Sr. No</font></b></td>
            <td><b><font color = "#246F97">Username</font></b></td>
            <td><b><font color = "#246F97">Title</font></b></td>
            <td colspan="2"><b><font color = "#246F97">Sub-title</font></b></td>

            <td><b><font color = "#246F97">Event Type</font></b></td>
            <td><b><font color = "#246F97">Show on a list hub calendar ?</font></b></td>
            <td><b><font color = "#246F97">Show on a wordpress calendar ?</font></b></td>
            <td><b><font color = "#246F97">Event Location</font></b></td>
            <td><b><font color = "#246F97">Specify location</font></b></td>
            <td><b><font color = "#246F97">Address</font></b></td>
            <td><b><font color = "#246F97">City</font></b></td>
            <td><b><font color = "#246F97">State</font></b></td>
            <td><b><font color = "#246F97">Zip</font></b></td>
            <td><b><font color = "#246F97">Start Date</font></b></td>
            <td><b><font color = "#246F97">End Date</font></b></td>
            <td><b><font color = "#246F97">Ticket Vendor Url</font></b></td>
            <td><b><font color = "#246F97">Website URL</font></b></td>
            <td><b><font color = "#246F97">Facebook URL</font></b></td>
            <td><b><font color = "#246F97">Video</font></b></td>
            <td><b><font color = "#246F97">Name</font></b></td>
            <td><b><font color = "#246F97">Email</font></b></td>
            <td><b><font color = "#246F97">Phone Number</font></b></td>
            <td><b><font color = "#246F97">Allow Event to edit</font></b></td>
            <td><b><font color = "#246F97">Short Description</font></b></td>
            <td><b><font color = "#246F97">Long Description</font></b></td>
            <td><b><font color = "#246F97">Event URL For Share</font></b></td>
            <td><b><font color = "#246F97">Categories</font></b></td>
            <td><b><font color = "#246F97">Vibes</font></b></td>
            <td><b><font color = "#246F97">Status</font></b></td>
            <td><b><font color = "#246F97">Created Date</font></b></td>
            <td><b><font color = "#246F97">Modified Date</font></b></td>
        </tr>
        <?php
        $i = 0;
        foreach ($events as $event) {
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
                        if (!empty($event['User']['username']))
                            echo $event['User']['username'];
                        else
                            echo "N/A";
                        ?></td>
                <td colspan="2" bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($event['Event']['title']))
                            echo $event['Event']['title'];
                        else
                            echo "N/A";
                        ?></td>
                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($event['Event']['sub_title']))
                            echo $event['Event']['sub_title'];
                        else
                            echo "N/A";
                        ?></td>
                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($event['Event']['event_type']))
                            if ($event['Event']['event_type'] == 1)
                                echo 'Private';
                            else
                                echo 'Public';
                        else
                            echo "N/A";
                        ?></td>
                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($event['Event']['option_to_show']))
                            if ($event['Event']['option_to_show'] == 1)
                                echo 'Yes';
                            else
                                echo 'No';
                        else
                            echo "N/A";
                        ?></td>
                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($event['Event']['option_to_show']))
                            if ($event['Event']['option_to_show'] == 1)
                                echo 'Yes';
                            else
                                echo 'No';
                        else
                            echo "N/A";
                        ?></td>
                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($event['Event']['event_location']))
                            echo $event['Event']['event_location'];
                        else
                            echo "N/A";
                        ?></td>
                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($event['Event']['specify']))
                            echo $event['Event']['specify'];
                        else
                            echo "N/A";
                        ?></td>
                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($event['Event']['event_address']))
                            echo $event['Event']['event_address'];
                        else
                            echo "N/A";
                        ?></td>
                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($event['Event']['cant_find_city']))
                            echo $event['Event']['cant_find_city'];
                        else
                            echo "N/A";
                        ?></td>
                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($event['Event']['cant_find_state']))
                            echo $event['Event']['cant_find_state'];
                        else
                            echo "N/A";
                        ?></td><td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                                 if (!empty($event['Event']['cant_find_zip_code']))
                                     echo $event['Event']['cant_find_zip_code'];
                                 else
                                     echo "N/A";
                                 ?></td><td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                                 if (!empty($event['Event']['start_date']))
                                     echo $event['Event']['start_date'];
                                 else
                                     echo "N/A";
                                 ?></td><td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                                 if (!empty($event['Event']['end_date']))
                                     echo $event['Event']['end_date'];
                                 else
                                     echo "N/A";
                                 ?></td><td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                                 if (!empty($event['Event']['ticket_vendor_url']))
                                     echo $event['Event']['ticket_vendor_url'];
                                 else
                                     echo "N/A";
                                 ?></td><td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                                 if (!empty($event['Event']['website_url']))
                                     echo $event['Event']['website_url'];
                                 else
                                     echo "N/A";
                                 ?></td><td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                                 if (!empty($event['Event']['facebook_url']))
                                     echo $event['Event']['facebook_url'];
                                 else
                                     echo "N/A";
                                 ?></td><td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                                 if (!empty($event['Event']['video']))
                                     echo $event['Event']['video'];
                                 else
                                     echo "N/A";
                                 ?></td><td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                                 if (!empty($event['Event']['main_info_name']))
                                     echo $event['Event']['main_info_name'];
                                 else
                                     echo "N/A";
                                 ?></td><td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                                 if (!empty($event['Event']['main_info_email']))
                                     echo $event['Event']['main_info_email'];
                                 else
                                     echo "N/A";
                                 ?></td>
                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($event['Event']['main_info_phone_no']))
                            echo $event['Event']['main_info_phone_no'];
                        else
                            echo "N/A";
                        ?></td>
                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($event['Event']['allow_users_to_edit']))
                            echo $event['Event']['allow_users_to_edit'];
                        else
                            echo "N/A";
                        ?></td>
                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($event['Event']['short_description']))
                            echo $event['Event']['short_description'];
                        else
                            echo "N/A";
                        ?></td>
                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($event['Event']['description']))
                            echo $event['Event']['description'];
                        else
                            echo "N/A";
                        ?></td>
                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($event['Event']['event_url_for_share']))
                            echo $event['Event']['event_url_for_share'];
                        else
                            echo "N/A";
                        ?></td>
                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php foreach ($event["EventCategory"] as $cate) { ?>
                            <?php echo $cate["Category"]["name"] . ","; ?>
                    <?php } ?></td>
                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php foreach ($event["EventVibe"] as $vibe) { ?>
                            <?php echo $vibe["Vibe"]["name"] . ","; ?>
                    <?php } ?></td>
                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if ($event['Event']['status'] == 1)
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
                        if (!empty($event['Event']['created']))
                            echo date('m/d/Y', strtotime($event['Event']['created']));
                        else
                            echo "N/A";
                        ?></td>
                <td bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($event['Event']['modified']))
                            echo date('m/d/Y', strtotime($event['Event']['modified']));
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