<?php
//pr($report_setting);die;
ini_set('max_execution_time', 600); //increase max_execution_time to 10 min if data set is very large
$filename = "Contacts.csv"; //create a file
//$xls_file = fopen('php://output', 'w');
//header('Content-type: application/vnd.ms-excel');
header('Content-type: application/csv;');
header('Content-Disposition: attachment; filename="' . $filename . '"');
if(!empty($datas))
{
    echo "Sr.No.,Email,First-Name,Last-Name,Phone\n";
}
else
{
    echo "No Record in List.";
}
/*?>

<table>
    <tr>
        <td colspan="1" bgcolor="#246F97"></td>
        <td colspan="4" bgcolor="#246F97"><b><font color="#FFFFFF" size = "3;">Email Records</font></b></td>
    </tr>
    <?php if (!empty($datas)) { ?>
        <tr>
            <td><b><font color = "#246F97">Sr. No</font></b></td>
            <td colspan="4"><b><font color = "#246F97">Email</font></b></td>
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
                <td colspan="4"bgcolor = "<?php
                if ($i % 2 == 0)
                    echo '#d4d4d4';
                else
                    echo '#FFFFFF';
                ?>"><?php
                        if (!empty($data))
                            echo $data;
                        else
                            echo "N/A";
                        ?></td>
            <?php
        }
    } else {
        echo "<tr><td>No record found</td></tr>";
    }
    ?>
</table>
<?php */

function outputCSV($data) {
    $output = fopen("php://output", "w");
    $i=1;
    foreach ($data as $row) {
        array_unshift($row['ListEmail'],$i);
        fputcsv($output, $row['ListEmail']); // here you can change delimiter/enclosure
        $i++;
    }
    fclose($output);
}

outputCSV($datas);

//fclose($xls_file);
exit;
?>