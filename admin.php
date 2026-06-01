<<<<<<< HEAD
<?php
session_start();

// Security check for admin access
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: signin.php");
    exit();
}

include 'db.php';

/* ==========================
   DASHBOARD STATISTICS
========================== */

$stats_query = "
SELECT
    COUNT(*) as total_trans,
    SUM(CASE WHEN is_fraud = 1 THEN 1 ELSE 0 END) as fraud_count,
    SUM(amount) as total_volume
FROM transactions
";

$stats_result = $conn->query($stats_query);
$stats = $stats_result->fetch_assoc();

$fraud_logs_count = $conn->query(
    "SELECT COUNT(*) AS total FROM fraud_logs"
)->fetch_assoc()['total'];

$history_count = $conn->query(
    "SELECT COUNT(*) AS total FROM transaction_history"
)->fetch_assoc()['total'];

$total_transactions = $stats['total_trans'] ?? 0;
$fraud_count = $stats['fraud_count'] ?? 0;
$total_volume = $stats['total_volume'] ?? 0;

$fraud_rate = $total_transactions > 0
    ? round(($fraud_count / $total_transactions) * 100, 2)
    : 0;

/* ==========================
   TRANSACTION LOG
========================== */

$query = "
SELECT
    transactions.*,
    users.name,
    users.account_no
FROM transactions
JOIN users
ON transactions.user_id = users.id
ORDER BY transactions.trans_time DESC
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard - Fraud Analytics</title>

<style>

body{
    font-family:'Segoe UI',Arial,sans-serif;
    background:#f4f7fc;
    padding:30px;
    margin:0;
}

.container{
    max-width:1300px;
    margin:auto;
    background:white;
    padding:30px;
    border-radius:12px;
    box-shadow:0 4px 20px rgba(0,0,0,0.05);
}

h2{
    color:#1e3a8a;
    margin-top:0;
}

.metrics-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
    margin-top:20px;
    margin-bottom:30px;
}

.metric-card{
    background:#f8fafc;
    padding:20px;
    border-radius:8px;
    border:1px solid #e2e8f0;
}

.metric-title{
    font-size:12px;
    text-transform:uppercase;
    color:#64748b;
    font-weight:bold;
}

.metric-value{
    font-size:24px;
    font-weight:bold;
    color:#1e293b;
    margin-top:10px;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

th{
    background:#1e293b;
    color:white;
    padding:15px;
    text-align:left;
}

td{
    padding:15px;
    border-bottom:1px solid #e2e8f0;
}

.fraud-row{
    background:#fff1f2;
}

.fraud-row td{
    color:#991b1b;
}

.badge{
    padding:6px 12px;
    border-radius:20px;
    font-size:11px;
    font-weight:bold;
}

.badge-fraud{
    background:#ef4444;
    color:white;
}

.badge-safe{
    background:#22c55e;
    color:white;
}

.logout{
    float:right;
    color:#ef4444;
    text-decoration:none;
    font-weight:bold;
}

.back-link{
    display:inline-block;
    margin-top:20px;
    text-decoration:none;
    color:#2563eb;
    font-weight:bold;
}

.section-title{
    color:#1e3a8a;
    margin-top:30px;
}

</style>
</head>

<body>

<div class="container">

<a href="logout.php" class="logout">
    Logout 🚪
</a>

<h2>🏦🛡️ Bank Admin Dashboard</h2>

<p style="color:#64748b;">
    Real-time management metrics and transactional risk intelligence.
</p>

<!-- MAIN DASHBOARD -->

<div class="metrics-grid">

    <div class="metric-card" style="border-left:4px solid #2563eb;">
        <div class="metric-title">Total Volume</div>
        <div class="metric-value">
            ₹<?php echo number_format($total_volume,2); ?>
        </div>
    </div>

    <div class="metric-card" style="border-left:4px solid #475569;">
        <div class="metric-title">Total Processed</div>
        <div class="metric-value">
            <?php echo $total_transactions; ?> txs
        </div>
    </div>

    <div class="metric-card" style="border-left:4px solid #ef4444;">
        <div class="metric-title">Fraud Blocked</div>
        <div class="metric-value">
            <?php echo $fraud_count; ?> rows
        </div>
    </div>

    <div class="metric-card" style="border-left:4px solid #eab308;">
        <div class="metric-title">System Risk Rate</div>
        <div class="metric-value">
            <?php echo $fraud_rate; ?>%
        </div>
    </div>

</div>

<!-- ADDITIONAL TABLE STATISTICS -->

<h3 class="section-title">
    Additional Database Statistics
</h3>

<div class="metrics-grid">

    <div class="metric-card" style="border-left:4px solid #9333ea;">
        <div class="metric-title">Fraud Logs</div>
        <div class="metric-value">
            <?php echo $fraud_logs_count; ?>
        </div>
    </div>

    <div class="metric-card" style="border-left:4px solid #059669;">
        <div class="metric-title">History Records</div>
        <div class="metric-value">
            <?php echo $history_count; ?>
        </div>
    </div>

</div>

<!-- TRANSACTION TABLE -->

<h3 class="section-title">
    Transaction Records
</h3>

<table>

<thead>
<tr>
    <th>ID</th>
    <th>Customer Name</th>
    <th>Account No</th>
    <th>Amount</th>
    <th>Location</th>
    <th>Time</th>
    <th>Status</th>
</tr>
</thead>

<tbody>

<?php if($result->num_rows > 0): ?>

<?php while($row = $result->fetch_assoc()): ?>

<tr class="<?php echo $row['is_fraud'] ? 'fraud-row' : ''; ?>">

<td>#<?php echo $row['id']; ?></td>

<td>
    <strong>
        <?php echo htmlspecialchars($row['name']); ?>
    </strong>
</td>

<td>
    <?php echo htmlspecialchars($row['account_no']); ?>
</td>

<td>
    ₹<?php echo number_format($row['amount'],2); ?>
</td>

<td>
    <?php echo htmlspecialchars($row['location']); ?>
</td>

<td>
    <?php echo date('d M Y H:i', strtotime($row['trans_time'])); ?>
</td>

<td>

<?php if($row['is_fraud']): ?>

<span class="badge badge-fraud">
🚩 FRAUD
</span>

<?php else: ?>

<span class="badge badge-safe">
✅ SAFE
</span>

<?php endif; ?>

</td>

</tr>

<?php endwhile; ?>

<?php else: ?>

<tr>
<td colspan="7" style="text-align:center;">
No transactions found.
</td>
</tr>

<?php endif; ?>

</tbody>

</table>

<a href="home.php" class="back-link">
← Return to Transaction Portal
</a>

</div>

</body>
=======
<?php
session_start();

// Security check for admin access
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: signin.php");
    exit();
}

