<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $account_no = htmlspecialchars(trim($_POST['account_no']));
    $amount     = floatval($_POST['amount']);
    $payment    = htmlspecialchars(trim($_POST['payment_method']));
    $location   = htmlspecialchars(trim($_POST['location']));
    $time       = htmlspecialchars(trim($_POST['transaction_time']));

    if (empty($account_no) || empty($amount) || empty($location)) {
        die("Error: Missing required fields.");
    }

    // Rule-Based Engine
    $is_fraudulent = 0; // 0 for safe, 1 for fraud
    $reasons = [];

    if ($amount > 50000) { 
        $is_fraudulent = 1;
        $reasons[] = "Transaction amount exceeds threshold.";
    }
    if ($payment === 'credit_card' && $amount > 30000) {
        $is_fraudulent = 1;
        $reasons[] = "High-value credit card check.";
    }

    // Save transaction to DB (Assumes a simple columns structure)
    // Adjust column names if your database layout differs
    $stmt = $conn->prepare("INSERT INTO transactions (account_no, amount, location, trans_time, is_fraud) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sdssi", $account_no, $amount, $location, $time, $is_fraudulent);
    $stmt->execute();

    // Visual Result Wrapper
    echo "<!DOCTYPE html><html><head><title>Results</title><style>body{font-family:Arial; background:#f4f7fc; padding:40px;} .card{background:white; padding:20px; border-radius:8px; max-width:500px; margin:0 auto; box-shadow:0 2px 10px rgba(0,0,0,0.1); text-align:center;}</style></head><body><div class='card'>";
    if ($is_fraudulent) {
        echo "<h2 style='color:#dc2626;'>⚠️ Fraud Risk Detected</h2><p>Saved to log for investigation.</p>";
    } else {
        echo "<h2 style='color:#16a34a;'>✅ Transaction Approved</h2><p>Processed successfully.</p>";
    }
    echo "<hr><p><a href='index.php'>← Back to Form</a> | <a href='admin.php'>Go to Admin Dashboard →</a></p></div></body></html>";

    $stmt->close();
    $conn->close();
} else {
    header("Location: index.php");
    exit();
}
?>