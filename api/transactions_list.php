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
    $response['message'] = "Manager ID is Empty";
    print_r(json_encode($response));
    return false;
}
$manager_id = $db->escapeString($_POST['manager_id']);


$sql = "SELECT * FROM transactions t,users u WHERE t.user_id = u.id AND t.manager_id = $manager_id";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num >= 1){
    $response['success'] = true;
    $response['message'] = "Transactions Listed Successfully";
    $response['data'] = $res;
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "No Transactions List found";
    print_r(json_encode($response));

}
