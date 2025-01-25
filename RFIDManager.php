<?php

class RFIDManager {
    private $con;
    private $TBL_TRANSACTIONS;

    public function __construct($tbl = "rfid_transactions") {
        $this->TBL_TRANSACTIONS = $tbl;

        $this->con = new mysqli('localhost', 'MUCYO Ivan', 'ivan.2008', 'rfid_system');

        if ($this->con->connect_error) {
            die("Connection failed: " . $this->con->connect_error);
        }

        $sql = "
            CREATE TABLE IF NOT EXISTS " . $this->TBL_TRANSACTIONS . " (
              id INT AUTO_INCREMENT PRIMARY KEY,
              customer VARCHAR(255) NOT NULL,
              initial_balance DECIMAL(10, 2) NOT NULL,
              transport_fare DECIMAL(10, 2) NOT NULL,
              new_balance DECIMAL(10, 2) NOT NULL,
              timestamp DATETIME NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";
        if (!$this->con->query($sql)) {
            die("Error creating table: " . $this->con->error);
        }
    }

    public function getTableName() {
        return $this->TBL_TRANSACTIONS;
    }

    public function saveTransaction($customer, $initial_balance, $transport_fare, $timestamp) {
        $new_balance = $initial_balance - $transport_fare;
        $sql = "INSERT INTO " . $this->TBL_TRANSACTIONS . " 
                (customer, initial_balance, transport_fare, new_balance, timestamp) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("sddds", $customer, $initial_balance, $transport_fare, $new_balance, $timestamp);

        if ($stmt->execute()) {
            echo "Transaction uploaded successfully!";
        } else {
            echo "Failed to save transaction: " . $stmt->error;
        }
        $stmt->close();
    }

    public function __destruct() {
        if ($this->con) {
            $this->con->close();
        }
    }
}
?>
