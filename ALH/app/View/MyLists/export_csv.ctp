<?php
//pr($report_setting);die;
ini_set('max_execution_time', 600); //increase max_execution_time to 10 min if data set is very large
$filename = "Email_list.xls"; //create a file
$xls_file = fopen('php://output', 'w');
//header('Content-type: application/vnd.ms-excel');
header('Content-type: application/excel');
header('Content-Disposition: attachment; filename="' . $filename . '"');
?>

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
<?php
fclose($xls_file);
exit;
?>