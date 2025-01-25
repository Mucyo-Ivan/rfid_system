<?php
include_once("RFIDManager.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer = trim($_POST['customer'] ?? '');
    $initial_balance = floatval($_POST['initial_balance'] ?? 0);
    $transport_fare = floatval($_POST['transport_fare'] ?? 0);
    $timestamp = date("Y-m-d H:i:s"); 

    if (!empty($customer) && $initial_balance > 0 && $transport_fare > 0) {
        $rfidManager = new RFIDManager();
        $rfidManager->saveTransaction($customer, $initial_balance, $transport_fare, $timestamp);
    } else {
        echo "Invalid input data. Please ensure all fields are filled out correctly.";
    }
} else {
    echo "Invalid request method. Only POST requests are allowed.";
}
?>
