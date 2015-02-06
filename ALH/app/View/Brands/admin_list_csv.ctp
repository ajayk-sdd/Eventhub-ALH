<?php
//pr($report_setting);die;
ini_set('max_execution_time', 600); //increase max_execution_time to 10 min if data set is very large
$filename = "Brand_list.xls"; //create a file
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
    <?php if(!empty($brands)){?>
    <tr>
        <td><b><font color = "#246F97">Sr. No</font></b></td>
        <td><b><font color = "#246F97">Name</font></b></td>
        <td><b><font color = "#246F97">Username</font></b></td>
        <td colspan="4"><b><font color = "#246F97">Description</font></b></td>
        <td><b><font color = "#246F97">Status</font></b></td>
        <td><b><font color = "#246F97">Created Date</font></b></td>
        <td><b><font color = "#246F97">Modified Date</font></b></td>
    </tr>
    <?php
    $i = 0;
    foreach ($brands as $brand) {
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
                    if (!empty($brand['Brand']['name']))
                        echo $brand['Brand']['name'];
                    else
                        echo "N/A";
                    ?></td>
            <td bgcolor = "<?php
            if ($i % 2 == 0)
                echo '#d4d4d4';
            else
                echo '#FFFFFF';
            ?>"><?php
                    if (!empty($brand['User']['username']))
                        echo $brand['User']['username'];
                    else
                        echo "N/A";
                    ?></td>
            
           
            <td colspan="4" bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                if (!empty($brand['Brand']['description']))
                    echo $brand['Brand']['description'];
                else
                    echo "N/A";
                ?></td>
           
            <td bgcolor = "<?php
                    if ($i % 2 == 0)
                        echo '#d4d4d4';
                    else
                        echo '#FFFFFF';
                    ?>"><?php
            if ($brand['Brand']['status'] == 1)
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
            if (!empty($brand['Brand']['created']))
                echo date('m/d/Y', strtotime($brand['Brand']['created']));
            else
                echo "N/A";
            ?></td>
            <td bgcolor = "<?php
                    if ($i % 2 == 0)
                        echo '#d4d4d4';
                    else
                        echo '#FFFFFF';
                    ?>"><?php
        if (!empty($brand['Brand']['modified']))
            echo date('m/d/Y', strtotime($brand['Brand']['modified']));
        else
            echo "N/A";
        ?></td>

        </tr>                 
    <?php
    } } else {
        echo "<tr><td>No record found</td></tr>";
    }
?>
</table>
<?php
fclose($xls_file);
exit;
?>