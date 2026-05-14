<?php
include 'db.php';

// Simplified query: No JOIN needed, pulling directly from transactions
$query = "SELECT id, account_no, amount, location, trans_time, is_fraud 
          FROM transactions 
          ORDER BY trans_time DESC";

$result = $conn->query($query);

if (!$result) {
    die("Query Failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Fraud Detection</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f7fc; padding: 40px; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        h2 { color: #1e3a8a; margin-bottom: 5px; }
        p { color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; border: 1px solid #e2e8f0; text-align: left; }
        th { background: #2c3e50; color: white; font-weight: 600; }
        .fraud-row { background: #fee2e2; color: #991b1b; font-weight: bold; }
        .safe-row { background: #f0fdf4; color: #166534; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; text-transform: uppercase; }
        .badge-fraud { background: #ef4444; color: white; }
        .badge-safe { background: #22c55e; color: white; }
        .back-link { display: inline-block; margin-top: 15px; color: #2563eb; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="container">
    <h2>🏦 Bank Admin Dashboard</h2>
    <p>Monitoring all recent transactions for suspicious activity.</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Account Number</th>
                <th>Amount (₹)</th>
                <th>Location</th>
                <th>Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr class="<?php echo $row['is_fraud'] ? 'fraud-row' : 'safe-row'; ?>">
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['account_no']); ?></td>
                        <td>₹<?php echo number_format($row['amount'], 2); ?></td>
                        <td><?php echo htmlspecialchars($row['location']); ?></td>
                        <td><?php echo $row['trans_time']; ?></td>
                        <td>
                            <?php if ($row['is_fraud']): ?>
                                <span class="badge badge-fraud">🚩 FRAUD FLAG</span>
                            <?php else: ?>
                                <span class="badge badge-safe">✅ Verified</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #666;">No transactions recorded yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="index.php" class="back-link">← Go to Transaction Form</a>
</div>

</body>
</html>