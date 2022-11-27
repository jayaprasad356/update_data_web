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


if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User ID is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['manager_id'])) {
    $response['success'] = false;
    $response['message'] = "Manager ID is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['amount'])) {
    $response['success'] = false;
    $response['message'] = "Amount is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['remarks'])) {
    $response['success'] = false;
    $response['message'] = "Remarks is Empty";
    print_r(json_encode($response));
    return false;
}

$user_id = $db->escapeString($_POST['user_id']);
$manager_id = $db->escapeString($_POST['manager_id']);
$amount = $db->escapeString($_POST['amount']);
$remarks = $db->escapeString($_POST['remarks']);

$sql = "UPDATE users SET balance = balance + $amount WHERE id = $user_id";
$db->sql($sql);

$sql = "INSERT INTO transactions (`user_id`,`manager_id`,`amount`,`remarks`)VALUES('$user_id','$manager_id','$amount','$remarks')";
$db->sql($sql);
$res = $db->getResult();
$response['success'] = true;
$response['message'] = "Transaction Registered Successfully";
print_r(json_encode($response));

?>