<?php
//pr($report_setting);die;
ini_set('max_execution_time', 600); //increase max_execution_time to 10 min if data set is very large
$filename = "User_list.csv"; //create a file
$xls_file = fopen('php://output', 'w');
//header('Content-type: application/vnd.ms-excel');
header('Content-type: application/excel');
header('Content-Disposition: attachment; filename="' . $filename . '"');

//header("Content-type: text/csv");
//header("Content-Disposition: attachment; filename=$filename.csv");
//header("Pragma: no-cache");
//header("Expires: 0");
?>

Sr. No,Username,Email,Role,Status,Created Date,Modified Date
<?php
$i = 0;
foreach ($users as $user) {
    $i++;
    ?>
    <?php echo $i; ?>,<?php
    if (!empty($user['User']['username']))
        echo $user['User']['username'];
    else
        echo "N/A";
    ?>,<?php
    if (!empty($user['User']['email']))
        echo $user['User']['email'];
    else
        echo "N/A";
    ?>,<?php
    if (!empty($user['Role']['name']))
        echo $user['Role']['name'];
    else
        echo "N/A";
    ?>,<?php
    if ($user['User']['status'] == 1)
        echo "Active";
    else
        echo "Inactive";
    ?>,<?php
    if (!empty($user['User']['created']))
        echo date('m/d/Y', strtotime($user['User']['created']));
    else
        echo "N/A";
    ?>,<?php
    if (!empty($user['User']['modified']))
        echo date('m/d/Y', strtotime($user['User']['modified']));
    else
        echo "N/A";
    ?>            
    <?php
}
?>
<?php
fclose($xls_file);
exit;
?>