include 'db.php';

// --- UPGRADE: ADVANCED AGGREGATION QUERY FOR ANALYTICS ---
$stats_query = "SELECT 
                    COUNT(*) as total_trans,
                    SUM(CASE WHEN is_fraud = 1 THEN 1 ELSE 0 END) as fraud_count,
                    SUM(amount) as total_volume
                FROM transactions";
$stats_result = $conn->query($stats_query);
$stats = $stats_result->fetch_assoc();

$total_transactions = $stats['total_trans'] ?? 0;
$fraud_count = $stats['fraud_count'] ?? 0;
$total_volume = $stats['total_volume'] ?? 0;
$fraud_rate = $total_transactions > 0 ? round(($fraud_count / $total_transactions) * 100, 2) : 0;

// Current transaction detailed log query
$query = "SELECT transactions.*, users.name, users.account_no 
          FROM transactions 
          JOIN users ON transactions.user_id = users.id 
          ORDER BY transactions.trans_time DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Fraud Analytics</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f7fc; padding: 30px; margin: 0; }
        .container { max-width: 1150px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        h2 { color: #1e3a8a; margin-top: 0; display: flex; align-items: center; gap: 10px; }
        
        /* Metrics Grid Layout */
        .metrics-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; margin-top: 20px; }
        .metric-card { background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; text-align: left; }
        .metric-title { font-size: 12px; text-transform: uppercase; color: #64748b; font-weight: bold; }
        .metric-value { font-size: 24px; font-weight: bold; color: #1e293b; margin-top: 5px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; overflow: hidden; border-radius: 8px; }
        th { background: #1e293b; color: white; padding: 15px; text-align: left; font-size: 13px; text-transform: uppercase; }
        td { padding: 15px; border-bottom: 1px solid #e2e8f0; font-size: 14px; color: #334155; }
        .fraud-row { background: #fff1f2; }
        .fraud-row td { color: #991b1b; }
        .badge { padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: bold; }
        .badge-fraud { background: #ef4444; color: white; }
        .badge-safe { background: #22c55e; color: white; }
        tr:hover { background-color: #f8fafc; transition: 0.2s; }
        .back-link { display: inline-block; margin-top: 25px; color: #2563eb; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>

<div class="container">
    <a href="logout.php" style="float: right; color: #ef4444; text-decoration: none; font-weight: bold;">Logout 🚪</a>
    <h2>🏦🛡️ Bank Admin Dashboard</h2>
    <p style="color: #64748b;">Real-time management metrics and transactional risk intelligence.</p>

    <div class="metrics-grid">
        <div class="metric-card" style="border-left: 4px solid #2563eb;">
            <div class="metric-title">Total Volume</div>
            <div class="metric-value">₹<?php echo number_format($total_volume, 2); ?></div>
        </div>
        <div class="metric-card" style="border-left: 4px solid #475569;">
            <div class="metric-title">Total Processed</div>
            <div class="metric-value"><?php echo $total_transactions; ?> txs</div>
        </div>
        <div class="metric-card" style="border-left: 4px solid #ef4444;">
            <div class="metric-title">Fraud Blocked</div>
            <div class="metric-value"><?php echo $fraud_count; ?> rows</div>
        </div>
        <div class="metric-card" style="border-left: 4px solid #eab308;">
            <div class="metric-title">System Risk Rate</div>
            <div class="metric-value"><?php echo $fraud_rate; ?>%</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer Name</th>
                <th>Account No</th>
                <th>Amount</th>
                <th>Location</th>
                <th>Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr class="<?php echo $row['is_fraud'] ? 'fraud-row' : ''; ?>">
                        <td>#<?php echo $row['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['account_no']); ?></td>
                        <td>₹<?php echo number_format($row['amount'], 2); ?></td>
                        <td><?php echo htmlspecialchars($row['location']); ?></td>
                        <td><?php echo date('d M, Y H:i', strtotime($row['trans_time'])); ?></td>
                        <td>
                            <?php if ($row['is_fraud']): ?>
                                <span class="badge badge-fraud">🚩 FRAUD FLAG</span>
                            <?php else: ?>
                                <span class="badge badge-safe">✅ VERIFIED</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 30px; color: #94a3b8;">No records logged in the database.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="home.php" class="back-link">← Return to Transaction Portal</a>
</div>

</body>
>>>>>>> 387c73c66cf8311e8491754526278b8dbc3fad42
</html>