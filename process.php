<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $account_no = $conn->real_escape_string($_POST['account_no']);
    $amount = $_POST['amount'];
    $location = $conn->real_escape_string($_POST['location']);

    $user_res = $conn->query("SELECT id FROM users WHERE account_no = '$account_no'");
    
    if ($user_res->num_rows > 0) {
        $user = $user_res->fetch_assoc();
        $user_id = $user['id'];
        $is_fraud = 0;
        $reason = ""; // To keep track of why it was flagged

        // 2. SMART LOGIC: Check the last known location
        $last_res = $conn->query("SELECT location FROM transactions WHERE user_id = '$user_id' ORDER BY id DESC LIMIT 1");
        
        if ($last_res->num_rows > 0) {
            $last_trans = $last_res->fetch_assoc();
            if ($last_trans['location'] !== $location) {
                $is_fraud = 1;
                $reason = "Location mismatch from previous transaction!";
            }
        }

        if ($amount > 20000) {
            $is_fraud = 1;
            $reason = "Transaction amount exceeds limit (₹20,000)!";
        }
        // ==========================================================

        // 3. Save the transaction
        $stmt = "INSERT INTO transactions (user_id, amount, location, is_fraud) VALUES ('$user_id', '$amount', '$location', '$is_fraud')";
        
        if ($conn->query($stmt)) {
            if ($is_fraud == 1) {
                // We use the $reason variable here to show the specific error
                echo "<div style='color:white; background:red; padding:20px; border-radius:10px;'>⚠️ FRAUD DETECTED: $reason</div>";
            } else {
                echo "<div style='color:white; background:green; padding:20px; border-radius:10px;'>✅ Transaction Verified & Successful.</div>";
            }
        }
    } else {
        echo "Account not found.";
    }
}
?>