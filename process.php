<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $account_no = $conn->real_escape_string($_POST['account_no']);
    $amount = $_POST['amount'];
    $location = $conn->real_escape_string($_POST['location']);

    // 1. Get User ID from Account Number
    $user_res = $conn->query("SELECT id FROM users WHERE account_no = '$account_no'");
    
    if ($user_res->num_rows > 0) {
        $user = $user_res->fetch_assoc();
        $user_id = $user['id'];
        $is_fraud = 0;

        // 2. SMART LOGIC: Check the last known location
        $last_res = $conn->query("SELECT location FROM transactions WHERE user_id = '$user_id' ORDER BY id DESC LIMIT 1");
        
        if ($last_res->num_rows > 0) {
            $last_trans = $last_res->fetch_assoc();
            
            // If the new location is different from the last one, flag it!
            if ($last_trans['location'] !== $location) {
                $is_fraud = 1;
            }
        }

        // 3. Save the transaction
        $stmt = "INSERT INTO transactions (user_id, amount, location, is_fraud) VALUES ('$user_id', '$amount', '$location', '$is_fraud')";
        
        if ($conn->query($stmt)) {
            if ($is_fraud == 1) {
                echo "<div style='color:white; background:red; padding:20px;'>⚠️ FRAUD DETECTED: Location mismatch from previous transaction!</div>";
            } else {
                echo "<div style='color:white; background:green; padding:20px;'>✅ Transaction Verified & Successful.</div>";
            }
        }
    } else {
        echo "Account not found.";
    }
}
?>