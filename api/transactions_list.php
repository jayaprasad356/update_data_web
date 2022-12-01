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
if (empty($_POST['date'])) {
    $response['success'] = false;
    $response['message'] = "Date is Empty";
    print_r(json_encode($response));
    return false;
}
$manager_id = $db->escapeString($_POST['manager_id']);
$date = $db->escapeString($_POST['date']);


$sql = "SELECT * FROM transactions t,users u WHERE t.user_id = u.id AND t.manager_id = $manager_id AND t.date='$date'";
$db->sql($sql);
$res = $db->getResult();
$sql = "SELECT SUM(amount) AS total_balance FROM transactions WHERE manager_id = $manager_id";
$db->sql($sql);
$result = $db->getResult();
$total_balance=$result[0]['total_balance'];
$num = $db->numRows($res);
if ($num >= 1){
    foreach ($res as $row) {
        $temp['id'] = $row['id'];
        $temp['user_id'] = $row['user_id'];
        $temp['amount'] = $row['amount'];
        $temp['date'] = $row['date'];
        $temp['remarks'] = $row['remarks'];
        $temp['balance'] = $row['balance'];
        $temp['total_balance'] =$total_balance;
        $rows[] = $temp;
    }
    $response['success'] = true;
    $response['message'] = "Transactions Listed Successfully";
    $response['grand_total'] = $total_balance;
    $response['data'] = $rows;
  
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "No Transactions List found";
    print_r(json_encode($response));

}
