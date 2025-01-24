<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'rfid_system';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM transactions ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RFID Transactions</title>
</head>
<body>
    <div class="header">
        <h1>RFID Transactions</h1>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Initial Balance</th>
                    <th>Transport Fare</th>
                    <th>New Balance</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Check if there are any records
                if ($result->num_rows > 0) {
                    // Output data for each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row['id'] . "</td>
                                <td>" . $row['customer'] . "</td>
                                <td>" . $row['initial_balance'] . "</td>
                                <td>" . $row['transport_fare'] . "</td>
                                <td>" . $row['new_balance'] . "</td>
                                <td>" . $row['timestamp'] . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No transactions found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
