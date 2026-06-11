<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        die("Unauthorized access.");
    }

    $account_no = trim($_POST['account_no']);
    $amount = floatval($_POST['amount']);
    $location = trim($_POST['location']);
    $user_id = $_SESSION['user_id'];

    // --- FRAUD DETECTION ENGINE ---
    $is_fraud = 0;
    $reason = "Transaction Verified";

    if ($amount > 20000) {
        $is_fraud = 1;
        $reason = "High Amount Alert (> ₹20,000)";
    }

    $loc_query = "SELECT last_location FROM users WHERE id = ?";
    $stmt = $conn->prepare($loc_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $user_data = $res->fetch_assoc();
    $last_location = $user_data['last_location'] ?? '';

    if (!empty($last_location) && strtolower($last_location) !== strtolower($location)) {
        $is_fraud = 1;
        $reason = "Location Mismatch (Last seen in " . htmlspecialchars($last_location) . ")";
    }

    // --- DATABASE LAYER: ACID TRANSACTION BLOCK ---
    $conn->begin_transaction();

    try {
        // 1. Insert into transactions
        $ins_query = "INSERT INTO transactions (user_id, amount, location, is_fraud) VALUES (?, ?, ?, ?)";
        $ins_stmt = $conn->prepare($ins_query);
        $ins_stmt->bind_param("idsi", $user_id, $amount, $location, $is_fraud);
        $ins_stmt->execute();

        $transaction_id = $conn->insert_id;

        // 2. Insert into fraud_logs if applicable
        if ($is_fraud == 1) {
            $fraud_query = "INSERT INTO fraud_logs (transaction_id, fraud_reason) VALUES (?, ?)";
            $fraud_stmt = $conn->prepare($fraud_query);
            $fraud_stmt->bind_param("is", $transaction_id, $reason);
            $fraud_stmt->execute();
        }

        // 3. Insert into transaction_history
        $status = ($is_fraud == 1) ? 'Fraud' : 'Safe';
        $history_query = "INSERT INTO transaction_history (transaction_id, user_id, amount, location, status) VALUES (?, ?, ?, ?, ?)";
        $history_stmt = $conn->prepare($history_query);
        $history_stmt->bind_param("iidss", $transaction_id, $user_id, $amount, $location, $status);
        $history_stmt->execute();

        // NOTE: The user's last_location is updated AUTOMATICALLY via the DB trigger here!
        
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        die("Critical Database Error. Transaction rolled back: " . $e->getMessage());
    }

    // --- PRESENTATION LAYER ---
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <title>Transaction Result</title>
        <style>
            body { font-family: 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background: #f0f4f8; margin: 0; }
            .result-card { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,0.05); text-align: center; max-width: 400px; width: 100%; }
            .status-icon { font-size: 50px; margin-bottom: 15px; }
            .btn { display: inline-block; background: #1e3a8a; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 20px; }
            .btn:hover { background: #1d4ed8; }
        </style>
    </head>
    <body>
        <div class="result-card">
            <?php if ($is_fraud == 1): ?>
                <div class="status-icon">🚩</div>
                <h2 style="color: #ef4444; margin-top: 0;">Fraud Detected!</h2>
                <p style="color: #991b1b; background: #fee2e2; padding: 12px; border-radius: 6px; font-weight: 500;">
                    <strong>Reason:</strong> <?php echo $reason; ?>
                </p>
            <?php else: ?>
                <div class="status-icon">✅</div>
                <h2 style="color: #22c55e; margin-top: 0;">Transaction Secure</h2>
                <p style="color: #15803d; background: #dcfce7; padding: 12px; border-radius: 6px; font-weight: 500;">
                    Your payment request was processed successfully.
                </p>
            <?php endif; ?>
            <a href="index.php" class="btn">Go Back</a>
        </div>
    </body>
    </html>
    <?php
}
?>