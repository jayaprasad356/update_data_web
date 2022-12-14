<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
date_default_timezone_set('Asia/Kolkata');
include_once('../includes/crud.php');

$db = new Database();
$db->connect();
// if (empty($_POST['manager_id'])) {
//     $response['success'] = false;
//     $response['message'] = "Manager ID is Empty";
//     print_r(json_encode($response));
//     return false;
// }
// if (empty($_POST['date'])) {
//     $response['success'] = false;
//     $response['message'] = "Date is Empty";
//     print_r(json_encode($response));
//     return false;
// }
$manager_id = $db->escapeString($_POST['manager_id']);
$date = $db->escapeString($_POST['date']);


$sql = "SELECT * FROM transactions t,users u WHERE t.user_id = u.id AND t.manager_id = $manager_id AND date='$date'";
$db->sql($sql);
$res = $db->getResult();
$sql = "SELECT SUM(amount) AS total_balance FROM transactions WHERE manager_id = $manager_id AND date='$date'";
$db->sql($sql);
$result = $db->getResult();
$total_balance=$result[0]['total_balance'];
$yesterday_date=date('Y-m-d',strtotime("-1 days"));
$sql = "SELECT SUM(amount) AS yesterday_balance FROM transactions WHERE manager_id = $manager_id AND date='$yesterday_date'";
$db->sql($sql);
$ressy = $db->getResult();
$yesterday_balance= $ressy[0]['yesterday_balance'];
if($ressy[0]['yesterday_balance']  == null){
    $yesterday_balance= 0;

}

$sql = "SELECT * FROM need_amount WHERE manager_id = $manager_id AND date='$date'";
$db->sql($sql);
$resslot = $db->getResult();
$num = $db->numRows($resslot);
if($num>=1){
    $need_amount=$resslot[0]['amount'];
    $profit=$resslot[0]['profit'];
}
else{
    $need_amount=0;
    $profit=0;

}
$num = $db->numRows($res);
if ($num >= 1){
    $rows = array();
    foreach ($res as $row) {
        $temp['id'] = $row['id'];
        $temp['user_id'] = $row['user_id'];
        $temp['name'] = $row['name'];
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
    $response['yesterday_balance'] = $yesterday_balance;
    $response['need_amount'] = $need_amount;
    $response['profit'] =$profit;
    $response['data'] = $rows;
  
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "No Transactions List found";
    $response['grand_total'] = "0";
    $response['need_amount'] = "0";
    print_r(json_encode($response));

}
