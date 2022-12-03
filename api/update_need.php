<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include_once('../includes/crud.php');

$db = new Database();
$db->connect();


if (empty($_POST['manager_id'])) {
    $response['success'] = false;
    $response['message'] = " Manager Id is Empty";
    print_r(json_encode($response));
    return false;
}

if (empty($_POST['date'])) {
    $response['success'] = false;
    $response['message'] = "Date is Empty";
    print_r(json_encode($response));
    return false;
}

$manager_id=$db->escapeString($_POST['manager_id']);
$date = $db->escapeString($_POST['date']);
$amount = $db->escapeString($_POST['amount']);

$sql = "SELECT * FROM need_amount WHERE date='$date' AND manager_id='$manager_id'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);


if ($num >= 1) {
    $sql = "UPDATE need_amount SET amount = $amount  WHERE manager_id=$manager_id AND date='$date'";
    $db->sql($sql);
    $res = $db->getResult();
    $response['success'] = true;
    $response['message'] = "Update Need Updated Successfully";
    print_r(json_encode($response));
}
else{
    $sql = "INSERT INTO need_amount (`manager_id`,`amount`,`date`)VALUES('$manager_id','$amount','$date')";
    $db->sql($sql);
    $res = $db->getResult();
    $response['success'] = true;
    $response['message'] = "Update need Added Successfully";
    print_r(json_encode($response));

}

print_r(json_encode($response));




?>
